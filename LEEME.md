# Laravel Cashier Package
Este paquete provee funcionalidad comun para la gestion en sesión para la venta de productos en general.
## Cómo instalar
1. Se recomienda instalar este paquete a través de composer
```sh
$ composer require enea/laravel-cashier
```

2. Opcionalmente, agregue el provedor a la llave `providers` en `config/app.php`
```php
'providers' => [
    // ...
    Enea\Cashier\Provider\CashierServiceProvider::class,
    // ...
],
```
## Uso Básico
El carrito de compra se identifica con un token que es generado a momento de su creación, es necesario proveer este token para poder modificar, eliminar y/o agregar productos.

```php
class SaleController extends Controller
{
    /**
     * Start the purchase
     */
    public function index(Client $client, Request $request )
    {
        $shopping = ShoppingManager::initialize( $client );
        
        $shopping->setPaymentDocument(new Invoice); // optional
        
        return response()->json([
            'token_cart' => $shopping->token( ),
            'shopping' => $shopping->toArray( )
        ]);
    }

    /**
     * Add a product to shopping cart
     */
    public function add(Product $product, Request $request)
    {
        $shopping = ShoppingManager::find($request->header('CART-TOKEN'));
        
        return response()->json([
            'added' => $shopping->push($product, $request->get('quantity')), // true or false
            'shopping' => $shopping->toArray( )
        ]);
    }
    
    /**
     * Remove a product to shopping cart
     */
    public function remove(Request $request)
    {
        $shopping = ShoppingManager::find($request->header('CART-TOKEN'));

        return response()->json([
            'removed' => $shopping->remove($request->get('product_key')), // true o false
            'shopping' => $shopping->toArray( )
        ]);
    }
    
    /**
     * Change the amount
     */
    public function change( Request $request )
    {
        $shopping = ShoppingManager::find( $request->header('CART-TOKEN'));
        $product = $shopping->find($request->get('key_product'));

        if (is_null( $product )) {
            abort(404);
        }

        $product->setQuantity(2);

        return response()->json([
            'product' => $product,
            'shopping' => $shopping->toArray( )
        ]);
    }
}
```
 Vea la clase que se encarga de hacer los calculos [`Enea\Cashier\Calculator`](https://github.com/eneasdh-fs/laravel-cashier/blob/master/src/Calculator.php)
```php
    class StoreSaleController extends Controller
    {
        public function store( Request $request )
        {
            $shopping = ShoppingManager::find( $request->header('CART-TOKEN'));
            $products = $this->build( $shopping );
    
            $payment = DB::Transaction(function( ) use( $products, $shopping ){
    
                $payment = Payment::create(
                    //$shopping->toArray()
                );
    
                PaymentItem::insert( $products );
    
                return $payment;
            });
    
            return response()->json([
                'success' => true,
                'document' => $payment
            ]);
        }
        protected function build( ShoppingCart $cart )
        {
            $products = array( );
    
            $cart->collection()->each(function( SalableItem $item) use( & $products) {
                // $products[ ] = $item->getCalculator( )->toArray( );
            });
    
            return $products;
        }
    }
    
```


## Interfaces
El paquete ofrece una serie de interfaces que convierten un modelo en un determinado actor.
    
- #### Enea\Cashier\Contracts\BuyerContract:
    Representa al `comprador`, el modelo del cliente deberá implementar está interface para poder iniciar la compra.
    ``` php
    public function index(Client $client, Request $request )
    {
        $shopping = ShoppingManager::initialize( $client );
        
        // ..
    }
    ```
- #### Enea\Cashier\Contracts\SalableContract:
    Representa al `elemento a vender`. Ofrece algunas propiedades necesarias para realizar los calculos.
    ```php
    public function add( Product $product, Request $request)
    {
        $shopping = ShoppingManager::find($request->header('CART-TOKEN'));
        $shopping->push($product, $request->get('quantity'));
        
        // ..
    }
    ```
- #### Enea\Cashier\Contract\AccountContract
    Representa una cuenta a pagar, debe ser adjuntada al carrito de la compra.
La implementación de esta interfaz limitará los elementos para elegir proporcionando una lista personalizada.
Caso de ejemplo:
Una pre-factura a liquidar, ésta tiene elementos que se han cargado desde que se abrió la pre-factura. En este caso,
es necesario validar que los artículos a pagar estén dentro del detalle de dicha prefactura.
    ```php
    public function index(Preinvoice $preinvoice, Request $request )
    {
        $client = $preinvoice->client;
        $shopping = ShoppingManager::initialize( $client )
            ->attach($preinvoice);

        // ..
    }
    ```
- #### Enea\Cashier\Contracts\AccountElementContract:
    Representa a un elemento dentro del la cuenta
    ```php
    public function add(Request $request)
    {
        $shopping = ShoppingManager::find($request->header('CART-TOKEN'));
        $shopping->pull($request->get('product_key'));
        
        // ..
    }
    ```
- #### Enea\Cashier\Contracts\DiscountableContract:
    Al implementar esta interfaz, es posible asignar un descuento porcentual sobre un elemento, se usa en conjunto con la interface `SalableContract`o `AccountElementContract` para alertar al paquete que es posible aplicar un descuento a dicho elemento.
    ```php
    class Product extends Model implements SalableContract, DiscountableContract
    {
        /**
         * Get the item discount in percentage
         * @return int
         */
        public function getDiscountPercentage(): int
        {
            return // Percentage discount
        }
    }
    ```
- #### Enea\Cashier\Contracts\CalculatorContract:
    En caso de que la clase encargada de realizar los calculos que viene configurada en el paquete no se ajuste a su realidad, es posible implementar esta interface o extender de la clase `Enea\Cashier\Calculator` para modficar su comportamiento.
    ```php
    class CustomCalculator implements CalculatorContract
    {
        //
    }
    ```
- #### Enea\Cashier\Contracts\DocumentContract:
    Representa el tipo de documento y especifica los impuestos.
    ```php
    class Invoice implements DocumentContract
    {
        protected const IGV = 18;
    
        /**
         * @var BusinessOwner
         */
        protected $owner;
    
        /**
         * Invoice constructor.
         * @param BusinessOwner $owner
         */
        public function __construct( BusinessOwner $owner = null )
        {
            $this->owner = $owner;
        }
    
        /**
         * Get tax percentage
         * @return int
         */
        public function getTaxPercentageAttribute(): int
        {
            return self::IGV;
        }
    
        /**
         * Returns the owner of social reason
         * @return BusinessOwner
         * */
        public function getBusinessOwner(): ?BusinessOwner
        {
            return $this->owner;
        }
    }
    ```
- #### Enea\Cashier\Contracts\BusinessOwner:
    Representa la razon social de una factura.
    ```php
    class Owner implements BusinessOwner
    {
        /**
         * Identification of the owner of the business name
         *
         * @return int|string
         * */
        public function getBusinessOwnerKey( )
        {
            return $this->getKey();
        }
    
        /**
         * Returns the taxpayer's unique identification
         *
         * @return string
         */
        public function getTaxpayerIdentification( ): string 
        {
            return $this->ruc;
        }
        /**
         * Returns the social reason
         *
         * @return string
         */
        public function getDescription( ): string
        {
            return $this->name;
        }
        
    }
    ```
## Más documentación

Se puede encontrar gran cantidad de comentarios dentro del código fuente al igual que en las pruebas ubicadas en el directorio `tests`.

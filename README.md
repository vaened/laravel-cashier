# Laravel Cashier Package
This package provides common functionality for session management for the sale of products in general.
## How to install
1. It is recommended to install this package through composer
```sh
$ composer require enea/laravel-cashier
```

2. Optionally, add the provider to the `providers` key in` config / app.php`
```php
'providers' => [
    // ...
    Enea\Cashier\Provider\CashierServiceProvider::class,
    // ...
],
```
## Basic Usage
The shopping cart is identified with a ring that is generated at the time of its creation, it is necessary to provide this ring to modify, delete and / or add products.

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
 See the class in charge of doing the calculations [`Enea\Cashier\Calculator`](https://github.com/eneasdh-fs/laravel-cashier/blob/master/src/Calculator.php)
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
The package offers a series of interfaces that convert a model to a particular actor.
    
- #### Enea\Cashier\Contracts\BuyerContract:
    Represents the `buyer`, the model the client must implement is an interface to be able to start the purchase.
    ``` php
    public function index(Client $client, Request $request )
    {
        $shopping = ShoppingManager::initialize( $client );
        
        // ..
    }
    ```
- #### Enea\Cashier\Contracts\SalableContract:
    Represents the `element to sell`. It provides some properties needed to perform the calculations.
    ```php
    public function add( Product $product, Request $request)
    {
        $shopping = ShoppingManager::find($request->header('CART-TOKEN'));
        $shopping->push($product, $request->get('quantity'));
        
        // ..
    }
    ```
- #### Enea\Cashier\Contract\AccountContract
    Represents an account payable, must be attached to the shopping cart.
    The implementation of this interface allows the elements to choose to include a custom list.
    Example case:
    A pre-invoice to be settled, this has items that have been loaded since the pre-invoice was opened. In this case,
    It is necessary to validate that the articles are paid within the detail of said prefacture.

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
    Represents an item within the account.
    ```php
    public function add(Request $request)
    {
        $shopping = ShoppingManager::find($request->header('CART-TOKEN'));
        $shopping->pull($request->get('product_key'));
        
        // ..
    }
    ```
- #### Enea\Cashier\Contracts\DiscountableContract:
    When implementing this interface, it is possible to assign a percentage discount on an element, it is used in conjunction with the `SalableContract` or` AccountElementContract` interface to alert the package that it is possible to apply a discount to that element. Represents an element within the count
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
    In case the class in charge of performing the calculations that is configured in the package does not fit its reality, it is possible to implement this interface or extend of the `Enea\Cashier\Calculator` class to modify its behavior.
    ```php
    class CustomCalculator implements CalculatorContract
    {
        //
    }
    ```
- #### Enea\Cashier\Contracts\DocumentContract:
    Represents the document type and specifies the taxes.
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
    Represents the Social Reason of an invoice.
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
## More documentation

You can find a lot of comments within the source code as well as the tests located in the `tests` directory.

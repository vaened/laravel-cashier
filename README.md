# Deprecated - Laravel Cashier Package
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

**This repository is marked as deprecated and has been replaced by a better and more comprehensive version.**

**Reason**: We have released [Swift Cart](https://github.com/vaened/swift-cart), which offers enhanced functionalities, a more user-friendly experience, and improved performance. As a result, this repository will no longer receive updates.

**Deprecation Date**: August 4st, 2023.

This package provides a functionality to manage the sale of products and abstracts all the calculations you need for a sale.
```php
// create a shopping cart
$document = Invoice::create()->using([Taxes::IVA]);
$shoppingCart = ShoppingManager::initialize($client, $document);

// add a global discount
$discount = Discount::percentage(15)->setCode('PROMOTIONAL');
$shoppingCart->addDiscount($discount);

// add products
$keyboard = $shoppingCart->push(Product::find(1), 5);
$keyboard->addDiscount(Discount::percentage(8)->setCode('ONLY-TODAY'));

$backpack = $shoppingCart->push(Product::find(2));
$backpack->setQuantity(10);

// get totals
$shoppingCart->getSubtotal();
$shoppingCart->getTotalDiscounts();
$shoppingCart->getTotalTaxes();
$shoppingCart->getTotal();
```

## Installation

Laravel Cashier requires PHP 7.4. This version supports Laravel 7

To get the latest version, simply require the project using Composer:
```sh
$ composer require enea/laravel-cashier
```

And publish the configuration file.

```sh
$ php artisan vendor:publish --provider='Enea\Cashier\CashierServiceProvider'
```

## Class reference

This table defines the implementation of the models necessary for the operation of the package, there are some models that come to help such as: `$discount` and `$document`, however it is recommended to replace these models with your own.

| **Concrete**     | **Abstract**                                                 | **Description**               |
| ---------------- | ------------------------------------------------------------ | ----------------------------- |
| `$client`        | [`Enea\Cashier\Contracts\BuyerContract`](/src/Contracts/BuyerContract.php) | person who makes the purchase |
| `$document`      | [`Enea\Cashier\Contracts\DocumentContract`](/src/Contracts/DocumentContract.php) | type of sale document         |
| `$product`       | [`Enea\Cashier\Contracts\ProductContract`](/src/Contracts/ProductContract.php) | product being sold            |
| `$quote`         | [`Enea\Cashier\Contracts\QuoteContract`](/src/Contracts/QuoteContract.php) | quote available for sale      |
| `$quotedProduct` | [`Enea\Cashier\Contracts\QuotedProductContract`](/src/Contracts/QuotedProductContract.php) | quoted product to sell        |
| `$discount`      | [`Enea\Cashier\Modifiers\DiscountContract`](/src/Modifiers/DiscountContract.php) | representation of a discount  |
| `$tax`           | [`Enea\Cashier\Modifiers\TaxContract`](/src/Modifiers/TaxContract.php) | representation of a tax       |

## Usage

To start a purchase you must use the `ShoppingManager::initialize($client, $document)`.

```php
use App\Client;
use Enea\Cashier\Documents\Invoice;
use Enea\Cashier\Facades\ShoppingManager;

$document = Invoice::create()->using([Taxes::IVA]); // or use your own model
$shoppingCart = ShoppingManager::initialize(Client::find(10), $document);
```

When you initialize a shopping cart, a token is generated so that it can be searched from `ShoppingManager::find($token)`. This function gets the shopping cart from session.
```php
$token = $shoppingCart->getGeneratedToken();
$shoppingCart = ShoppingManager::find($token); // returns the shopping cart that matches the token
```

It is also possible that you want to invoice a quote, and to do so you must call the `attach` function of the `$shoppingCart`. Doing this creates a [`QuoteManager`](/src/QuoteManager.php) instance inside the Shopping Cart, which can be accessed from the `$shoppingCart->getQuoteManager()` function.
```php
$shoppingCart->attach($quote);
```

Now you just have to add products to the shopping cart using `$shoppingCart->push($product, $quantity)`.

```php
$keyboard = Product::query()->where('description', 'Keyboard K530-rgb')->firstOrFail();
$productCartItem = $shoppingCart->push($keyboard, 4);
```

Or you can also pull products from the quote using `$shoppingCart->pull($productID)`.

```php
$productCartItem = $shoppingCart->pull($productID);
```

The `push` and `pull` methods returns an instance of [`ProductCartItem`](/src/Items/ProductCartItem.php), which provides a lot of useful method.

```php
// set product quantity
$productCartItem->setQuantity(10);

// configure custom properties
$productCartItem->setProperty(['key' => 'value']);
$productCartItem->putProperty('key', 'value');
$productCartItem->removeProperty('key');

// manage discounts
$productCartItem->addDiscounts($discounts);
$productCartItem->addDiscount($discount);
$productCartItem->getDiscount($discountCode);
$productCartItem->removeDiscount($discountCode);

// get the totals
$cashier = $productCartItem->getCashier();
$cashier->getUnitPrice();
$cashier->getGrossUnitPrice();
$cashier->getNetUnitPrice();
$cashier->getQuantity();
$cashier->getSubtotal();
$cashier->getTotalDiscounts();
$cashier->getTotalTaxes();
$cashier->getTotal();
```

### Example

For this example, we are going to simulate a simple purchase. We need a `$client` and we will use a `$invoice` with `IVA` as a sales document.

```php
class ShoppingCartController extends Controller
{
  public function start(Client $client): JsonResponse
  {
    $shoppingCart = ShoppingManager::initialize($client);        
    $shoppingCart->setDocument(Invoice::create()->using([Taxes::IGV])); 

    return response()->json([
      'token' => $shoppingCart->getGeneratedToken(),
      'shoppingCart' => $shoppingCart->toArray()
    ]);
  }

  public function addGlobalDiscount(Discount $discount, Request $request): JsonResponse
  {
    $shoppingCart = ShoppingManager::find($request->header('CART-TOKEN'));
    $shoppingCart->addDiscount($discount);

    return response()->json(compact('shoppingCart'));
  }

  public function removeGlobalDiscount(Discount $discount, Request $request): JsonResponse
  {
    $shoppingCart = ShoppingManager::find($request->header('CART-TOKEN'));
    $shoppingCart->removeDiscount($discount->getDiscountCode());

    return response()->json(compact('shoppingCart'));
  }
}
```

Now we add a controller to manage shopping cart products.

```php
class ProductManagerController extends Controller
{
  public function addProduct(Product $product, Request $request): JsonResponse
  {
    $shoppingCart = ShoppingManager::find($request->header('CART-TOKEN'));
    $added = $shoppingCart->push($product, $request->get('quantity'));

    return response()->json(compact('shoppingCart', 'added'));
  }

  public function removeProduct(string $productID, Request $request): JsonResponse
  {
    $shoppingCart = ShoppingManager::find($request->header('CART-TOKEN'));
    $shoppingCart->remove($productID);

    return response()->json(compact('shoppingCart'));
  }

  public function updateProductQuantity(string $productID, Request $request): JsonResponse
  {
    $shoppingCart = ShoppingManager::find($request->header('CART-TOKEN'));
    $product = $shoppingCart->find($productID);
    $product->setQuantity($request->get('quantity'));

    return response()->json(compact('shoppingCart', 'product'));
  }

  public function addDiscountToProduct(string $productID, Discount $discount, Request $request): JsonResponse
  {
    $shoppingCart = ShoppingManager::find($request->header('CART-TOKEN'));
    $product = $shoppingCart->find($productID);
    $product->addDiscount($discount);

    return response()->json(compact('shoppingCart'));
  }  

  public function removeDiscountToProduct(string $productID, Discount $discount, Request $request): JsonResponse
  {
    $shoppingCart = ShoppingManager::find($request->header('CART-TOKEN'));
    $product = $shoppingCart->find($productID);
    $product->removeDiscount($discount);

    return response()->json(compact('shoppingCart'));
  }
}
```

And to finish we save the document in the database.

```php
class PurchaseController extends Controller
{
  public function store(Request $request): JsonResponse
  {
    $shoppingCart = ShoppingManager::find($request->header('CART-TOKEN'));
    $products = $shoppingCart->collection()->map($this->toOrderProduct());

    $order = DB::Transaction($this->createOrder($shoppingCart, $products));
    $dropped = $this->destroyShoppingCart($shoppingCart->getGeneratedToken());

    return response()->json(compact('order', 'dropped'));
  }

  private function createOrder(ShoppingCart $cart, Collection $products): Closure
  {
    return function() use ($cart, $products): Order {
      $order = Order::create([
        // complete the structure of your model
        'subtotal' => $cart->getSubtotal(),
        'total' => $cart->getTotal(),
        'document_id'd => $cart->getDocument()->getUniqueIdentificationKey(),
      ]);                
      $order->detail()->saveMany($products);    
      return $order;
    };            
  }

  private function toOrderProduct(): Closure
  {
    return fn(ProductCartItem $product) => new OrderProduct([
      'product_id' => $product->getUniqueIdentificationKey(),
      'quantity' => $product->getQuantity(),
      'unit_price' => $product->getCashier()->getUnitPrice(),
      'discount' => $product->getCashier()->getTotalDiscounts(),
      'iva_pct' =>  $product->getTax('IVA')->getPercentage(),
    ]);
  }

  private function destroyShoppingCart(string $token): bool
  {
    ShoppingManager::drop($token);
    return !ShoppingManager::has($token);
  }
}
```

## Cashier

It is responsible for centralizing the calculations to get taxes, discounts and totals for each product. you can find it in [**Enea\Cashier\Calculations\Cashier**](/src/Calculations/Cashier.php).

| **Method** | **Description** | **Return** |
| ---------- | ---- | ------ |
| **`getUnitPrice()`** | unit sales price | float |
| `getGrossUnitPrice()` | gross price (price without tax) | float |
| `getNetUnitPrice()` | net price (price + taxes) | float |
| `getSubtotal()` | subtotal | float |
| `getTotalDiscounts()` | total discounts | float |
| `getTotalTaxes()` | total taxes | float |
| `getTotal()` | final total with discounts and taxes | float |
| `getTaxes()` | all taxes grouped by name | [**Taxed[]**](/src/Calculations/Taxed.php) |
| ``getTax(string $name)`` | tax by name | [**Taxed**](/src/Calculations/Taxed.php) |
| ``getDiscounts()`` | all discounts grouped by code | [**Discounted[]**](/src/Calculations/Discounted.php) |
| ``getDiscount(string $code)`` | discount by code | [**Discounted**](/src/Calculations/Discounted.php) |

### Pricing

Cashier separates the prices into 3, `getGrossUnitPrice()`, `getNetUnitPrice()` and `getUnitPrice()`,  where the latter is the unit price after evaluating taxes, both **included** and **excluded**. `$cashier->getUnitPrice()` is the function used for all calculations. You can see an example in code from [`Enea\Tests\Calculations\PriceTest`](/tests/Calculations/PriceTest.php)

| **Method**         | getGrossUnitPrice()       | getNetUnitPrice()         | getUnitPrice()            |
| ------------------ | ------------------------- | ------------------------- | ------------------------- |
| **Base**           | 100.00 $USD               | 100.00 $USD               | 100.00 $USD               |
| **Included Taxes** | IVA(12%), AnotherTax(11%) | IVA(12%), AnotherTax(11%) | IVA(12%), AnotherTax(11%) |
| **Tax to use**     | IVA(12%)                  | IVA(12%)                  | IVA(12%)                  |
| **Applied**        | -                         | IVA and AnotherTax        | IVA                       |
| **Total**          | 81.30 $USD                | 100 $USD                  | 90.24 $USD                |

## Configuration

There are a few things you need to know to set up taxes and discounts correctly.

- [**Enea\Cashier\Modifiers\DiscountContract**](/src/Modifiers/DiscountContract.php)

  Represents an applicable discount. There is quite a functional helper implementation in [`Enea\Cashier\Modifiers\Discount`](src/Modifiers/Discount.php) so it is not totally necessary to assign your own model, unless you want full control over the discount codes.

  ```php
  namespace Enea\Cashier\Modifiers;
  
  use Enea\Cashier\Calculations\Percentager;
  use Enea\Cashier\Modifiers\DiscountContract;
  
  class Discount implements DiscountContract
  {
      public function getDiscountCode(): string
      {
          return $this->code;
      }
  
      public function getDescription(): string
      {
          return $this->description;
      }
  
      public function extract(float $total): float
      {
          if (! $this->percentage) {
              return $this->discount;
          }
  				// logic to calculate a percentage discount
          return Percentager::excluded($total, $this->discount)->calculate();
      }
  }
  ```

- [**Enea\Cashier\Contracts\DocumentContract**](/src/Contracts/DocumentContract.php)

  Represents the type of document with which the sale will be made and also defines the taxes that will be applied to the products.

  ```php
  namespace App\Models;
  
  use Enea\Cashier\Taxes;
  use Enea\Cashier\Contracts\DocumentContract;
  use Illuminate\Database\Eloquent\Model;
  
  class Document extends Model implements DocumentContract
  {
      public function taxesToUse(): array
      {
        	// some logic
          return [
            Taxes::IGV, // tax name
          ];
      }
  }
  ```

- [**Enea\Cashier\Modifiers\TaxContract**](/src/Modifiers/TaxContract.php)

  Represents the tax on the `product`, the package has a help implementation which can be found in [`Enea\Cashier\Modifiers\Tax`](/src/Modifiers/Tax.php)
  
  ```php
  namespace App\Models;
  
  use Enea\Cashier\Contracts\ProductContract;
  use Enea\Cashier\Modifiers\Tax;
  use Enea\Cashier\Taxes;
  
  class Product extends Model implements ProductContract
  {
      public function getUnitPrice(): float
      {
          return $this->sale_price;
      }
  
      public function getShortDescription(): string
      {
          return $this->short_description;
      }
  
      public function getTaxes(): array
      {
          return [
              Tax::included(Taxes::IGV, $this->igv_pct),
          ];
      }
  }
  ```
  
  To use taxes it is necessary to understand that they can be configured in 2 ways, **included** and **excluded**
  
  | **Type**       | **INCLUDED** | **EXCLUDED** |
  | -------------- | ------------ | ------------ |
  | **Unit Price** | 100.00 $USD  | 100.00 $USD  |
  | **Tax %**      | 10%          | 10%          |
  | **Total Tax**  | 9.09 $USD    | 10.00 $USD   |
  | **Net Price**  | 100.00 $USD  | 110.00 $USD  |


## More documentation

You can find a lot of comments within the source code as well as the tests located in the `tests` directory.


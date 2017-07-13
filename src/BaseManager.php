<?php
/**
 * Created by enea dhack - 30/05/2017 03:19 PM.
 */

namespace Enea\Cashier;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Collection;

abstract class BaseManager implements Arrayable, Jsonable
{
    /**
     * Identification.
     *
     * @var string
     * */
    protected $token;

    /**
     * Temporary storage of account items.
     *
     * @var Collection
     */
    protected $storage;

    /**
     * Custom properties.
     *
     * @var Collection
     */
    protected $properties;

    /**
     * Default igv percentage.
     *
     * @var int
     */
    protected $impostPercentage = Calculator::ZERO;

    /**
     * Selected items.
     *
     * @var  Collection
     * */
    private $collection;

    /**
     * BaseManager constructor.
     */
    public function __construct()
    {
        $this->clean();
    }

    /**
     * Build subtotal items.
     *
     * @return float
     */
    public function getSubtotal()
    {
        return $this->collection->sum(function (BaseItem $item) {
            return $item->getCalculator()->getSubtotal();
        });
    }

    /**
     * Build definite total.
     *
     * @return float
     */
    public function getDefinitiveTotal()
    {
        return $this->collection->sum(function (BaseItem $item) {
            return $item->getCalculator()->getDefinitiveTotal();
        });
    }

    /**
     * Build total tax.
     *
     * @return float
     */
    public function getImpost()
    {
        return $this->collection->sum(function (BaseItem $item) {
            return $item->getCalculator()->getImpost();
        });
    }

    /**
     * Returns the discount applied to the item.
     *
     * @return float
     */
    public function getDiscount()
    {
        return $this->collection->sum(function (BaseItem $item) {
            return $item->getCalculator()->getDiscount();
        });
    }

    /**
     * Returns the plan discount.
     *
     * @return float
     */
    public function getPlanDiscount()
    {
        return $this->collection->sum(function (BaseItem $item) {
            return $item->getCalculator()->getPlanDiscount();
        });
    }

    /**
     * Returns the total discount.
     *
     * @return float
     */
    public function getTotalDiscounts()
    {
        return $this->collection->sum(function (BaseItem $item) {
            return $item->getCalculator()->getTotalDiscounts();
        });
    }

    /**
     * Returns the tax percentage.
     *
     * @return int
     */
    public function getImpostPercentage()
    {
        return $this->impostPercentage;
    }

    /**
     * Add a new item to the collection.
     *
     * @param $key
     * @param BaseItem $item
     * @return void
     */
    protected function add($key, BaseItem $item)
    {
        $this->collection->put($key, $item);
    }

    /**
     * Filter items that have not been marked as deleted.
     *
     * @return  Collection
     */
    public function collection()
    {
        return $this->collection;
    }

    /**
     * Clean the collection.
     *
     * @return  void
     * */
    public function clean()
    {
        $this->storage = collect();
        $this->collection = collect();
        $this->properties = collect();
    }

    /**
     * Count the number of items in the collection.
     *
     * @return int
     */
    public function count()
    {
        return $this->collection()->count();
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'token' => $this->token(),
            'subtotal' => $this->getSubtotal(),
            'definitive_total' => $this->getDefinitiveTotal(),
            'impost' => $this->getImpost(),
            'discount' => $this->getDiscount(),
            'plan_discount' => $this->getPlanDiscount(),
            'total_discounts' => $this->getTotalDiscounts(),
            'impost_percentage' => $this->getImpostPercentage(),
            'elements' => $this->collection()->toArray(),
        ];
    }

    /**
     * Returns the dynamic properties that were added to the cart.
     *
     * @return Collection
     */
    public function properties()
    {
        return $this->properties;
    }

    /**
     * Returns true in case the property passed by parameter exists in the cart.
     *
     * @param $property
     * @return bool
     */
    public function hasProperty($property)
    {
        return isset($this->properties[$property]);
    }

    /**
     * Returns property passed by parameter, and null, if not found.
     *
     * @param $property
     * @return mixed
     */
    public function getProperty($property)
    {
        return $this->properties()->get($property);
    }

    /**
     * Set a dynamic property.
     *
     * @param string $property
     * @param mixed $value
     * @return $this
     */
    public function setProperty($property, $value)
    {
        $this->properties()->put($property, $value);
        return $this;
    }

    /**
     * Returns only the value of the elements leaving aside the keys.
     *
     * @return Collection
     */
    public function lists()
    {
        return $this->collection()->values();
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int $options
     *
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * Generate a more truly "random" alpha-numeric string.
     *
     * @return string
     */
    public function token()
    {
        return $this->token ?: $this->token = str_random(30);
    }
}

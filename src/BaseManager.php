<?php
/**
 * Created by enea dhack - 30/05/2017 03:19 PM.
 */

namespace Enea\Cashier;

use Countable;
use Enea\Cashier\Contracts\AttributableContract;
use Enea\Cashier\Contracts\BuyerContract;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Collection;

abstract class BaseManager implements Arrayable, Jsonable, AttributableContract, Countable
{
    use IsJsonable, HasAttributes;

    /**
     * Customer cart owner.
     *
     * @var BuyerContract
     */
    protected $buyer;

    /**
     * Custom properties.
     *
     * @var Collection
     */
    protected $attributes;

    /**
     * Selected items.
     *
     * @var  Collection
     * */
    protected $collection;

    /**
     * Identification token.
     *
     * @var string
     * */
    private $token;

    /**
     * BaseManager constructor.
     *
     * @param BuyerContract $buyer
     */
    public function __construct(BuyerContract $buyer)
    {
        $this->initialize();
        $this->buyer = $buyer;
    }

    /**
     * Build subtotal items.
     *
     * @return float
     */
    public function getSubtotal()
    {
        return Helpers::decimalFormat($this->collection->sum(function (BaseItem $item) {
            return $item->getCalculator()->getCleanSubtotal();
        }));
    }

    /**
     * Build definite total.
     *
     * @return float
     */
    public function getDefinitiveTotal()
    {
        return Helpers::decimalFormat($this->collection->sum(function (BaseItem $item) {
            return $item->getCalculator()->getCleanDefinitiveTotal();
        }));
    }

    /**
     * Build total tax.
     *
     * @return float
     */
    public function getTotalTaxes()
    {
        return Helpers::decimalFormat($this->collection->sum(function (BaseItem $item) {
            return $item->getCalculator()->getCleanTaxes();
        }));
    }

    /**
     * Returns the discount applied to the item.
     *
     * @return float
     */
    public function getTotalDiscounts()
    {
        return Helpers::decimalFormat($this->collection->sum(function (BaseItem $item) {
            return $item->getCalculator()->getCleanDiscounts();
        }));
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
     * Returns buyer instance.
     *
     * @return BuyerContract
     */
    public function buyer()
    {
        return $this->buyer;
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
     * Generate a more truly "random" alpha-numeric string.
     *
     * @return string
     */
    public function getGeneratedToken()
    {
        return $this->token ?: $this->token = str_random(30);
    }

    /**
     * Returns the calculated amounts.
     *
     * @return array
     */
    public function getArrayableCalculator()
    {
        return [
            'subtotal' => $this->getSubtotal(),
            'definitive_total' => $this->getDefinitiveTotal(),
            'total_taxes' => $this->getTotalTaxes(),
            'total_discounts' => $this->getTotalDiscounts(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return $this->collection()->count();
    }

    /**
     * {@inheritdoc}
     * */
    public function getAdditionalAttributes()
    {
        return $this->attributes;
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return array_merge($this->getArrayableCalculator(), [
            'token' => $this->getGeneratedToken(),
            'elements' => $this->collection()->toArray(),
        ]);
    }

    /**
     * Add a new item to the collection.
     *
     * @param BaseItem $item
     * @return void
     */
    protected function add(BaseItem $item)
    {
        $this->collection()->put($item->getElementKey(), $item);
    }

    /**
     * Initialize variables.
     *
     * @return void
     */
    private function initialize()
    {
        $this->attributes = collect();
        $this->collection = collect();
    }
}

<?php
/**
 * Created by enea dhack - 17/06/17 01:09 PM.
 */

namespace Enea\Tests\Models;

use Enea\Cashier\Contracts\AccountContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Preinvoice extends Model implements AccountContract
{
    protected $fillable = ['id', 'custom_property'];

    public $incrementing = false;

    /**
     * Returns identification one in the database - primary key.
     *
     * @return string
     */
    public function getKeyIdentification()
    {
        return $this->getKey();
    }

    /**
     * {@inheritdoc}
     * */
    public function getAdditionalAttributes()
    {
        // TODO: Implement getAdditionalAttributes() method.
    }

    /**
     * Returns elements that implement 'SalableContract'.
     *
     * @return Collection
     */
    public function getElements()
    {
        return collect([
            new PreinvoiceItem([
                'id' => 100,
                'price' => 130.50,
                'quantity' => 3,
                'description' => 'some description',
                'taxable' => true,
            ]),
            new PreinvoiceItem([
                'id' => 101,
                'price' => 530.30,
                'quantity' => 1,
                'description' => 'some description',
                'taxable' => true,
            ]),
            new PreinvoiceItem([
                'id' => 102,
                'price' => 10.50,
                'quantity' => 5,
                'description' => 'some description',
                'taxable' => true,
            ]),
            new PreinvoiceItem([
                'id' => 103,
                'price' => 30.40,
                'quantity' => 2,
                'description' => 'some description',
                'taxable' => true,
            ]),
        ]);
    }
}

<?php
/**
 * Created by enea dhack - 10/08/2020 0:25.
 */

namespace Enea\Tests\Calculations;

use Enea\Cashier\Calculations\Discounted;
use Enea\Cashier\Modifiers\Discount;
use Enea\Tests\TestCase;

class DiscountedTest extends TestCase
{
    public function test_discount(): void
    {
        $discount = new Discount('CODE', 10, 'Description', true, []);

        $this->assertEquals('CODE', $discount->getDiscountCode());
        $this->assertEquals('Description', $discount->getDescription());
        $this->assertEmpty($discount->getProperties());
        $this->assertEquals(80, $discount->extract(800));
    }

    public function test_percentage_discount(): void
    {
        $discounted = new Discounted(Discount::percentage(10), 400.0);
        $this->assertEquals(40, $discounted->getTotal());
    }

    public function test_discount_by_value(): void
    {
        $discounted = new Discounted(Discount::value(150), 800.5);
        $this->assertEquals(150, $discounted->getTotal());
    }

    public function test_help_methods_to_obtain_the_discount(): void
    {
        $discount = Discount::percentage(10)->setCode('EXAMPLE')->setDescription('promotional');
        $discounted = new Discounted($discount, 500);

        $this->assertEquals('promotional', $discounted->getDescription());
        $this->assertEquals('EXAMPLE', $discounted->getDiscountCode());
    }

    public function test_transform_discount_to_array(): void
    {
        $discount = Discount::percentage(10, ['property' => 'value']);
        $discount->setCode('EXAMPLE')->setDescription('promotional');
        $discounted = new Discounted($discount, 500);

        $this->assertEquals([
            'code' => 'EXAMPLE',
            'description' => 'promotional',
            'total' => 50.0,
            'properties' => [
                'property' => 'value'
            ],
        ], $discounted->toArray());
    }
}

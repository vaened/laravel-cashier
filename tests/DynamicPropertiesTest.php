<?php
/**
 * Created by enea dhack - 12/07/17 07:42 PM.
 */

namespace Enea\Tests;

use stdClass;

class DynamicPropertiesTest extends TestCase
{
    public function test_dynamic_properties_can_be_assigned()
    {
        $manager = $this->getManager();
        $shopping = $this->getShoppingCart($manager);

        $shopping->setProperty('a-property', 10);
        $this->assertTrue($shopping->hasProperty('a-property'));
        $this->assertSame($shopping->getProperty('a-property'), 10);

        $shopping->setProperty('another_property', new stdClass());
        $this->assertTrue($shopping->hasProperty('another_property'));
        $this->assertTrue($shopping->getProperty('another_property') instanceof stdClass);
    }
}

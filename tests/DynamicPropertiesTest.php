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

        $shopping->putAttribute('a-property', 10);
        $this->assertTrue($shopping->hasAttribute('a-property'));
        $this->assertSame($shopping->getAdditionalAttribute('a-property'), 10);

        $shopping->putAttribute('another_property', new stdClass());
        $this->assertTrue($shopping->hasAttribute('another_property'));
        $this->assertTrue($shopping->getAdditionalAttribute('another_property') instanceof stdClass);

        $shopping->removeAttribute('a-property');
        $this->assertFalse($shopping->hasAttribute('a-property'));
        $this->assertNull($shopping->getAdditionalAttribute('a-property'));
    }
}

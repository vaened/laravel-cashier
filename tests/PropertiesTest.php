<?php
/**
 * Created by enea dhack - 12/08/2020 16:19.
 */

namespace Enea\Tests;

use Enea\Tests\Models\Attributable;

class PropertiesTest extends TestCase
{
    public function test_set_properties(): void
    {
        $model = new Attributable([]);

        $model->setProperties(['day' => 500]);
        $this->assertEquals(500, $model->getProperty('day'));
    }

    public function test_check_the_existence_of_a_property(): void
    {
        $model = new Attributable(['level' => 10]);

        $this->assertTrue($model->hasProperty('level'));
        $this->assertFalse($model->hasProperty('non-existent'));
    }

    public function test_modify_a_property(): void
    {
        $model = new Attributable(['id' => 10]);

        $model->putProperty('id', 50);
        $this->assertEquals(50, $model->getProperty('id'));
    }

    public function test_add_a_property(): void
    {
        $model = new Attributable(['id' => 10]);

        $model->putProperty('name', 'enea');
        $this->assertEquals('enea', $model->getProperty('name'));
    }

    public function test_remove_a_property(): void
    {
        $model = new Attributable(['id' => 10]);

        $model->removeProperty('id');
        $this->assertFalse($model->hasProperty('id'));
    }

    public function test_get_all_properties(): void
    {
        $model = new Attributable(['id' => 10]);

        $model->putProperty('name', 'enea');
        $model->putProperty('age', 24);
        $this->assertEquals([
            'id' => 10,
            'name' => 'enea',
            'age' => 24,
        ], $model->getProperties());
    }
}

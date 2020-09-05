<?php
/**
 * Created by enea dhack - 06/08/2020 19:00.
 */

namespace Enea\Tests;

class DataBaseTestCase extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->setUpDatabase();
        $this->setUpSeeder();
    }

    protected function getEnvironmentSetUp($app)
    {
        $config = $app->make('config');
        $config->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]);
    }

    private function setUpDatabase(): void
    {
        include_once __DIR__ . '/database/migrations/0000_00_00_000001_create_laravel_cashier_test_tables.stub';
        $this->app->make('\\CreateLaravelCashierTestTables')->up();
    }

    private function setUpSeeder(): void
    {
        include_once __DIR__ . '/database/seeds/LaravelCashierTestSeeder.stub';
        $this->seed('\\LaravelCashierTestSeeder');
    }
}

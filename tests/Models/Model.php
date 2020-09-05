<?php
/**
 * Created by enea dhack - 08/08/2020 18:57.
 */

namespace Enea\Tests\Models;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model as LaravelModel;

class Model extends LaravelModel
{
    public static function find(int $ID, Closure $closure = null): ?self
    {
        $closure = $closure ?: fn(Builder $builder) => $builder;
        return $closure(self::query())->find($ID);
    }
}

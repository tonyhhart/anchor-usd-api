<?php


namespace App\Traits;


trait HasTypedFactory
{
    /**
     * @param mixed ...$parameters
     * @return static
     */
    public static function factoryMake(...$parameters)
    {
        return self::factory()->make(...$parameters);
    }

    /**
     * @param mixed ...$parameters
     * @return static
     */
    public static function factoryCreate(...$parameters)
    {
        return self::factory()->create(...$parameters);
    }
}

<?php

declare(strict_types = 1);

namespace App\Enum;

/**
 * Class AuthorLocationTypeEnum
 * @package App\Enum
 */
class AuthorLocationTypeEnum extends Enumerable
{
    /**
     * @return AuthorLocationTypeEnum|Enumerable
     * @throws \ReflectionException
     */
    final public static function local(): AuthorLocationTypeEnum
    {
        return self::make('local', 'Local');
    }

    /**
     * @return AuthorLocationTypeEnum|Enumerable
     * @throws \ReflectionException
     */
    final public static function europe(): AuthorLocationTypeEnum
    {
        return self::make('europe', 'Europe');
    }

    /**
     * @return AuthorLocationTypeEnum|Enumerable
     * @throws \ReflectionException
     */
    final public static function asia(): AuthorLocationTypeEnum
    {
        return self::make('asia', 'Asia');
    }

    /**
     * @return AuthorLocationTypeEnum|Enumerable
     * @throws \ReflectionException
     */
    final public static function africa(): AuthorLocationTypeEnum
    {
        return self::make('africa', 'Africa');
    }

    /**
     * @return AuthorLocationTypeEnum|Enumerable
     * @throws \ReflectionException
     */
    final public static function australia(): AuthorLocationTypeEnum
    {
        return self::make('australia', 'Australia');
    }

    /**
     * @return AuthorLocationTypeEnum|Enumerable
     * @throws \ReflectionException
     */
    final public static function northAmerica(): AuthorLocationTypeEnum
    {
        return self::make('n_america', 'North America');
    }

    /**
     * @return AuthorLocationTypeEnum|Enumerable
     * @throws \ReflectionException
     */
    final public static function southAmerica(): AuthorLocationTypeEnum
    {
        return self::make('s_america', 'South America');
    }
}
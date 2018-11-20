<?php

declare(strict_types = 1);

namespace App\Enum;

/**
 * Class ArticleTypeEnum
 * @package App\Enum
 */
class ArticleTypeEnum extends Enumerable
{
    /**
     * @return ArticleTypeEnum|Enumerable
     * @throws \ReflectionException
     */
    final public static function draft(): ArticleTypeEnum
    {
        return self::make('draft', 'Draft Article');
    }

    /**
     * @return ArticleTypeEnum|Enumerable
     * @throws \ReflectionException
     */
    final public static function published(): ArticleTypeEnum
    {
        return self::make('published', 'Article is published', 'Article will be public');
    }
}
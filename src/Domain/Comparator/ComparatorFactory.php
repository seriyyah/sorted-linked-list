<?php

declare(strict_types=1);

namespace App\Domain\Comparator;

use InvalidArgumentException;

final class ComparatorFactory
{
    /**
     * Create a comparator for the given type.
     *
     * @param string $type Type of comparator to create for ('int' or 'string')
     * @return Comparator The appropriate comparator instance
     * @throws InvalidArgumentException If type is not 'int' or 'string'
     */
    public static function createForType(string $type): Comparator
    {
        return match ($type) {
            'int' => new IntegerComparator(),
            'string' => new StringComparator(),
            default => throw new InvalidArgumentException(
                "Unsupported type '{$type}'. Only 'int' and 'string' types are supported."
            ),
        };
    }

    /**
     * Create a comparator for the given value.
     *
     * @param int|string $value The value to create a comparator for
     * @return Comparator The appropriate comparator instance
     */
    public static function createForValue(int|string $value): Comparator
    {
        $type = is_int($value) ? 'int' : 'string';
        return self::createForType($type);
    }
}

<?php

declare(strict_types=1);

namespace App\Domain\Comparator;

/**
 * Comparator for integer values using numeric ordering.
 */
final class IntegerComparator implements Comparator
{
    public function compare(int|string $a, int|string $b): int
    {
        /** @var int $a */
        /** @var int $b */
        return $a <=> $b;
    }

    public function getType(): string
    {
        return 'int';
    }
}

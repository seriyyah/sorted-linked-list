<?php

declare(strict_types=1);

namespace App\Domain\Comparator;

/**
 * Comparator for string values using lexicographic (dictionary) ordering.
 */
final class StringComparator implements Comparator
{
    /**
     * Normalize to -1, 0, 1
     */
    public function compare(int|string $a, int|string $b): int
    {
        /** @var string $a */
        /** @var string $b */
        $result = strcmp($a, $b);

        return $result <=> 0;
    }

    public function getType(): string
    {
        return 'string';
    }
}

<?php

declare(strict_types=1);

namespace App\Domain\Comparator;

/**
 * Implementations must return:
 *
 * - -1 if $a < $b
 * -  0 if $a == $b
 * -  1 if $a > $b
 */
interface Comparator
{
    /**
     * Compare two values according to this comparator's strategy.
     *
     * @param int|string $a The first value
     * @param int|string $b The second value
     * @return int -1|0|1 Result of comparison
     */
    public function compare(int|string $a, int|string $b): int;

    /**
     * Get the type this comparator operates on.
     *
     * @return 'int'|'string'
     */
    public function getType(): string;
}

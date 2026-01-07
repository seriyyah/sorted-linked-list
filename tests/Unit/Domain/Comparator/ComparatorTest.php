<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Comparator;

use App\Domain\Comparator\IntegerComparator;
use App\Domain\Comparator\StringComparator;
use PHPUnit\Framework\TestCase;

class ComparatorTest extends TestCase
{
    public function test_integer_comparator_returns_negative_for_less_than(): void
    {
        $comparator = new IntegerComparator();

        self::assertSame(-1, $comparator->compare(1, 2));
    }

    public function test_integer_comparator_returns_zero_for_equal(): void
    {
        $comparator = new IntegerComparator();

        self::assertSame(0, $comparator->compare(42, 42));
    }

    public function test_integer_comparator_returns_positive_for_greater_than(): void
    {
        $comparator = new IntegerComparator();

        self::assertSame(1, $comparator->compare(5, 3));
    }

    public function test_integer_comparator_type(): void
    {
        $comparator = new IntegerComparator();

        self::assertSame('int', $comparator->getType());
    }

    public function test_string_comparator_returns_negative_for_less_than(): void
    {
        $comparator = new StringComparator();

        self::assertSame(-1, $comparator->compare('apple', 'banana'));
    }

    public function test_string_comparator_returns_zero_for_equal(): void
    {
        $comparator = new StringComparator();

        self::assertSame(0, $comparator->compare('hello', 'hello'));
    }

    public function test_string_comparator_returns_positive_for_greater_than(): void
    {
        $comparator = new StringComparator();

        self::assertSame(1, $comparator->compare('zebra', 'apple'));
    }

    public function test_string_comparator_type(): void
    {
        $comparator = new StringComparator();

        self::assertSame('string', $comparator->getType());
    }

    public function test_string_comparator_case_sensitive(): void
    {
        $comparator = new StringComparator();

        // strcmp is case-sensitive
        self::assertNotSame(0, $comparator->compare('Hello', 'hello'));
    }
}

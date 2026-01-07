<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Comparator;

use App\Domain\Comparator\ComparatorFactory;
use App\Domain\Comparator\IntegerComparator;
use App\Domain\Comparator\StringComparator;
use PHPUnit\Framework\TestCase;

class ComparatorFactoryTest extends TestCase
{
    public function test_create_integer_comparator_by_type(): void
    {
        $comparator = ComparatorFactory::createForType('int');

        self::assertInstanceOf(IntegerComparator::class, $comparator);
    }

    public function test_create_string_comparator_by_type(): void
    {
        $comparator = ComparatorFactory::createForType('string');

        self::assertInstanceOf(StringComparator::class, $comparator);
    }

    public function test_create_integer_comparator_by_value(): void
    {
        $comparator = ComparatorFactory::createForValue(42);

        self::assertInstanceOf(IntegerComparator::class, $comparator);
    }

    public function test_create_string_comparator_by_value(): void
    {
        $comparator = ComparatorFactory::createForValue('hello');

        self::assertInstanceOf(StringComparator::class, $comparator);
    }

    public function test_throws_for_unsupported_type(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Unsupported type");

        /** @var 'int'|'string' $invalidType */
        $invalidType = 'bool';

        ComparatorFactory::createForType($invalidType);
    }
}

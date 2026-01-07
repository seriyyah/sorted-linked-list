<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain;

use App\Domain\SortedLinkedList;
use App\Exception\TypeMismatchException;
use PHPUnit\Framework\TestCase;

class SortedLinkedListTest extends TestCase
{
    public function test_empty_list_creation(): void
    {
        $list = SortedLinkedList::empty();

        self::assertTrue($list->isEmpty());
        self::assertSame(0, $list->size());
    }

    public function test_create_from_empty_array(): void
    {
        $list = SortedLinkedList::fromArray([]);

        self::assertTrue($list->isEmpty());
    }

    public function test_create_from_integer_array(): void
    {
        $list = SortedLinkedList::fromArray([3, 1, 2]);

        self::assertFalse($list->isEmpty());
        self::assertSame(3, $list->size());
        self::assertSame([1, 2, 3], $list->getAll());
    }

    public function test_create_from_string_array(): void
    {
        $list = SortedLinkedList::fromArray(['cherry', 'apple', 'banana']);

        self::assertSame(3, $list->size());
        self::assertSame(['apple', 'banana', 'cherry'], $list->getAll());
    }

    public function test_insert_into_empty_list_determines_type(): void
    {
        $list = SortedLinkedList::empty()->insert(42);

        self::assertSame('int', $list->getValueType());
    }

    public function test_insert_string_into_empty_list(): void
    {
        $list = SortedLinkedList::empty()->insert('hello');

        self::assertSame('string', $list->getValueType());
    }

    public function test_cannot_insert_different_type(): void
    {
        $list = SortedLinkedList::empty()->insert(42);

        $this->expectException(TypeMismatchException::class);
        $this->expectExceptionMessage('Cannot insert string into SortedLinkedList');
        $list->insert('hello');
    }

    public function test_cannot_insert_int_into_string_list(): void
    {
        $list = SortedLinkedList::empty()->insert('hello');

        $this->expectException(TypeMismatchException::class);
        $this->expectExceptionMessage('Cannot insert int into SortedLinkedList');
        $list->insert(42);
    }

    public function test_insert_maintains_sorted_order_integers(): void
    {
        $list = SortedLinkedList::empty()
            ->insert(5)
            ->insert(3)
            ->insert(7)
            ->insert(1)
            ->insert(9);

        self::assertSame([1, 3, 5, 7, 9], $list->getAll());
    }

    public function test_insert_maintains_sorted_order_strings(): void
    {
        $list = SortedLinkedList::empty()
            ->insert('dog')
            ->insert('cat')
            ->insert('elephant')
            ->insert('apple');

        self::assertSame(['apple', 'cat', 'dog', 'elephant'], $list->getAll());
    }

    public function test_insert_duplicate_values(): void
    {
        $list = SortedLinkedList::empty()
            ->insert(1)
            ->insert(2)
            ->insert(1)
            ->insert(3)
            ->insert(2);

        self::assertSame([1, 1, 2, 2, 3], $list->getAll());
    }

    public function test_insert_at_beginning(): void
    {
        $list = SortedLinkedList::fromArray([2, 3, 4])->insert(1);

        self::assertSame([1, 2, 3, 4], $list->getAll());
    }

    public function test_insert_at_end(): void
    {
        $list = SortedLinkedList::fromArray([1, 2, 3])->insert(4);

        self::assertSame([1, 2, 3, 4], $list->getAll());
    }

    public function test_insert_in_middle(): void
    {
        $list = SortedLinkedList::fromArray([1, 3, 5])->insert(4);

        self::assertSame([1, 3, 4, 5], $list->getAll());
    }

    public function test_remove_from_list(): void
    {
        $list = SortedLinkedList::fromArray([1, 2, 3, 4, 5])->remove(3);

        self::assertSame([1, 2, 4, 5], $list->getAll());
        self::assertSame(4, $list->size());
    }

    public function test_remove_first_element(): void
    {
        $list = SortedLinkedList::fromArray([1, 2, 3])->remove(1);

        self::assertSame([2, 3], $list->getAll());
    }

    public function test_remove_last_element(): void
    {
        $list = SortedLinkedList::fromArray([1, 2, 3])->remove(3);

        self::assertSame([1, 2], $list->getAll());
    }

    public function test_remove_nonexistent_value_returns_same_list(): void
    {
        $list = SortedLinkedList::fromArray([1, 2, 3]);
        $removed = $list->remove(99);

        self::assertSame([1, 2, 3], $removed->getAll());
        self::assertSame(3, $removed->size());
    }

    public function test_remove_from_empty_list(): void
    {
        $list = SortedLinkedList::empty()->remove(42);

        self::assertTrue($list->isEmpty());
    }

    public function test_remove_first_occurrence_only(): void
    {
        $list = SortedLinkedList::fromArray([1, 2, 2, 2, 3])->remove(2);

        self::assertSame([1, 2, 2, 3], $list->getAll());
    }

    public function test_remove_wrong_type_returns_same_list(): void
    {
        $list = SortedLinkedList::fromArray([1, 2, 3]);
        $removed = $list->remove('1'); // string '1', not int

        self::assertSame([1, 2, 3], $removed->getAll());
    }

    public function test_contains_returns_true_for_existing_value(): void
    {
        $list = SortedLinkedList::fromArray([1, 2, 3, 4, 5]);

        self::assertTrue($list->contains(3));
    }

    public function test_contains_returns_false_for_missing_value(): void
    {
        $list = SortedLinkedList::fromArray([1, 2, 3, 4, 5]);

        self::assertFalse($list->contains(99));
    }

    public function test_contains_returns_false_for_empty_list(): void
    {
        $list = SortedLinkedList::empty();

        self::assertFalse($list->contains(1));
    }

    public function test_contains_wrong_type_returns_false(): void
    {
        $list = SortedLinkedList::fromArray([1, 2, 3]);

        self::assertFalse($list->contains('1'));
    }

    public function test_contains_strings(): void
    {
        $list = SortedLinkedList::fromArray(['apple', 'banana', 'cherry']);

        self::assertTrue($list->contains('banana'));
        self::assertFalse($list->contains('grape'));
    }

    public function test_first_returns_smallest_value(): void
    {
        $list = SortedLinkedList::fromArray([5, 2, 8, 1, 9]);

        self::assertSame(1, $list->first());
    }

    public function test_first_returns_null_for_empty_list(): void
    {
        $list = SortedLinkedList::empty();

        self::assertNull($list->first());
    }

    public function test_head_returns_first_node(): void
    {
        $list = SortedLinkedList::fromArray([1, 2, 3]);
        $head = $list->head();

        self::assertNotNull($head);
        self::assertSame(1, $head->getValue());
    }

    public function test_head_returns_null_for_empty_list(): void
    {
        $list = SortedLinkedList::empty();
        self::assertNull($list->head());
    }

    public function test_size_increases_with_insertions(): void
    {
        $list = SortedLinkedList::empty();

        self::assertSame(0, $list->size());

        $list = $list->insert(1);

        self::assertSame(1, $list->size());

        $list = $list->insert(2);

        self::assertSame(2, $list->size());
    }

    public function test_size_decreases_with_removals(): void
    {
        $list = SortedLinkedList::fromArray([1, 2, 3, 4, 5]);

        self::assertSame(5, $list->size());

        $list = $list->remove(3);

        self::assertSame(4, $list->size());

        $list = $list->remove(1);

        self::assertSame(3, $list->size());
    }

    public function test_is_empty_checks_correctly(): void
    {
        $list = SortedLinkedList::empty();

        self::assertTrue($list->isEmpty());

        $list = $list->insert(1);

        self::assertFalse($list->isEmpty());

        $list = $list->remove(1);

        self::assertTrue($list->isEmpty());
    }

    public function test_map_transforms_values(): void
    {
        $list = SortedLinkedList::fromArray([1, 2, 3, 4, 5]);
        $doubled = $list->map(static fn ($x) => (int)$x * 2);

        self::assertSame([2, 4, 6, 8, 10], $doubled);
    }

    public function test_map_on_empty_list(): void
    {
        $list = SortedLinkedList::empty();
        $result = $list->map(static fn ($x) => (int)$x * 2);

        self::assertSame([], $result);
    }

    public function test_filter_returns_sorted_list(): void
    {
        $list = SortedLinkedList::fromArray([1, 2, 3, 4, 5]);
        $filtered = $list->filter(static fn ($x) => $x > 2);

        self::assertInstanceOf(SortedLinkedList::class, $filtered);
        self::assertSame([3, 4, 5], $filtered->getAll());
    }

    public function test_filter_maintains_sorted_order(): void
    {
        $list = SortedLinkedList::fromArray([1, 2, 3, 4, 5]);
        $filtered = $list->filter(static fn ($x) => (int)$x % 2 === 0);

        self::assertSame([2, 4], $filtered->getAll());
    }

    public function test_filter_returns_empty_list_if_no_matches(): void
    {
        $list = SortedLinkedList::fromArray([1, 2, 3]);
        $filtered = $list->filter(static fn ($x) => $x > 10);

        self::assertTrue($filtered->isEmpty());
    }

    public function test_insert_returns_new_instance(): void
    {
        $list1 = SortedLinkedList::fromArray([1, 2, 3]);
        $list2 = $list1->insert(4);

        // diff instances
        self::assertNotSame($list1, $list2);

        // original unchanged
        self::assertSame([1, 2, 3], $list1->getAll());
        self::assertSame([1, 2, 3, 4], $list2->getAll());
    }

    public function test_remove_returns_new_instance(): void
    {
        $list1 = SortedLinkedList::fromArray([1, 2, 3]);
        $list2 = $list1->remove(2);

        // diff instances
        self::assertNotSame($list1, $list2);

        // original unchanged
        self::assertSame([1, 2, 3], $list1->getAll());
        self::assertSame([1, 3], $list2->getAll());
    }

    public function test_filter_returns_new_instance(): void
    {
        $list1 = SortedLinkedList::fromArray([1, 2, 3, 4, 5]);
        $list2 = $list1->filter(static fn ($x) => $x > 2);

        // diff instances
        self::assertNotSame($list1, $list2);

        // Original unchanged
        self::assertSame([1, 2, 3, 4, 5], $list1->getAll());
    }

    public function test_large_dataset(): void
    {
        $values = range(1, 1000);
        shuffle($values);

        $list = SortedLinkedList::fromArray($values);

        self::assertSame(1000, $list->size());
        self::assertSame(1, $list->first());
        self::assertSame(range(1, 1000), $list->getAll());
    }

    public function test_negative_integers(): void
    {
        $list = SortedLinkedList::fromArray([5, -3, 0, 10, -1]);

        self::assertSame([-3, -1, 0, 5, 10], $list->getAll());
    }

    public function test_strings_with_special_characters(): void
    {
        $list = SortedLinkedList::fromArray(['@hello', '#world', '!test']);

        self::assertSame(['!test', '#world', '@hello'], $list->getAll());
    }

    public function test_chainable_operations(): void
    {
        $list = SortedLinkedList::empty()
            ->insert(5)
            ->insert(3)
            ->insert(7)
            ->remove(3)
            ->insert(1);

        self::assertSame([1, 5, 7], $list->getAll());
    }
}

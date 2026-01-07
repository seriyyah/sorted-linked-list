<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Comparator\Comparator;
use App\Domain\Comparator\ComparatorFactory;
use App\Exception\TypeMismatchException;

/**
 * Immutable sorted linked list that maintains values in sorted order.
 * Supports only int or string, never mixed.
 */
final readonly class SortedLinkedList
{
    /**
     * @param Node|null $head The first node in the list
     * @param int $size Cached size for O(1) size queries
     * @param Comparator $comparator Strategy for comparing values
     */
    private function __construct(
        private ?Node $head = null,
        private int $size = 0,
        private Comparator $comparator = new \App\Domain\Comparator\StringComparator(),
    ) {
    }

    /**
     * Create an empty sorted linked list.
     *
     * @return self
     */
    public static function empty(): self
    {
        return new self();
    }

    /**
     * Create a sorted linked list from an array of values.
     * All values must be of the same type (int or string).
     *
     * @param array<int|string> $values Array of values to insert
     * @return self New sorted list containing all values
     * @throws TypeMismatchException If values contain mixed types
     */
    public static function fromArray(array $values): self
    {
        $list = self::empty();

        foreach ($values as $value) {
            $list = $list->insert($value);
        }

        return $list;
    }

    /**
     * Insert a value maintaining sorted order.
     *
     * @param int|string $value The value to insert
     * @return self New list with value inserted in sorted position
     * @throws TypeMismatchException If value type differs from existing list type
     */
    public function insert(int|string $value): self
    {
        // determine type from first value
        if ($this->isEmpty()) {
            $comparator = ComparatorFactory::createForValue($value);

            return new self(
                new Node($value),
                1,
                $comparator
            );
        }

        // type matches existing list
        if (!$this->isValidType($value)) {
            throw TypeMismatchException::forIncompatibleType(
                $this->comparator->getType(),
                is_int($value) ? 'int' : 'string'
            );
        }

        // Insert in sorted position
        return new self(
            $this->insertRecursive($this->head, $value),
            $this->size + 1,
            $this->comparator
        );
    }

    /**
     * Remove a value from the list.
     * If value appears multiple times, only removes first occurrence.
     *
     * @param int|string $value The value to remove
     * @return self New list with value removed (or same list if not found)
     */
    public function remove(int|string $value): self
    {
        if ($this->isEmpty()) {
            return $this;
        }

        if (!$this->isValidType($value)) {
            return $this;
        }

        $found = false;
        $newHead = $this->removeRecursive($this->head, $value, $found);

        if (!$found) {
            return $this;
        }

        return new self(
            $newHead,
            $this->size - 1,
            $this->comparator
        );
    }

    /**
     * @param int|string $value The value to search for
     * @return bool True if list contains value, false otherwise
     */
    public function contains(int|string $value): bool
    {
        if ($this->isEmpty() || !$this->isValidType($value)) {
            return false;
        }

        return $this->containsRecursive($this->head, $value);
    }

    /**
     * Get all values as an array in sorted order.
     *
     * @return array<int|string>
     */
    public function getAll(): array
    {
        if ($this->isEmpty()) {
            return [];
        }

        $result = [];
        $current = $this->head;

        while ($current !== null) {
            $result[] = $current->getValue();
            $current = $current->getNext();
        }

        return $result;
    }

    /**
     * Get the first (smallest) value in the list.
     *
     * @return int|string|null Null if list is empty
     */
    public function first(): int|string|null
    {
        return $this->head?->getValue();
    }

    /**
     * Get the head node.
     *
     * @return Node|null
     */
    public function head(): ?Node
    {
        return $this->head;
    }

    /**
     * Get the number of elements in the list.
     *
     * @return int
     */
    public function size(): int
    {
        return $this->size;
    }

    /**
     * Check if list is empty.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->size === 0;
    }

    /**
     * Transform each value using a callback.
     *
     * @template R
     * @param callable(int|string): R $callback
     * @return array<R>
     */
    public function map(callable $callback): array
    {
        return array_map($callback, $this->getAll());
    }

    /**
     * Filter values based on callback predicate.
     * Returns new sorted list with matching values.
     *
     * @param callable(int|string): bool $callback
     * @return self
     */
    public function filter(callable $callback): self
    {
        $filtered = array_filter($this->getAll(), $callback);

        return self::fromArray($filtered);
    }

    /**
     * @return 'int'|'string'|null Null if list is empty
     */
    public function getValueType(): ?string
    {
        return $this->isEmpty() ? null : $this->comparator->getType();
    }

    /**
     * @param Node|null $node Current node in traversal
     * @param int|string $value Value to insert
     * @return Node New node structure with value inserted
     */
    private function insertRecursive(?Node $node, int|string $value): Node
    {
        if ($node === null) {
            return new Node($value);
        }

        $comparison = $this->comparator->compare($value, $node->getValue());

        if ($comparison <= 0) {
            return new Node($value, $node);
        }

        return $node->withNext($this->insertRecursive($node->getNext(), $value));
    }

    /**
     * @param Node|null $node Current node in traversal
     * @param int|string $value Value to remove
     * @param bool $found Reference flag set to true when value is found
     * @return Node|null New node structure with value removed
     */
    private function removeRecursive(?Node $node, int|string $value, bool &$found): ?Node
    {
        if ($node === null) {
            return null;
        }

        $comparison = $this->comparator->compare($value, $node->getValue());

        if ($comparison === 0) {
            $found = true;

            return $node->getNext();
        }

        if ($comparison < 0) {
            return $node;
        }

        return $node->withNext($this->removeRecursive($node->getNext(), $value, $found));
    }

    /**
     * @param Node|null $node Current node in traversal
     * @param int|string $value Value to search for
     * @return bool True if value found, false otherwise
     */
    private function containsRecursive(?Node $node, int|string $value): bool
    {
        if ($node === null) {
            return false;
        }

        $comparison = $this->comparator->compare($value, $node->getValue());

        if ($comparison === 0) {
            return true;
        }

        if ($comparison < 0) {
            return false;
        }

        return $this->containsRecursive($node->getNext(), $value);
    }

    /**
     * @param int|string $value
     * @return bool
     */
    private function isValidType(int|string $value): bool
    {
        $valueType = is_int($value) ? 'int' : 'string';

        return $valueType === $this->comparator->getType();
    }
}

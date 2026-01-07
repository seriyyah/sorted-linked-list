<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * Immutable value object representing a node in the sorted linked list.
 */
final readonly class Node
{
    /**
     * @param int|string $value The value stored in this node
     * @param self|null $next Reference to next node in the chain
     */
    public function __construct(
        private int|string $value,
        private ?self $next = null
    ) {
    }

    /**
     * Get the value stored in this node.
     *
     * @return int|string
     */
    public function getValue(): int|string
    {
        return $this->value;
    }

    /**
     * Get the next node in the chain.
     *
     * @return self|null
     */
    public function getNext(): ?self
    {
        return $this->next;
    }

    /**
     * Check if this node has a next node.
     *
     * @return bool
     */
    public function hasNext(): bool
    {
        return $this->next !== null;
    }

    /**
     * Create a new node with this node's value but a new next reference.
     *
     * @param self|null $newNext The new next node
     * @return self New node instance with updated reference
     */
    public function withNext(?self $newNext): self
    {
        return new self($this->value, $newNext);
    }
}

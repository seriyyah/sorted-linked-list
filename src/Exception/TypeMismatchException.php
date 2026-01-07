<?php

declare(strict_types=1);

namespace App\Exception;

/**
 * Thrown when attempting to insert a value of a different type into an existing list.
 * This enforces the contract that a SortedLinkedList must contain homogeneous types.
 */
final class TypeMismatchException extends SortedLinkedListException
{
    /**
     * @param string $expectedType The type already in the list
     * @param string $givenType The type attempting to be inserted
     */
    public static function forIncompatibleType(string $expectedType, string $givenType): self
    {
        return new self(
            sprintf(
                'Cannot insert %s into SortedLinkedList<T> of type %s. Lists must contain homogeneous types.',
                $givenType,
                $expectedType
            )
        );
    }
}

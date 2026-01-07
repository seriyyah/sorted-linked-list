<?php

declare(strict_types=1);

namespace App\Exception;

/**
 * Base exception for all SortedLinkedList domain operations.
 * This serves as the contract for all domain-specific exceptions,
 * allowing unified error handling across the library.
 */
class SortedLinkedListException extends \DomainException
{
}

<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain;

use App\Domain\Node;
use PHPUnit\Framework\TestCase;

class NodeTest extends TestCase
{
    public function test_node_stores_value(): void
    {
        $node = new Node(42);

        self::assertSame(42, $node->getValue());
    }

    public function test_node_stores_string_value(): void
    {
        $node = new Node('hello');

        self::assertSame('hello', $node->getValue());
    }

    public function test_node_has_no_next_by_default(): void
    {
        $node = new Node(42);

        self::assertNull($node->getNext());
        self::assertFalse($node->hasNext());
    }

    public function test_node_can_have_next_reference(): void
    {
        $next = new Node(2);
        $node = new Node(1, $next);

        self::assertTrue($node->hasNext());
        self::assertSame($next, $node->getNext());
    }

    public function test_node_is_immutable_with_next(): void
    {
        $next1 = new Node(2);
        $node1 = new Node(1, $next1);

        $next2 = new Node(3);
        $node2 = $node1->withNext($next2);

        // original node wasnt changed
        self::assertSame($next1, $node1->getNext());

        // new node has a diff next
        self::assertSame($next2, $node2->getNext());

        // values are same
        self::assertSame(1, $node1->getValue());
        self::assertSame(1, $node2->getValue());
    }

    public function test_node_with_next_null(): void
    {
        $next = new Node(2);
        $node1 = new Node(1, $next);
        $node2 = $node1->withNext(null);

        self::assertTrue($node1->hasNext());
        self::assertFalse($node2->hasNext());
        self::assertNull($node2->getNext());
    }
}

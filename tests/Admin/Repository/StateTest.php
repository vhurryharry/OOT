<?php

declare(strict_types=1);

namespace App\Admin\Repository;

use PHPUnit\Framework\TestCase;

class StateTest extends TestCase
{
    public function testIsCreatingStateQuery(): void
    {
        $state = new State();
        $state->addFilter('foo', 'bar', STATE::FILTER_EQUALS);
        $this->assertEquals('where foo = :foo limit 25 offset 0', $state->toQuery());
        $this->assertEquals(['foo' => '%bar%'], $state->toQueryParams());
    }

    public function testIsCreatingStateLikeQuery(): void
    {
        $state = new State();
        $state->addFilter('foo', 'bar', STATE::FILTER_LIKE);
        $this->assertEquals('where foo ~~* :foo limit 25 offset 0', $state->toQuery());
        $this->assertEquals(['foo' => '%bar%'], $state->toQueryParams());
    }

    public function testIsCreatingStateLikeQueryWithoutZeroOffset(): void
    {
        $state = new State(10, -10);
        $this->assertEquals('limit 10 offset 0', $state->toQuery());
    }
}

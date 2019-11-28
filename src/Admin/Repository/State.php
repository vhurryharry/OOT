<?php

declare(strict_types=1);

namespace App\Admin\Repository;

class State
{
    const FILTER_LIKE = '~~*';
    const FILTER_EQUALS = '=';

    /**
     * @var int
     */
    protected $limit;

    /**
     * @var int
     */
    protected $offset;

    /**
     * @var array
     */
    protected $filters = [];

    public function __construct(int $limit = 25, int $offset = 0)
    {
        $this->limit = $limit;
        $this->offset = max($offset, 0);
    }

    public static function fromDatagrid(array $datagridState): State
    {
        if (!isset($datagridState['state']['page'])) {
            return new State();
        }

        $current = $datagridState['state']['page']['current'] - 1;
        $size = $datagridState['state']['page']['size'];
        $filters = $datagridState['state']['filters'] ?? [];

        $state = new State($size, $size * $current);

        foreach ($filters as $filter) {
            $state->addFilter($filter['property'], $filter['value']);
        }

        return $state;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function addFilter(string $key, $value, string $operator = self::FILTER_LIKE): void
    {
        $this->filters[$key] = [
            'value' => $value,
            'operator' => $operator,
        ];
    }

    public function toQuery(string $order = null): string
    {
        $query = '';
        $conditionals = [];

        foreach ($this->filters as $column => $filter) {
            $conditionals[] = sprintf('%s %s %s', $column, $filter['operator'], ':' . $column);
        }

        if (!empty($conditionals)) {
            $query .= sprintf('where %s ', implode(' AND ', $conditionals));
        }

        if ($order) {
            $query .= ' order by ' . $order;
        }

        $query .= sprintf(' limit %d offset %d', $this->limit, $this->offset);

        return $query;
    }

    public function toQueryParams(): array
    {
        $params = [];

        foreach ($this->filters as $key => $filter) {
            $params[$key] = sprintf('%%%s%%', $filter['value']);
        }

        return $params;
    }
}

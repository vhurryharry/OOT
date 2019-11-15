<?php

declare(strict_types=1);

namespace App\Admin\Repository;

class EntityRepository
{
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
        $this->offset = $offset;
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
}

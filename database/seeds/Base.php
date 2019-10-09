<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;
use Ramsey\Uuid\Uuid;

class Base extends AbstractSeed
{
    public function run(): void
    {
        $entities = [
            [
                'id' => Uuid::uuid4(),
                'slug' => 'homepage',
                'title' => 'Homepage',
                'type' => 'page',
                'content' => 'foobar',
            ],
        ];

        $this
            ->table('entity')
            ->insert($entities)
            ->save();
    }
}

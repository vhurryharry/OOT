<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;
use Ramsey\Uuid\Uuid;

class Base extends AbstractSeed
{
    public function run(): void
    {
        $this->createPages();
        $this->createMenu();
    }

    protected function createPages(): void
    {
        $entities = [
            [
                'id' => Uuid::uuid4(),
                'slug' => 'homepage',
                'title' => 'Homepage',
                'type' => 'page',
                'content' => 'foobar',
            ],
            [
                'id' => Uuid::uuid4(),
                'slug' => 'faq',
                'title' => 'FAQ',
                'type' => 'page',
                'content' => 'foobar',
            ],
            [
                'id' => Uuid::uuid4(),
                'slug' => 'about',
                'title' => 'About',
                'type' => 'page',
                'content' => 'foobar',
            ],
            [
                'id' => Uuid::uuid4(),
                'slug' => 'get-updates',
                'title' => 'Get Updates',
                'type' => 'page',
                'content' => 'foobar',
            ],
            [
                'id' => Uuid::uuid4(),
                'slug' => 'sommelier-registry',
                'title' => 'Sommelier Registry',
                'type' => 'page',
                'content' => 'foobar',
            ],
            [
                'id' => Uuid::uuid4(),
                'slug' => 'contact',
                'title' => 'Contact',
                'type' => 'page',
                'content' => 'foobar',
            ],
        ];

        $this
            ->table('entity')
            ->insert($entities)
            ->save();
    }

    protected function createMenu(): void
    {
        $menus = [
            [
                'title' => 'Homepage',
                'link' => 'homepage',
            ],
            [
                'title' => 'Courses',
                'link' => 'courses',
            ],
            [
                'title' => 'FAQ',
                'link' => 'faq',
            ],
            [
                'title' => 'About',
                'link' => 'about',
            ],
            [
                'title' => 'Get Updates',
                'link' => 'get-updates',
            ],
            [
                'title' => 'Sommelier Registry',
                'link' => 'sommelier-registry',
            ],
            [
                'title' => 'Contact',
                'link' => 'contact',
            ],
        ];

        $this
            ->table('menu')
            ->insert($menus)
            ->save();
    }
}

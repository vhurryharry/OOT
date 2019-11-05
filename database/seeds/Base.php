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
        $this->createTestimonials();
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

    protected function createTestimonials(): void
    {
        $testimonials = [
            [
                'title' => 'The quality and range of instruction were at a level that far surpassed my expectations.',
                'author_text' => 'Willem, The Netherlands',
            ],
            [
                'title' => 'Fabulous course with incredible amounts of information that will be used as a reference guide for years to come.',
                'author_text' => 'An olive oil producer from Turkey',
            ],
            [
                'title' => 'I loved every minute of it. I am very proud to be part of this group.',
                'author_text' => 'Wilma van Grinsven – Padberg, Oil & Vinegar chain owner',
            ],
            [
                'title' => 'I highly recommend it. Every day was absolutely worth it.',
                'author_text' => 'Elise LaGamba, grocery executive',
            ],
            [
                'title' => 'Whether you’re a chef, an importer like me, no matter what area you’re in, you should do this course.',
                'author_text' => 'Thomas Sheridan, importer',
            ],
            [
                'title' => 'I enjoyed from the first thing in the morning to the last minute when I go home. The course was extremely well done.',
                'author_text' => 'Karim Fitouri, Olivko',
            ],
            [
                'title' => 'The course was eye-opening and led by inspiring experts.',
                'author_text' => 'Ann Bartyzel',
            ],
            [
                'title' => 'An awesome course. Very excited to stay in touch with all of my new colleagues and educate more and more.',
                'author_text' => 'A corporate chef from California',
            ],
            [
                'title' => 'Best class ever for learning about what I love.',
                'author_text' => 'Olive oil producer from California',
            ],
            [
                'title' => 'This was awesome! Thank you so much for offering this program in California.',
                'author_text' => 'Theo Epstein – chef, speaker and olive oil producer',
            ],
            [
                'title' => 'Thanks for a wonderful week! I’ve found my tribe!',
                'author_text' => 'Certified Sommelier, Germany',
            ],
        ];

        $this
            ->table('course_testimonial')
            ->insert($testimonials)
            ->save();
    }

}

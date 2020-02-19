<?php


use Phinx\Seed\AbstractSeed;

class CourseCategoriesSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $this->createCourseCategories();
    }

    protected function createCourseCategories(): void
    {
        $categories = [
            [
                'category' => 'Quality Assessment'
            ],
            [
                'category' => 'Farming and Production'
            ],
            [
                'category' => 'Culinary'
            ],
            [
                'category' => 'Marketing'
            ],
            [
                'category' => 'Health'
            ]
        ];

        $this
            ->table('course_category')
            ->insert($categories)
            ->save();
    }
}

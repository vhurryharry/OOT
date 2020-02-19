<?php

use Phinx\Migration\AbstractMigration;

class AddedCourseCategory extends AbstractMigration
{
    public function up(): void {        
        $courseCategory = $this->table('course_category');
        $courseCategory->addColumn('category', 'string')
              ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('updated_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('deleted_at', 'timestamp', ['null' => true])
              ->create();

        $course = $this->table('course');
        $course->addColumn('categories', 'integer[]', ['null' => true])
                ->save();
    }

    public function down(): void {        
        $courseCategory = $this->table('course_category')->drop();

        $course = $this->table('course');
        $course->removeColumn('categories')
            ->save();
    }
}

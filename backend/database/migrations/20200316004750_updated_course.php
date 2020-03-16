<?php

use Phinx\Migration\AbstractMigration;

class UpdatedCourse extends AbstractMigration
{
    public function up(): void {        
        $course = $this->table('course');
        $course->addColumn('last_date', 'date')
              ->save();
    }

    public function down(): void {
        $course = $this->table('course');
        $course->removeColumn('last_date')
                ->save();
    }
}

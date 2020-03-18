<?php

use Phinx\Migration\AbstractMigration;

class UpdatedCourse extends AbstractMigration
{
    public function up(): void
    {
        $course = $this->table('course');
        $course->addColumn('last_date', 'date')
            ->addColumn('note', 'string', ['null' => true])
            ->addColumn('status', 'string')
            ->save();
    }

    public function down(): void
    {
        $course = $this->table('course');
        $course->removeColumn('last_date')
            ->removeColumn('note')
            ->removeColumn('status')
            ->save();
    }
}

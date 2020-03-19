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
            ->addColumn('start_time', 'time')
            ->addColumn('end_time', 'time')
            ->save();
    }

    public function down(): void
    {
        $course = $this->table('course');
        $course->removeColumn('last_date')
            ->removeColumn('note')
            ->removeColumn('status')
            ->removeColumn('start_time')
            ->removeColumn('end_time')
            ->save();
    }
}

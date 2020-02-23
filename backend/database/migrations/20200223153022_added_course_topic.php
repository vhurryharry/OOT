<?php

use Phinx\Migration\AbstractMigration;

class AddedCourseTopic extends AbstractMigration
{
    public function up(): void {        
        $courseTopic = $this->table('course_topic');
        $courseTopic->addColumn('topic', 'string')
              ->addColumn('description', 'string')
              ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('updated_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('deleted_at', 'timestamp', ['null' => true])
              ->create();

        $course = $this->table('course');
        $course->addColumn('topics', 'integer[]', ['null' => true])
                ->save();

        $customer = $this->table('customer');
        $customer->addColumn('avatar', 'string', ['null' => true])
                ->save();
    }

    public function down(): void {
        $courseTopic = $this->table('course_topic')->drop();

        $course = $this->table('course');
        $course->removeColumn('topics')
            ->save();

        $customer = $this->table('customer');
        $customer->removeColumn('avatar')
            ->save();
    }
}

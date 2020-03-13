<?php

use Phinx\Migration\AbstractMigration;

class UpdatedTestimonial extends AbstractMigration
{
    public function up(): void {        
        $courseTestimonial = $this->table('course_testimonial');
        $courseTestimonial->removeColumn('content')
              ->removeColumn('title')
              ->addColumn('testimonial', 'string', ['null' => true])
              ->addColumn('author_occupation', 'string', ['null' => true])
              ->addColumn('author_avatar', 'string', ['null' => true])
              ->save();
    }

    public function down(): void {
        $courseTestimonial = $this->table('course_testimonial');
        $courseTestimonial->addColumn('content', 'string', ['null' => true])
                ->addColumn('title', 'string', ['null' => true])
                ->removeColumn('testimonial')
                ->removeColumn('author_occupation')
                ->removeColumn('author_avatar')
                ->save();
    }
}

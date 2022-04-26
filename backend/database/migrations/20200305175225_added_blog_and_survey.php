<?php

use Phinx\Migration\AbstractMigration;

class AddedBlogAndSurvey extends AbstractMigration
{
    public function up(): void
    {
        $blog = $this->table('blog');
        $blog->addColumn('author', 'string')
            ->addColumn('cover_image', 'string')
            ->addColumn('title', 'string')
            ->addColumn('subtitle', 'string')
            ->addColumn('category', 'integer')
            ->addColumn('slug', 'string')
            ->addColumn('content', 'string')
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('deleted_at', 'timestamp', ['null' => true])
            ->create();

        $blogCategory = $this->table('blog_category')
            ->addColumn('category', 'string')
            ->create();

        $surveyQuestion = $this->table('survey_question');
        $surveyQuestion->addColumn('question', 'string')
            ->addColumn('type', 'string')
            ->addColumn('course_id', 'string')
            ->addColumn('extra', 'string', ['null' => true])
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('deleted_at', 'timestamp', ['null' => true])
            ->create();

        $surveyResult = $this->table('survey_result');
        $surveyResult->addColumn('customer_id', 'string')
            ->addColumn('course_id', 'string')
            ->addColumn('question_id', 'integer')
            ->addColumn('answer', 'string')
            ->addColumn('extra', 'string', ['null' => true])
            ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('deleted_at', 'timestamp', ['null' => true])
            ->create();
    }

    public function down(): void
    {
        $blog = $this->table('blog')->drop();
        $blogCategory = $this->table('blog_category')->drop();
        $surveyQuestion = $this->table('survey_question')->drop();
        $surveyResult = $this->table('survey_result')->drop();
    }
}

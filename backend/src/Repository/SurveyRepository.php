<?php

declare(strict_types=1);

namespace App\Repository;

use App\Database;
use App\Entity\Course;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SurveyRepository
{
    /**
     * @var Database
     */
    protected $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function findQuestionsBySlug(string $slug): array
    {
        $course = $this->db->find(
            'select * from course where slug = ? and deleted_at is null',
            [$slug]
        );

        if (!$course) {
            return [];
        }

        $course = Course::fromDatabase($course);

        $questions = $this->db->findAll('select * from survey_question where course_id = ? and deleted_at is null', [$course->getId()]);

        return $questions;
    }
}

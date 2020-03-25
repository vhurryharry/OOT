<?php

declare(strict_types=1);

namespace App\Repository;

use App\Database;
use App\Entity\Course;
use App\Entity\CourseReview;
use App\Security\Customer;
use App\Entity\SurveyResult;
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

    public function saveResultsBySlug(string $slug, string $userId, array $results): bool
    {
        if (count($results) == 0) {
            return false;
        }

        $course = $this->db->find('select * from course where slug = ? and deleted_at is null', [$slug]);
        $customer = $this->db->find('select * from customer where id = ? and deleted_at is null', [$userId]);

        if (!$course || !$customer) {
            return false;
        }

        $course = Course::fromDatabase($course);
        $customer = Customer::fromDatabase($customer);

        $this->db->insert('course_review', CourseReview::fromJson([
            'rating' => $results[0]['result'],
            'title' => 'Survey result',
            'author' => $customer->getId(),
            'course' => $course->getId()
        ])->toDatabase());

        foreach ($results as $result) {
            $this->db->insert('survey_result', SurveyResult::fromJson([
                'questionId' => $result['question'],
                'customerId' => $customer->getId(),
                'courseId' => $course->getId(),
                'answer' => strval($result['result']),
                'extra' => $result['type']
            ])->toDatabase());
        }

        return true;
    }
}

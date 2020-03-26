<?php

declare(strict_types=1);

namespace App\Repository;

use App\Database;
use App\Entity\Course;
use App\Entity\CourseReview;
use App\Entity\SurveyQuestion;
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

    public function findQuestionsByCourse(string $id): array
    {
        $course = $this->db->find(
            'select * from course where id = ? and deleted_at is null',
            [$id]
        );

        if (!$course) {
            return [];
        }

        $course = Course::fromDatabase($course);

        $questions = $this->db->findAll('select * from survey_question where course_id = ? and deleted_at is null', [$course->getId()]);

        foreach ($questions as &$question) {
            $question = SurveyQuestion::fromDatabase(($question));
        }

        return $questions;
    }

    public function addDefaultQuestions(UuidInterface $courseId, array $instructors)
    {
        $city = $this->db->find("select city from course where id = ?", [$courseId])['city'];

        $this->db->insert('survey_question', SurveyQuestion::fromJson([
            'question' => 'Please rate the quality of the overall program',
            'courseId' => $courseId,
            'type' => 'rating',
            'extra' => ''
        ])->toDatabase());

        $this->db->insert('survey_question', SurveyQuestion::fromJson([
            'question' => 'Please rate the overall quality of the instruction in the course',
            'courseId' => $courseId,
            'type' => 'rating',
            'extra' => ''
        ])->toDatabase());

        $this->db->insert('survey_question', SurveyQuestion::fromJson([
            'question' => 'How did you like the venue (' . $city . ') for this course?',
            'courseId' => $courseId,
            'type' => 'rating',
            'extra' => ''
        ])->toDatabase());

        $this->db->insert('survey_question', SurveyQuestion::fromJson([
            'question' => 'How would you rate the overall organization of the program?',
            'courseId' => $courseId,
            'type' => 'rating',
            'extra' => ''
        ])->toDatabase());

        $this->db->insert('survey_question', SurveyQuestion::fromJson([
            'question' => 'How well do you think the program has prepared you for your professional/personal goals related to this topic?',
            'courseId' => $courseId,
            'type' => 'rating',
            'extra' => ''
        ])->toDatabase());

        foreach ($instructors as &$instructor) {
            $name = $this->db->find("select concat(first_name, ' ', last_name) as name from customer where id = ?", [$instructor['id']])['name'];

            $this->db->insert('survey_question', SurveyQuestion::fromJson([
                'question' => 'Please rate the overall quality of instruction in sessions led by ' . $name,
                'courseId' => $courseId,
                'type' => 'rating',
                'extra' => ''
            ])->toDatabase());
        }
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

        foreach ($questions as &$question) {
            $question = SurveyQuestion::fromDatabase(($question));
        }

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

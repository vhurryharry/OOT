<?php

declare(strict_types=1);

namespace App\Repository;

use App\Database;
use App\Entity\Course;
use App\Entity\CourseInstructor;
use App\Entity\CourseOption;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CourseRepository
{
    /**
     * @var Database
     */
    protected $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function findAll(): array
    {
        $courses = $this->db->findAll(
            "select id, title, slug, city, start_date, last_date, spots, categories from course where deleted_at is null and status <> 'pending_confirmation'"
        );

        if (!$courses) {
            return [];
        }

        foreach ($courses as &$course) {
            $reviews = $this->db->find('select sum(rating), count(rating) from course_review where course = ? and deleted_at is null', [$course['id']]);

            $course['rating'] = $reviews['count'] > 0 ? $reviews['sum'] / $reviews['count'] : 0;

            $categoryIds = substr($course['categories'], 1, -1);
            if (strlen($categoryIds) == 0) {
                $categories = [];
            } else {
                $categories = $this->db->findAll("select * from course_category where id IN ($categoryIds)");
            }

            $course['categories'] = $categories;

            $course['reservations'] = $this->db->find('select count(*) from course_reservation where course_id = ?', [$course['id']])['count'];
        }

        return $courses;
    }

    public function addNewCourse(Course $course)
    {
        $this->db->insert('course', $course->toDatabase());

        return $course->getId();
    }

    public function addCourseOption(UuidInterface $courseId, int $price)
    {
        $option = CourseOption::fromJson([
            "course" => $courseId,
            "price" => $price,
            "title" => "New",
            "dates" => [],
            "combo" => "f"
        ]);

        return $this->db->insert('course_option', $option->toDatabase());
    }

    public function addInstructor(UuidInterface $courseId, UuidInterface $instructorId)
    {
        $instructor = new CourseInstructor();
        $instructor->setCourseId($courseId);
        $instructor->setCustomerId($instructorId);

        return $this->db->insert('course_instructor', $instructor->toDatabase());
    }

    public function getCategories(): array
    {
        $availableCategories = $this->db->findAll(
            'select * from course_category where deleted_at is null'
        );

        return $availableCategories;
    }

    public function getNextCourseInfo()
    {
        return $this->db->find("select c.start_date, c.city from course as c inner join (select min(start_date) from course where deleted_at is null and start_date > NOW()) as c1 on c.start_date = c1.min");
    }

    public function getCourseWithPrice(string $id): array
    {
        $course = $this->db->find('select * from course where id = ? and deleted_at is null', [$id]);

        if (!$course) {
            throw new NotFoundHttpException();
        }

        $options = $this->db->findAll('select id, title, price, dates, combo from course_option where course = ? and deleted_at is null', [$course['id']]);

        $course['price'] = $options[0]['price'];

        return $course;
    }

    public function findBySlug(string $userId, string $slug): array
    {
        $course = $this->db->find(
            'select * from course where slug = ? and deleted_at is null',
            [$slug]
        );

        if (!$course) {
            throw new NotFoundHttpException();
        }

        $topicIds = substr($course['topics'], 1, -1);
        if (strlen($topicIds) == 0) {
            $topics = [];
        } else {
            $topics = $this->db->findAll("select * from course_topic where id IN ($topicIds)");
        }

        $course = Course::fromDatabase($course)->jsonSerialize();

        $course['topics'] = $topics;
        $course['reserved_count'] = $this->db->find('select count(*) from course_reservation where course_id = ?', [$course['id']])['count'];
        if ($userId != 'default') {
            $course['reserved'] = $this->db->find('select status from course_reservation where course_id = ? and customer_id = ?', [$course['id'], $userId]);
            if ($course['reserved'] == []) {
                $course['reserved'] = null;
            }
        } else {
            $course['reserved'] = null;
        }

        $options = $this->db->findAll('select id, title, price, dates, combo from course_option where course = ? and deleted_at is null', [$course['id']]);
        $course['price'] = 0;
        if (count($options) > 0)
            $course['price'] = $options[0]['price'];

        $rating = $this->db->find('select sum(rating), count(rating) from course_review where course = ? and deleted_at is null', [$course['id']]);
        $course['rating'] = $rating['count'] > 0 ? $rating['sum'] / $rating['count'] : 0;
        $course['count'] = $rating['count'];

        $course['testimonials'] = $this->db->findAll('select testimonial, author, author_occupation, author_avatar from course_testimonial where course = ?', [$course['id']]);

        $course['instructors'] = $this->db->findAll("select concat(i.first_name, ' ', i.last_name) as name, i.avatar from course_instructor as ci join customer as i on ci.customer_id = i.id where ci.course_id = ? and i.deleted_at is null", [$course['id']]);

        //$course['faq'] = $this->db->findAll('select title, content from faq where course = ? and deleted_at is null', [$course['id']]);

        return $course;
    }
}

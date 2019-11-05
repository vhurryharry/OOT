<?php

declare(strict_types=1);

namespace App\Repository;

use App\Database;
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
        return $this->db->findAll('select title, hero, slug from course where deleted_at is null');
    }

    public function findBySlug(string $slug): array
    {
        $course = $this->db->find(
            'select * from course where slug = ? and deleted_at is null',
            [$slug]
        );

        if (!$course) {
            throw new NotFoundHttpException();
        }

        $course['options'] = $this->db->findAll('select id, title, price, location, dates from course_option where course = ? and deleted_at is null', [$course['id']]);

        $course['reviews'] = $this->db->findAll('select title, content, rating from course_review where course = ? and deleted_at is null', [$course['id']]);

        $course['testimonials'] = $this->db->findAll('select title, content, author_text from course_testimonial where course = ? and deleted_at is null', [$course['id']]);

        $course['instructors'] = $this->db->findAll("select concat(i.first_name, ' ', i.last_name) as name from course_instructor as ci join customer as i on ci.customer_id = i.id where ci.course_id = ? and i.deleted_at is null", [$course['id']]);

        return $course;
    }
}

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
        $courses = $this->db->findAll(
            'select * from course where deleted_at is null'
        );

        if (!$courses) {
            throw new NotFoundHttpException();
        }

        foreach ($courses as &$course) {
            $reviews = $this->db->findAll('select title, content, rating from course_review where course = ? and deleted_at is null', [$course['id']]);
            $rating = 0;
    
            if(count($reviews) > 0) {
                foreach ($reviews as $review) {
                    $rating += $review['rating'];
                }
                $rating /= count($reviews);
            }
    
            $course['rating'] = $rating;
    
            $categoryIds = substr($course['categories'], 1, -1);
            if(strlen($categoryIds) == 0) {
                $categories = [];
            } else {
                $categories = $this->db->findAll("select * from course_category where id IN ($categoryIds)");
            }
            
            $course['categories'] = $categories;

            $course['reservations'] = $this->db->find('select count(*) from course_reservation where course_id = ?', [$course['id']])['count'];
        }

        return $courses;
    }

    public function getCategories(): array 
    {
        $availableCategories = $this->db->findAll(
            'select * from course_category where deleted_at is null'
        );

        return $availableCategories;
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

        $course['options'] = $this->db->findAll('select id, title, price, dates, combo from course_option where course = ? and deleted_at is null', [$course['id']]);

        $course['reviews'] = $this->db->findAll('select title, content, rating from course_review where course = ? and deleted_at is null', [$course['id']]);

        $course['testimonials'] = $this->db->findAll('select title, content, author_text from course_testimonial where course = ? and deleted_at is null', [$course['id']]);

        $course['instructors'] = $this->db->findAll("select concat(i.first_name, ' ', i.last_name) as name from course_instructor as ci join customer as i on ci.customer_id = i.id where ci.course_id = ? and i.deleted_at is null", [$course['id']]);

        $course['faq'] = $this->db->findAll('select title, content from faq where course = ? and deleted_at is null', [$course['id']]);

        return $course;
    }
}

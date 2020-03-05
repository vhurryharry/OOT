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
            'select id, title, slug, city, start_date, spots, categories from course where deleted_at is null'
        );

        if (!$courses) {
            throw new NotFoundHttpException();
        }

        foreach ($courses as &$course) {
            $reviews = $this->db->find('select sum(rating), count(rating) from course_review where course = ? and deleted_at is null', [$course['id']]);
    
            $course['rating'] = $reviews['count'] > 0 ? $reviews['sum'] / $reviews['count'] : 0;
    
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

    public function getNextCourseDate()
    {
        return $this->db->find("select min(start_date) from course where deleted_at is null and start_date > NOW()")['min'];
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

        $topicIds = substr($course['topics'], 1, -1);
        if(strlen($topicIds) == 0) {
            $topics = [];
        } else {
            $topics = $this->db->findAll("select * from course_topic where id IN ($topicIds)");
        }
        
        $course['topics'] = $topics;

        $course['options'] = $this->db->findAll('select id, title, price, dates, combo from course_option where course = ? and deleted_at is null', [$course['id']]);

        $rating = $this->db->find('select sum(rating), count(rating) from course_review where course = ? and deleted_at is null', [$course['id']]);
        $course['rating'] = $rating['count'] > 0 ? $rating['sum'] / $rating['count'] : 0;
        $course['count'] = $rating['count'];

        $course['testimonials'] = $this->db->findAll('select a.title, a.content, b.first_name, b.last_name, b.occupation from course_testimonial a left join customer b on a.author = b."id" where course = ?', [$course['id']]);

        $course['instructors'] = $this->db->findAll("select concat(i.first_name, ' ', i.last_name) as name, i.avatar from course_instructor as ci join customer as i on ci.customer_id = i.id where ci.course_id = ? and i.deleted_at is null", [$course['id']]);

        //$course['faq'] = $this->db->findAll('select title, content from faq where course = ? and deleted_at is null', [$course['id']]);

        return $course;
    }
}

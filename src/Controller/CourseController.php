<?php

declare(strict_types=1);

namespace App\Controller;

use App\Database;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CourseController extends AbstractController
{
    /**
     * @var Database
     */
    protected $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * @Route("/courses", name="courses")
     */
    public function list()
    {
        $courses = $this->db->findAll('select title, slug from course where deleted_at is null');

        return $this->render('course/list.html.twig', [
            'courses' => $courses,
        ]);
    }

    /**
     * @Route("/courses/{slug}", name="course", requirements={"slug"="[a-zA-Z0-9\-]+"})
     */
    public function course(string $slug)
    {
        $course = $this->db->find('select * from course where slug = ? and deleted_at is null', [$slug]);

        if (!$course) {
            throw new NotFoundHttpException();
        }

        $options = $this->db->findAll('select id, title, price, location, dates from course_option where course = ? and deleted_at is null', [$course['id']]);
        $reviews = $this->db->findAll('select title, content, rating from course_review where course = ? and deleted_at is null', [$course['id']]);
        $testimonials = $this->db->findAll('select title, content, author_text from course_testimonial where course = ? and deleted_at is null', [$course['id']]);
        $instructors = $this->db->findAll("select concat(i.first_name, ' ', i.last_name) as name from course_instructor as ci join customer as i on ci.customer_id = i.id where ci.course_id = ? and i.deleted_at is null", [$course['id']]);

        return $this->render('course/detail.html.twig', [
            'course' => $course,
            'options' => $options,
            'reviews' => $reviews,
            'testimonials' => $testimonials,
            'instructors' => $instructors,
        ]);
    }
}

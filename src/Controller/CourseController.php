<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\CourseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CourseController extends AbstractController
{
    /**
     * @var CourseRepository
     */
    protected $courseRepository;

    public function __construct(CourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    /**
     * @Route("/courses", name="courses")
     */
    public function list()
    {
        $courses = $this->courseRepository->findAll();

        return $this->render('course/list.html.twig', [
            'courses' => $courses,
        ]);
    }

    /**
     * @Route("/courses/{slug}", name="course", requirements={"slug"="[a-zA-Z0-9\-]+"})
     */
    public function course(string $slug)
    {
        $course = $this->courseRepository->findBySlug($slug);

        return $this->render('course/detail.html.twig', [
            'course' => $course,
        ]);
    }
}

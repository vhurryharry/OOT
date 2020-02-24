<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\State;
use App\CsvExporter;
use App\Database;
use App\Entity\CourseTestimonial;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CourseTestimonialController extends AbstractController
{
    /**
     * @var Database
     */
    protected $db;

    /**
     * @var CsvExporter
     */
    protected $csv;

    public function __construct(Database $db, CsvExporter $csv)
    {
        $this->db = $db;
        $this->csv = $csv;
    }

    /**
     * @Route("/", methods={"GET"})
     */
    public function courseTestimonials(Request $request)
    {
        $testimonials = $this->db->findAll(
            'select * from course_testimonial'
        );

        $items = [];
        foreach ($testimonials as $course_testimonial) {
            $items[] = (CourseTestimonial::fromDatabase($course_testimonial))->jsonSerialize();
        }

        return new JsonResponse([
            'items' => $items,
			'total' => $this->db->count('course_testimonial'),
			'alive' => $this->db->count('course_testimonial', false)
        ]);
    }

    /**
     * @Route("/search", methods={"POST"})
     */
    public function search(Request $request)
    {
        $state = State::fromDatagrid($request->request->all());
        $testimonials = $this->db->findAll(
            'select * from course_testimonial ' . $state->toQuery(),
            $state->toQueryParams()
        );

        $items = [];
        foreach ($testimonials as $course_testimonial) {
            $items[] = (CourseTestimonial::fromDatabase($course_testimonial))->jsonSerialize();
        }

        return new JsonResponse([
            'items' => $items,
			'total' => $this->db->count('course_testimonial'),
			'alive' => $this->db->count('course_testimonial', false)
        ]);
    }

    /**
     * @Route("/find", methods={"POST"})
     */
    public function find(Request $request)
    {
        $course_testimonial = $this->db->find('select * from course_testimonial where id = ?', [$request->get('id')]);
        if (!$course_testimonial) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse(CourseTestimonial::fromDatabase($course_testimonial));
    }

    /**
     * @Route("/create", methods={"POST"})
     */
    public function create(Request $request)
    {
        $this->db->insert('course_testimonial', CourseTestimonial::fromJson($request->request->all())->toDatabase());

        return new JsonResponse();
    }

    /**
     * @Route("/update", methods={"POST"})
     */
    public function update(Request $request)
    {
        $this->db->update('course_testimonial', CourseTestimonial::fromJson($request->request->all())->toDatabase());

        return new JsonResponse();
    }

    /**
     * @Route("/delete", methods={"POST"})
     */
    public function delete(Request $request)
    {
        $ids = $request->request->get('ids');

        $params = [];
        foreach ($ids as $id) {
            $params[] = 'id = ?';
        }

        $this->db->execute(
            sprintf('update course_testimonial set deleted_at = now() where %s', implode('or ', $params)),
            $ids
        );

        return new JsonResponse();
    }

    /**
     * @Route("/restore", methods={"POST"})
     */
    public function restore(Request $request)
    {
        $ids = $request->request->get('ids');

        $params = [];
        foreach ($ids as $id) {
            $params[] = 'id = ?';
        }

        $this->db->execute(
            sprintf('update course_testimonial set deleted_at = null where %s', implode('or ', $params)),
            $ids
        );

        return new JsonResponse();
    }

    /**
     * @Route("/export", methods={"POST"})
     */
    public function export(Request $request)
    {
        $ids = $request->request->get('ids');

        $params = [];
        foreach ($ids as $id) {
            $params[] = 'id = ?';
        }

        if (empty($params)) {
            $testimonials = $this->db->findAll('select * from course_testimonial');
        } else {
            $testimonials = $this->db->findAll(
                sprintf('select * from course_testimonial where %s', implode('or ', $params)),
                $ids
            );
        }

        return new JsonResponse(['csv' => $this->csv->export($testimonials)]);
    }

    /**
     * @Route("/home", methods={"GET"})
     */
    public function courseTestimonialsForHome(Request $request)
    {
        $testimonials = $this->db->findAll(
            'select a.title, a.content, b.first_name, b.last_name, b.occupation from course_testimonial a left join customer b on a.author = b."id" where course IS NULL '
        );

        return new JsonResponse([
            'testimonials' => $testimonials
        ]);
    }
}

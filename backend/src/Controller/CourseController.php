<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\State;
use App\CsvExporter;
use App\Database;
use App\Repository\CourseRepository;
use App\Entity\Course;
use App\Security\Customer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CourseController extends AbstractController
{
    /**
     * @var Database
     */
    protected $db;

    /**
     * @var CsvExporter
     */
	protected $csv;
	
    /**
     * @var CourseRepository
     */
    protected $courseRepository;

    public function __construct(Database $db, CsvExporter $csv, CourseRepository $courseRepository)
    {
        $this->db = $db;
		$this->csv = $csv;
		$this->courseRepository = $courseRepository;
    }

    /**
     * @Route("/search", methods={"POST"})
     */
    public function search(Request $request)
    {
        $state = State::fromDatagrid($request->request->all());
        $courses = $this->db->findAll(
            'select * from course ' . $state->toQuery(),
            $state->toQueryParams()
        );

        $items = [];
        foreach ($courses as $course) {
            $items[] = (Course::fromDatabase($course))->jsonSerialize();
        }

        return new JsonResponse([
            'items' => $items,
			'total' => $this->db->count('course'),
            'alive' => $this->db->count('course', false),
        ]);
    }

    /**
     * @Route("/find", methods={"POST"})
     */
    public function find(Request $request)
    {
        $course = $this->db->find('select * from course where id = ?', [$request->get('id')]);
        if (!$course) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse(Course::fromDatabase($course));
    }

    /**
     * @Route("/instructor/find", methods={"POST"})
     */
    public function findInstructors(Request $request)
    {
        $instructors = $this->db->findAll('select ci.id as relation, c.* from course_instructor as ci join customer as c on ci.customer_id = c.id where ci.course_id = ?', [$request->get('id')]);

        $result = [];
        foreach ($instructors as $instructor) {
            $item = (Customer::fromDatabase($instructor))->jsonSerialize();
            $relation = ['relation' => $instructor['relation']];
            $result[] = array_merge($item, $relation);
        }

        return new JsonResponse($result);
    }

    /**
     * @Route("/create", methods={"POST"})
     */
    public function create(Request $request)
    {
        $this->db->insert('course', Course::fromJson($request->request->all())->toDatabase());

        return new JsonResponse();
    }

    /**
     * @Route("/update", methods={"POST"})
     */
    public function update(Request $request)
    {
        $this->db->update('course', Course::fromJson($request->request->all())->toDatabase());

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
            sprintf('update course set deleted_at = now() where %s', implode('or ', $params)),
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
            sprintf('update course set deleted_at = null where %s', implode('or ', $params)),
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
            $courses = $this->db->findAll('select * from course');
        } else {
            $courses = $this->db->findAll(
                sprintf('select * from course where %s', implode('or ', $params)),
                $ids
            );
        }

        return new JsonResponse(['csv' => $this->csv->export($courses)]);
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

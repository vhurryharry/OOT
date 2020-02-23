<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\State;
use App\CsvExporter;
use App\Database;
use App\Entity\CourseTopic;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CourseTopicController extends AbstractController
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
    public function courseTopics(Request $request)
    {
        $topics = $this->db->findAll(
            'select * from course_topic'
        );

        $items = [];
        foreach ($topics as $course_topic) {
            $items[] = (CourseTopic::fromDatabase($course_topic))->jsonSerialize();
        }

        return new JsonResponse([
            'items' => $items,
			'total' => $this->db->count('course_topic'),
			'alive' => $this->db->count('course_topic', false)
        ]);
    }

    /**
     * @Route("/search", methods={"POST"})
     */
    public function search(Request $request)
    {
        $state = State::fromDatagrid($request->request->all());
        $topics = $this->db->findAll(
            'select * from course_topic ' . $state->toQuery(),
            $state->toQueryParams()
        );

        $items = [];
        foreach ($topics as $course_topic) {
            $items[] = (CourseTopic::fromDatabase($course_topic))->jsonSerialize();
        }

        return new JsonResponse([
            'items' => $items,
			'total' => $this->db->count('course_topic'),
			'alive' => $this->db->count('course_topic', false)
        ]);
    }

    /**
     * @Route("/find", methods={"POST"})
     */
    public function find(Request $request)
    {
        $course_topic = $this->db->find('select * from course_topic where id = ?', [$request->get('id')]);
        if (!$course_topic) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse(CourseTopic::fromDatabase($course_topic));
    }

    /**
     * @Route("/create", methods={"POST"})
     */
    public function create(Request $request)
    {
        $this->db->insert('course_topic', CourseTopic::fromJson($request->request->all())->toDatabase());

        return new JsonResponse();
    }

    /**
     * @Route("/update", methods={"POST"})
     */
    public function update(Request $request)
    {
        $this->db->update('course_topic', CourseTopic::fromJson($request->request->all())->toDatabase());

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
            sprintf('update course_topic set deleted_at = now() where %s', implode('or ', $params)),
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
            sprintf('update course_topic set deleted_at = null where %s', implode('or ', $params)),
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
            $topics = $this->db->findAll('select * from course_topic');
        } else {
            $topics = $this->db->findAll(
                sprintf('select * from course_topic where %s', implode('or ', $params)),
                $ids
            );
        }

        return new JsonResponse(['csv' => $this->csv->export($topics)]);
    }
}

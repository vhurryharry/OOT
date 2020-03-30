<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\State;
use App\CsvExporter;
use App\Database;
use App\Repository\CourseRepository;
use App\Repository\CustomerRepository;
use App\Repository\SurveyRepository;
use App\Entity\Course;
use App\Entity\CourseInstructor;
use App\Security\Customer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

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
     * @var CustomerRepository
     */
    protected $customerRepository;

    /**
     * @var CourseRepository
     */
    protected $courseRepository;

    /**
     * @var SurveyRepository
     */
    protected $surveyRepository;

    public function __construct(
        Database $db,
        CsvExporter $csv,
        CourseRepository $courseRepository,
        CustomerRepository $customerRepository,
        SurveyRepository $surveyRepository
    ) {
        $this->db = $db;
        $this->csv = $csv;
        $this->courseRepository = $courseRepository;
        $this->customerRepository = $customerRepository;
        $this->surveyRepository = $surveyRepository;
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
     * @Route("/instructor/create", methods={"POST"})
     */
    public function createInstructor(Request $request)
    {
        $courseInstructor = CourseInstructor::fromJson($request->request->all());
        $this->db->insert("course_instructor", $courseInstructor->toDatabase());

        return new JsonResponse();
    }

    /**
     * @Route("/instructor/delete", methods={"POST"})
     */
    public function deleteInstructor(Request $request)
    {
        $this->db->execute("delete from course_instructor where id = ?", [$request->get('ids')['id']]);

        return new JsonResponse();
    }

    /**
     * @Route("/categories/find", methods={"POST"})
     */
    public function findCategories(Request $request)
    {
        $categoryIds = $this->db->find("select categories from course where id = ?", [$request->get('id')])['categories'];

        if (strlen($categoryIds) == 0) {
            return new JsonResponse([]);
        }

        $categoryIds = substr($categoryIds, 1, -1);
        if (strlen($categoryIds) == 0) {
            $categories = [];
        } else {
            $categories = $this->db->findAll("select * from course_category where id IN ($categoryIds)");
        }

        return new JsonResponse($categories);
    }

    /**
     * @Route("/categories/create", methods={"POST"})
     */
    public function addCategories(Request $request)
    {
        $course = $this->db->find("select * from course where id = ?", [$request->get('course_id')]);
        $categoryIds = $course['categories'];

        if (strlen($categoryIds) == 0) {
            return new JsonResponse([]);
        }

        $categoryIds = substr($categoryIds, 1, -1);
        if (strlen($categoryIds) == 0) {
            $course['categories'] = "{" . $request->get('category_id') . "}";
        } else {
            $course['categories'] = "{" . $categoryIds . "," . $request->get('category_id') . "}";
        }
        $this->db->update("course", $course);

        return new JsonResponse();
    }

    /**
     * @Route("/categories/delete", methods={"POST"})
     */
    public function deleteCategories(Request $request)
    {
        $ids = $request->get('ids');
        $course = $this->db->find("select * from course where id = ?", [$ids['course_id']]);
        $categoryIds = $course['categories'];

        if (strlen($categoryIds) == 0) {
            return new JsonResponse([]);
        }

        $categoryIds = substr($categoryIds, 1, -1);
        $categoryIds = explode(",", $categoryIds);

        $pos = array_search($ids['category_id'], $categoryIds);

        unset($categoryIds[$pos]);
        $categoryIds = implode(",", $categoryIds);

        $course['categories'] = "{" . $categoryIds . "}";
        $this->db->update("course", $course);

        return new JsonResponse();
    }


    /**
     * @Route("/topics/find", methods={"POST"})
     */
    public function findTopics(Request $request)
    {
        $topicIds = $this->db->find("select topics from course where id = ?", [$request->get('id')])['topics'];

        if (strlen($topicIds) == 0) {
            return new JsonResponse([]);
        }

        $topicIds = substr($topicIds, 1, -1);
        if (strlen($topicIds) == 0) {
            $topics = [];
        } else {
            $topics = $this->db->findAll("select * from course_topic where id IN ($topicIds)");
        }

        return new JsonResponse($topics);
    }

    /**
     * @Route("/topics/create", methods={"POST"})
     */
    public function addTopics(Request $request)
    {
        $course = $this->db->find("select * from course where id = ?", [$request->get('course_id')]);
        $topicIds = $course['topics'];

        if (strlen($topicIds) == 0) {
            return new JsonResponse([]);
        }

        $topicIds = substr($topicIds, 1, -1);
        if (strlen($topicIds) == 0) {
            $course['topics'] = "{" . $request->get('topic_id') . "}";
        } else {
            $course['topics'] = "{" . $topicIds . "," . $request->get('topic_id') . "}";
        }
        $this->db->update("course", $course);

        return new JsonResponse();
    }

    /**
     * @Route("/topics/delete", methods={"POST"})
     */
    public function deleteTopics(Request $request)
    {
        $ids = $request->get('ids');
        $course = $this->db->find("select * from course where id = ?", [$ids['course_id']]);
        $topicIds = $course['topics'];

        if (strlen($topicIds) == 0) {
            return new JsonResponse([]);
        }

        $topicIds = substr($topicIds, 1, -1);
        $topicIds = explode(",", $topicIds);

        $pos = array_search($ids['topic_id'], $topicIds);

        unset($topicIds[$pos]);
        $topicIds = implode(",", $topicIds);

        $course['topics'] = "{" . $topicIds . "}";
        $this->db->update("course", $course);

        return new JsonResponse();
    }

    /**
     * @Route("/create", methods={"POST"})
     */
    public function create(Request $request)
    {
        $id = $this->db->insert('course', Course::fromJson($request->request->all())->toDatabase());

        $this->surveyRepository->addDefaultQuestions(Uuid::fromString($id), []);

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
     * @Route("/", name="courses")
     */
    public function list()
    {
        $courses = $this->courseRepository->findAll();
        $categories = $this->courseRepository->getCategories();

        return new JsonResponse([
            "courses" => $courses,
            "categories" => $categories
        ]);
    }

    /**
     * @Route("/instructors", methods={"GET"})
     */
    public function getInstructors(Request $request)
    {
        $instructors = $this->customerRepository->getInstructors();

        return new JsonResponse([
            "instructors" => $instructors
        ]);
    }

    /**
     * @Route("/new", methods={"POST"})
     */
    public function newCourse(Request $request)
    {
        $rawCourse = $request->request->get('course');
        $course = Course::fromJson($rawCourse);

        $prevCourse = $this->courseRepository->findBySlug("default", $course->getSlug());

        if ($prevCourse != null) {
            return new JsonResponse([
                "success" => false,
                "error" => "Course already exist for that title!"
            ]);
        }

        $courseId = $this->courseRepository->addNewCourse($course);
        $this->courseRepository->addCourseOption(Uuid::fromString($courseId), (int) $rawCourse['tuition']);

        $instructors = $rawCourse['instructors'];

        foreach ($instructors as $instructor) {
            $this->courseRepository->addInstructor(Uuid::fromString($courseId), Uuid::fromString($instructor['id']));
        }

        $this->surveyRepository->addDefaultQuestions($courseId, $instructors);

        return new JsonResponse([
            "success" => true
        ]);
    }

    /**
     * @Route("/find/{userId}/{slug}", name="course", requirements={"slug"="[a-zA-Z0-9\-]+"})
     */
    public function course(string $userId, string $slug)
    {
        $course = $this->courseRepository->findBySlug($userId, $slug);

        if ($course == null) {
            return new JsonResponse([
                "success" => false,
                "error" => "Course not found for that slug!"
            ]);
        }

        return new JsonResponse([
            "success" => true,
            "course" => $course
        ]);
    }

    /**
     * @Route("/next-course-info", methods={"GET"})
     */
    public function getNextCourseInfo()
    {
        $nextCourse = $this->courseRepository->getNextCourseInfo();

        return new JsonResponse([
            "nextCourse" => $nextCourse
        ]);
    }
}

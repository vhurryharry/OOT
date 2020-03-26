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
use Symfony\Component\Filesystem\Filesystem;
use Aws\Sdk;
use App\Utils\Aws\AwsS3Util;
use Ramsey\Uuid\Uuid;

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
            'select * from course_testimonial where'
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
            'select * from course_testimonial where course is null ' . $state->toQuery(),
            $state->toQueryParams()
        );

        $items = [];
        foreach ($testimonials as $course_testimonial) {
            $items[] = (CourseTestimonial::fromDatabase($course_testimonial))->jsonSerialize();
        }

        return new JsonResponse([
            'items' => $items,
            'total' => $this->db->find('select count(*) from course_testimonial where course is null')['count'],
            'alive' => $this->db->find('select count(*) from course_testimonial where course is null and deleted_at is null')['count']
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
     * @Route("/findByCourse", methods={"POST"})
     */
    public function findByCourse(Request $request)
    {
        $testimonials = $this->db->findAll('select * from course_testimonial where course = ?', [$request->get('id')]);
        if (!$testimonials) {
            return new JsonResponse([
                'items' => []
            ]);
        }

        $items = [];
        foreach ($testimonials as $course_testimonial) {
            $items[] = (CourseTestimonial::fromDatabase($course_testimonial))->jsonSerialize();
        }

        return new JsonResponse([
            'items' => $items
        ]);
    }

    /**
     * @Route("/create", methods={"POST"})
     */
    public function create(Request $request)
    {
        $id = $this->db->insert('course_testimonial', CourseTestimonial::fromJson($request->request->all())->toDatabase());

        return new JsonResponse([
            'id' => $id
        ]);
    }

    /**
     * @Route("/file/{id}", methods={"POST"})
     */
    public function uploadFile(string $id, Request $request)
    {
        if ($request->files->get('file')) {
            $tempDirectory = "temp/";

            $filesystem = new Filesystem();
            $filesystem->mkdir($tempDirectory);

            $uploadedFile = $request->files->get('file');
            $uploadedFile->move($tempDirectory, $id . ".jpg");

            $sharedConfig = [
                'region' => 'us-east-2',
                'version' => 'latest',
                'credentials' => [
                    'key' => $this->getParameter('env(S3_KEY)'),
                    'secret' => $this->getParameter('env(S3_SECRET)')
                ]
            ];
            $sdk = new Sdk($sharedConfig);

            $s3 = new AwsS3Util($sdk);
            $avatarUrl = $s3->putObject('ootdev', $tempDirectory . $id . ".jpg", "testimonial_avatars/" . Uuid::uuid4() . '.jpg');

            $filesystem->remove([$tempDirectory . $id . '.jpg']);

            $query = "update course_testimonial set author_avatar = '" . $avatarUrl . "' where id = " . $id;

            $this->db->execute($query);

            return new JsonResponse([
                'success' => true,
                "avatar" => $avatarUrl,
                'error' => null
            ]);
        }

        return new JsonResponse([
            'success' => false,
            "avatar" => "",
            'error' => "No file to upload"
        ]);
    }

    /**
     * @Route("/update", methods={"POST"})
     */
    public function update(Request $request)
    {
        $id = $this->db->update('course_testimonial', CourseTestimonial::fromJson($request->request->all())->toDatabase());

        return new JsonResponse([
            'id' => $id
        ]);
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
            'select testimonial, author, author_occupation, author_avatar from course_testimonial where course IS NULL and deleted_at is null'
        );

        return new JsonResponse([
            'testimonials' => $testimonials
        ]);
    }
}

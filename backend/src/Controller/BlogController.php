<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\State;
use App\CsvExporter;
use App\Database;
use App\Entity\Blog;
use App\Repository\BlogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Filesystem\Filesystem;
use Aws\Sdk;
use App\Utils\Aws\AwsS3Util;
use Ramsey\Uuid\Uuid;

class BlogController extends AbstractController
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
     * @var BlogRepository
     */
    protected $blogRepository;

    public function __construct(Database $db, CsvExporter $csv, BlogRepository $blogRepository)
    {
        $this->db = $db;
        $this->csv = $csv;
		$this->blogRepository = $blogRepository;
    }

    /**
     * @Route("/search", methods={"POST"})
     */
    public function search(Request $request)
    {
        $state = State::fromDatagrid($request->request->all());
        $blogs = $this->db->findAll(
            'select * from blog ' . $state->toQuery(),
            $state->toQueryParams()
        );

        $items = [];
        foreach ($blogs as $blog) {
            $items[] = (Blog::fromDatabase($blog))->jsonSerialize();
        }

        return new JsonResponse([
            'items' => $items,
			'total' => $this->db->count('blog'),
			'alive' => $this->db->count('blog', false)
        ]);
    }

    /**
     * @Route("/find", methods={"POST"})
     */
    public function find(Request $request)
    {
        $blog = $this->db->find('select * from blog where id = ?', [$request->get('id')]);
        if (!$blog) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse(Blog::fromDatabase($blog));
    }

    /**
     * @Route("/create", methods={"POST"})
     */
    public function create(Request $request)
    {
        $id = $this->db->insert('blog', Blog::fromJson($request->request->all())->toDatabase());

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
            $uploadedFile->move($tempDirectory, $id.".jpg");

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
            $coverUrl = $s3->putObject('ootdev', $tempDirectory.$id.".jpg", "blog/".Uuid::uuid4().'.jpg');

            $filesystem->remove([$tempDirectory.$id.'.jpg']);

            $query = "update blog set cover_image = '".$coverUrl."' where id = ".$id;
            
            $this->db->execute($query);

            return new JsonResponse([
                'success' => true,
                "avatar" => $coverUrl,
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
        $id = $this->db->update('blog', Blog::fromJson($request->request->all())->toDatabase());

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
            sprintf('update blog set deleted_at = now() where %s', implode('or ', $params)),
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
            sprintf('update blog set deleted_at = null where %s', implode('or ', $params)),
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
            $blogs = $this->db->findAll('select * from blog');
        } else {
            $blogs = $this->db->findAll(
                sprintf('select * from blog where %s', implode('or ', $params)),
                $ids
            );
        }

        return new JsonResponse(['csv' => $this->csv->export($blogs)]);
    }

    /**
     * @Route("/", methods={"GET"})
     */
    public function list(Request $request)
    {
        $blogs = $this->blogRepository->findAll();
        $categories = $this->blogRepository->getCategories();

        return new JsonResponse([
            "blogs" => $blogs,
            "categories" => $categories
        ]);
    }

    /**
     * @Route("/find/{slug}", name="blog", requirements={"slug"="[a-zA-Z0-9\-]+"})
     */
    public function blog(string $slug)
    {
        $blog = $this->blogRepository->findBySlug($slug);

        return new JsonResponse([
            "blog" => $blog
        ]);
    }
}

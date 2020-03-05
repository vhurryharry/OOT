<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\State;
use App\CsvExporter;
use App\Database;
use App\Entity\Blog;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

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

    public function __construct(Database $db, CsvExporter $csv)
    {
        $this->db = $db;
        $this->csv = $csv;
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
        $this->db->insert('blog', Blog::fromJson($request->request->all())->toDatabase());

        return new JsonResponse();
    }

    /**
     * @Route("/update", methods={"POST"})
     */
    public function update(Request $request)
    {
        $this->db->update('blog', Blog::fromJson($request->request->all())->toDatabase());

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
}

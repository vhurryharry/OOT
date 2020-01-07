<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\State;
use App\CsvExporter;
use App\Database;
use App\Entity\Tag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class TagController extends AbstractController
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
        $tags = $this->db->findAll(
            'select * from tag ' . $state->toQuery(),
            $state->toQueryParams()
        );

        $items = [];
        foreach ($tags as $tag) {
            $items[] = (Tag::fromDatabase($tag))->jsonSerialize();
        }

        return new JsonResponse([
            'items' => $items,
            'total' => $this->db->count('tag'),
            'alive' => $this->db->count('tag', false),
        ]);
    }

    /**
     * @Route("/find", methods={"POST"})
     */
    public function find(Request $request)
    {
        $tag = $this->db->find('select * from tag where id = ?', [$request->get('id')]);
        if (!$tag) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse(Tag::fromDatabase($tag));
    }

    /**
     * @Route("/create", methods={"POST"})
     */
    public function create(Request $request)
    {
        $this->db->insert('tag', Tag::fromJson($request->request->all())->toDatabase());

        return new JsonResponse();
    }

    /**
     * @Route("/update", methods={"POST"})
     */
    public function update(Request $request)
    {
        $this->db->update('tag', Tag::fromJson($request->request->all())->toDatabase());

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
            sprintf('update tag set deleted_at = now() where %s', implode('or ', $params)),
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
            sprintf('update tag set deleted_at = null where %s', implode('or ', $params)),
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
            $tags = $this->db->findAll('select * from tag');
        } else {
            $tags = $this->db->findAll(
                sprintf('select * from tag where %s', implode('or ', $params)),
                $ids
            );
        }

        return new JsonResponse(['csv' => $this->csv->export($tags)]);
    }
}

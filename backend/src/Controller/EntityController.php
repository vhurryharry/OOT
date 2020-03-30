<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\State;
use App\CsvExporter;
use App\Database;
use App\Entity\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class EntityController extends AbstractController
{
    /**
     * @var Database
     */
    protected $db;

    /**
     * 
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
        $entities = $this->db->findAll(
            'select * from entity ' . $state->toQuery(),
            $state->toQueryParams()
        );

        $items = [];
        foreach ($entities as $entity) {
            $items[] = (Entity::fromDatabase($entity))->jsonSerialize();
        }

        return new JsonResponse([
            'items' => $items,
            'total' => $this->db->count('entity'),
            'alive' => $this->db->count('entity', false),
        ]);
    }

    /**
     * @Route("/find", methods={"POST"})
     */
    public function find(Request $request)
    {
        $entity = $this->db->find('select * from entity where id = ?', [$request->get('id')]);
        if (!$entity) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse(Entity::fromDatabase($entity));
    }

    /**
     * @Route("/create", methods={"POST"})
     */
    public function create(Request $request)
    {
        $this->db->insert('entity', Entity::fromJson($request->request->all())->toDatabase());

        return new JsonResponse();
    }

    /**
     * @Route("/update", methods={"POST"})
     */
    public function update(Request $request)
    {
        $this->db->update('entity', Entity::fromJson($request->request->all())->toDatabase());

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
            sprintf('update entity set deleted_at = now() where %s', implode('or ', $params)),
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
            sprintf('update entity set deleted_at = null where %s', implode('or ', $params)),
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
            $entities = $this->db->findAll('select * from entity');
        } else {
            $entities = $this->db->findAll(
                sprintf('select * from entity where %s', implode('or ', $params)),
                $ids
            );
        }

        return new JsonResponse(['csv' => $this->csv->export($entities)]);
    }

    /**
     * @Route("/entity/{slug}", name="entity", requirements={"slug"="[a-zA-Z0-9\-]+"})
     */
    public function entity(string $slug)
    {
        $entity = $this->db->find(
            'select * from entity where slug = ? and deleted_at is null',
            [$slug]
        );

        if (!$entity) {
            return new JsonResponse([
                'success' => false,
                'error' => "No page found for that slug"
            ]);
        }

        return new JsonResponse([
            'success' => true,
            'entity' => $entity
        ]);
    }
}

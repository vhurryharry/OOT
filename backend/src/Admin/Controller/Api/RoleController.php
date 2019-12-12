<?php

declare(strict_types=1);

namespace App\Admin\Controller\Api;

use App\Admin\Repository\State;
use App\CsvExporter;
use App\Database;
use App\Entity\Role;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class RoleController extends AbstractController
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
        $roles = $this->db->findAll(
            'select * from role ' . $state->toQuery(),
            $state->toQueryParams()
        );

        $items = [];
        foreach ($roles as $role) {
            $items[] = (Role::fromDatabase($role))->jsonSerialize();
        }

        return new JsonResponse([
            'items' => $items,
            'total' => $this->db->count('role'),
            'alive' => $this->db->count('role', false),
        ]);
    }

    /**
     * @Route("/find", methods={"POST"})
     */
    public function find(Request $request)
    {
        $role = $this->db->find('select * from role where id = ?', [$request->get('id')]);
        if (!$role) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse(Role::fromDatabase($role));
    }

    /**
     * @Route("/create", methods={"POST"})
     */
    public function create(Request $request)
    {
        $this->db->insert('role', Role::fromJson($request->request->all())->toDatabase());

        return new JsonResponse();
    }

    /**
     * @Route("/update", methods={"POST"})
     */
    public function update(Request $request)
    {
        $this->db->update('role', Role::fromJson($request->request->all())->toDatabase());

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
            sprintf('update role set deleted_at = now() where %s', implode('or ', $params)),
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
            sprintf('update role set deleted_at = null where %s', implode('or ', $params)),
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
            $roles = $this->db->findAll('select * from role');
        } else {
            $roles = $this->db->findAll(
                sprintf('select * from role where %s', implode('or ', $params)),
                $ids
            );
        }

        return new JsonResponse(['csv' => $this->csv->export($roles)]);
    }
}

<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\State;
use App\Security\User;
use App\CsvExporter;
use App\Database;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
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
        $users = $this->db->findAll(
            'select * from "user" ' . $state->toQuery(),
            $state->toQueryParams()
        );

        $items = [];
        foreach ($users as $user) {
            $items[] = (User::fromDatabase($user))->jsonSerialize();
        }

        return new JsonResponse([
            'items' => $items,
            'total' => $this->db->count('user'),
            'alive' => $this->db->count('user', false),
        ]);
    }

    /**
     * @Route("/find", methods={"POST"})
     */
    public function find(Request $request)
    {
        $user = $this->db->find('select * from "user" where id = ?', [$request->get('id')]);
        if (!$user) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse(User::fromDatabase($user));
    }

    /**
     * @Route("/create", methods={"POST"})
     */
    public function create(Request $request)
    {
        $this->db->insert('user', User::fromJson($request->request->all())->toDatabase());

        return new JsonResponse();
    }

    /**
     * @Route("/update", methods={"POST"})
     */
    public function update(Request $request)
    {
        $this->db->update('user', User::fromJson($request->request->all())->toDatabase());

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
            sprintf('update "user" set deleted_at = now() where %s', implode('or ', $params)),
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
            sprintf('update "user" set deleted_at = null where %s', implode('or ', $params)),
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
            $users = $this->db->findAll('select * from "user"');
        } else {
            $users = $this->db->findAll(
                sprintf('select * from "user" where %s', implode('or ', $params)),
                $ids
            );
        }

        return new JsonResponse(['csv' => $this->csv->export($users)]);
    }
}

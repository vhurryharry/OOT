<?php

declare(strict_types=1);

namespace App\Admin\Controller;

use App\Admin\Repository\State;
use App\Database;
use App\Entity\Menu;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class MenuController extends AbstractController
{
    /**
     * @var Database
     */
    protected $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    /**
     * @Route("/search", methods={"POST"})
     */
    public function search(Request $request)
    {
        $state = State::fromDatagrid($request->request->all());
        $menus = $this->db->findAll(
            'select * from menu ' . $state->toQuery('display_order asc'),
            $state->toQueryParams()
        );

        $items = [];
        foreach ($menus as $menu) {
            $items[] = (Menu::fromDatabase($menu))->jsonSerialize();
        }

        return new JsonResponse([
            'items' => $items,
            'total' => $this->db->count('menu'),
            'alive' => $this->db->count('menu', false),
        ]);
    }

    /**
     * @Route("/find", methods={"POST"})
     */
    public function find(Request $request)
    {
        $menu = $this->db->find('select * from menu where id = ?', [$request->get('id')]);
        if (!$menu) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse(Menu::fromDatabase($menu));
    }

    /**
     * @Route("/create", methods={"POST"})
     */
    public function create(Request $request)
    {
        $this->db->insert('menu', Menu::fromJson($request->request->all())->toDatabase());

        return new JsonResponse();
    }

    /**
     * @Route("/update", methods={"POST"})
     */
    public function update(Request $request)
    {
        $this->db->update('menu', Menu::fromJson($request->request->all())->toDatabase());

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
            sprintf('update menu set deleted_at = now() where %s', implode('or ', $params)),
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
            sprintf('update menu set deleted_at = null where %s', implode('or ', $params)),
            $ids
        );

        return new JsonResponse();
    }

    /**
     * @Route("/move", methods={"POST"})
     */
    public function move(Request $request)
    {
        $id = $request->request->get('id');

        if ($request->request->get('type') == 'up') {
            $order = '- 1';
        } else {
            $order = '+ 1';
        }

        $this->db->execute(
            sprintf('update menu set display_order = display_order %s where id = ?', $order),
            [
                $id,
            ]
        );

        return new JsonResponse();
    }
}

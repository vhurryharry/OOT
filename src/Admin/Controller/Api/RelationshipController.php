<?php

declare(strict_types=1);

namespace App\Admin\Controller\Api;

use App\Database;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RelationshipController extends AbstractController
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
     * @Route("/attach/{type}", methods={"POST"}, requirements={"type"="[a-z\_]+"})
     */
    public function attach(Request $request, string $type)
    {
        $this->db->insert($type, $request->request->all());

        return new JsonResponse();
    }

    /**
     * @Route("/detach/{type}", methods={"POST"}, requirements={"type"="[a-z\_]+"})
     */
    public function detach(Request $request, string $type)
    {
        $this->db->execute(
            sprintf('delete from %s where id = ?', $type),
            [$request->request->get('id')]
        );

        return new JsonResponse();
    }
}

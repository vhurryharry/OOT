<?php

declare(strict_types=1);

namespace App\Admin\Controller\Api;

use App\Admin\Repository\State;
use App\Database;
use App\Entity\CourseReservation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
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
        $auditLogs = $this->db->findAll(
            'select * from course_reservation ' . $state->toQuery(),
            $state->toQueryParams()
        );

        $items = [];
        foreach ($auditLogs as $auditLog) {
            $items[] = (CourseReservation::fromDatabase($auditLog))->jsonSerialize();
        }

        return new JsonResponse([
            'items' => $items,
            'total' => $this->db->count('course_reservation'),
        ]);
    }
}

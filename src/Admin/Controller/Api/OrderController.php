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
        $orderQuery = <<<SQL
select
    cr.*,
    row_to_json(c) as customer_json,
    row_to_json(co) as course_json,
    row_to_json(cp) as payment_json
from course_reservation as cr
join customer as c on c.id = cr.customer_id
join course as co on co.id = cr.course_id
left join course_payment as cp on cp.id = cr.payment
SQL;
        $state = State::fromDatagrid($request->request->all());
        $orders = $this->db->findAll(
            $orderQuery . $state->toQuery(),
            $state->toQueryParams()
        );

        $items = [];
        foreach ($orders as $order) {
            $items[] = (CourseReservation::fromDatabase($order))->jsonSerialize();
        }

        return new JsonResponse([
            'items' => $items,
            'total' => $this->db->count('course_reservation'),
        ]);
    }
}

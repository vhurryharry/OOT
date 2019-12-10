<?php

declare(strict_types=1);

namespace App\Admin\Controller\Api;

use App\Admin\Repository\State;
use App\CsvExporter;
use App\Database;
use App\Security\Customer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class CustomerController extends AbstractController
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
        $customers = $this->db->findAll(
            'select * from customer '.$state->toQuery(),
            $state->toQueryParams()
        );

		$items = [];
        foreach ($customers as $customer) {
            $items[] = (Customer::fromDatabase($customer))->jsonSerialize();
        }

        return new JsonResponse([
            'items' => $items,
			'total' => (int) $this->db->execute('select * from customer '.$state->toQuery(false, false),
				$state->toQueryParams()),
			'alive' => (int) $this->db->execute('select * from customer '.$state->toQuery(true, false),
				$state->toQueryParams())
        ]);
    }

    /**
     * @Route("/find", methods={"POST"})
     */
    public function find(Request $request)
    {
        $customer = $this->db->find('select * from customer where id = ?', [$request->get('id')]);
        if (!$customer) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse(Customer::fromDatabase($customer));
    }

    /**
     * @Route("/create", methods={"POST"})
     */
    public function create(Request $request)
    {
        $this->db->insert('customer', Customer::fromJson($request->request->all())->toDatabase());

        return new JsonResponse();
    }

    /**
     * @Route("/update", methods={"POST"})
     */
    public function update(Request $request)
    {
        $this->db->update('customer', Customer::fromJson($request->request->all())->toDatabase());

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
            sprintf('update customer set deleted_at = now() where %s', implode('or ', $params)),
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
            sprintf('update customer set deleted_at = null where %s', implode('or ', $params)),
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
            $customers = $this->db->findAll('select * from customer');
        } else {
            $customers = $this->db->findAll(
                sprintf('select * from customer where %s', implode('or ', $params)),
                $ids
            );
        }

        return new JsonResponse(['csv' => $this->csv->export($customers)]);
    }
}

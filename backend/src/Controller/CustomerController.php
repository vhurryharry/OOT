<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\State;
use App\CsvExporter;
use App\Database;
use App\Security\Customer;
use App\Event\CustomerRegistered;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\LoginType;
use App\Form\RegisterType;
use App\Repository\CustomerRepository;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Filesystem\Filesystem;

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
	
    /**
     * @var CustomerRepository
     */
    protected $customerRepository;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    public function __construct(Database $db, CsvExporter $csv, CustomerRepository $customerRepository, EventDispatcherInterface $eventDispatcher)
    {
        $this->db = $db;
        $this->csv = $csv;
        $this->customerRepository = $customerRepository;
        $this->eventDispatcher = $eventDispatcher;
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

    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request)
    {
        $form = $this->createForm(RegisterType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $customer = $this->customerRepository->register($form->getData());
            $this->addFlash('info', 'You will receive a confirmation email shortly.');
            $this->eventDispatcher->dispatch(new CustomerRegistered($customer));

            return $this->redirectToRoute('homepage');
        }

        return $this->render('register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request, AuthenticationUtils $auth)
    {
        $form = $this->createForm(LoginType::class);

        return $this->render('login.html.twig', [
            'form' => $form->createView(),
            'error' => $auth->getLastAuthenticationError(),
        ]);
    }

    /**
     * @Route("/account", name="account")
     */
    public function account(Request $request)
    {
        $customer = $this->getUser();

        if (!$customer) {
            throw new NotFoundHttpException();
        }

        $reservations = $this->customerRepository->findReservations($customer);

        return $this->render('account.html.twig', [
            'reservations' => $reservations,
        ]);
    }

    /**
     * @Route("/customer", methods={"PUT"})
     */
    public function updateUser(Request $request)
    {
        $customer = $request->get("user");
        $customer = Customer::fromDatabase($customer);

        $this->customerRepository->updateUser($customer);

        return new JsonResponse([
            'success' => true,
            'error' => null
        ]);
    }
}

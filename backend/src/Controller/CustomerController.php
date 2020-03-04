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
use Aws\Sdk;
use App\Utils\Aws\AwsS3Util;
use Ramsey\Uuid\Uuid;

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

    /**
     * @Route("/avatar/{id}", methods={"POST"})
     */
    public function updateUserAvatar(string $id, Request $request)
    {
        if ($request->files->get('avatar')) {
            $tempDirectory = "temp/";

            $filesystem = new Filesystem();
            $filesystem->mkdir($tempDirectory);

            $uploadedFile = $request->files->get('avatar');
            $uploadedFile->move($tempDirectory, $id.".jpg");

            $customer = $this->customerRepository->getCustomer($id);

            if (!$customer) {
                throw new NotFoundHttpException();
            }

            $customer = Customer::fromDatabase($customer);

            $sharedConfig = [
                'region' => 'us-east-2',
                'version' => 'latest',
                'credentials' => [
                    'key' => 'AKIA2BEPBX4AEHLFSNEM',
                    'secret' => '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'
                ]
            ];
            $sdk = new Sdk($sharedConfig);

            $s3 = new AwsS3Util($sdk);
            $avatarUrl = $s3->putObject('ootdev', $tempDirectory.$id.".jpg", "avatars/".Uuid::uuid4().'.jpg');

            $customer->setAvatar($avatarUrl);

            $filesystem->remove([$tempDirectory.$id.'.jpg']);

            $this->customerRepository->updateUser($customer);

            return new JsonResponse([
                'success' => true,
                "avatar" => $avatarUrl,
                'error' => null
            ]);
        }

        return new JsonResponse([
            'success' => false,
            "avatar" => "",
            'error' => "No file to upload"
        ]);
    }

    /**
     * @Route("/password", methods={"PUT"})
     */
    public function updatePassword(Request $request)
    {
        $data = $request->get("user");
        $customer = $this->customerRepository->findByLogin($data['login']);
        $customer = Customer::fromDatabase($customer);

        if(!$this->customerRepository->checkPassword($customer, $data['oldPassword'])) { 
            return new JsonResponse([
                'success' => false,
                'error' => "Invalid Password!"
            ]);           
        }

        $this->customerRepository->updatePassword($customer, $data['newPassword']);
        
        return new JsonResponse([
            'success' => true,
            'error' => null
        ]);
    }

    /**
     * @Route("/my-courses/{id}", methods={"GET"})
     */
    public function getMyCourses(string $id)
    {
        $customer = $this->customerRepository->getCustomer($id);
        
        if (!$customer) {
            return new JsonResponse([
                'success' => false,
                'error' => 'User not found!'
            ]);
        }

        $customer = Customer::fromDatabase($customer);

        $reservations = $this->customerRepository->findReservations($customer);
        $courses = [];

        $today = date("Y-m-d");

        foreach($reservations as $reservation) {
            $courses[] = [
                'id' => $reservation['course_id'],
                'city' => $reservation['city'],
                'start_date' => $reservation['start_date'],
                'completed' => $reservation['start_date'] < $today ? true : false
            ];

        }

        return new JsonResponse([
            'success' => true,
            'error' => null,
            'courses' => $courses
        ]);
    }

    /**
     * @Route("/payment-method/{id}", methods={"POST"})
     */
    public function addPaymentMethod(string $id, Request $request)
    {
        $customer = $this->customerRepository->getCustomer($id);
        
        if (!$customer) {
            return new JsonResponse([
                'success' => false,
                'error' => 'User not found!'
            ]);
        }

        $customer = Customer::fromDatabase($customer);

        $result = $this->customerRepository->addPaymentInfo($customer, $request->get('token'));

        if($result == false) {
            return new JsonResponse([
                'success' => false,
                'error' => "Error occured!"
            ]);
        }

        return new JsonResponse([
            'success' => true,
            'methods' => $this->customerRepository->getPaymentInfo($customer),
            'error' => null
        ]);
    }

    /**
     * @Route("/payment-method/{id}", methods={"GET"})
     */
    public function getPaymentMethod(string $id)
    {
        $customer = $this->customerRepository->getCustomer($id);
        
        if (!$customer) {
            return new JsonResponse([
                'success' => false,
                'error' => 'User not found!'
            ]);
        }

        $customer = Customer::fromDatabase($customer);

        $result = $this->customerRepository->getPaymentInfo($customer);

        if($result == null) {
            return new JsonResponse([
                'success' => false,
                'error' => "Error occured!"
            ]);
        }

        return new JsonResponse([
            'success' => true,
            'methods' => $result,
            'error' => null
        ]);
    }
}

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
use App\Repository\PaymentRepository;
use App\Repository\CourseRepository;
use App\Entity\Course;
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
     * @var PaymentRepository
     */
    protected $paymentRepository;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var CourseRepository
     */
    protected $courseRepository;

    public function __construct(
        Database $db,
        CsvExporter $csv,
        CustomerRepository $customerRepository,
        PaymentRepository $paymentRepository,
        CourseRepository $courseRepository,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->db = $db;
        $this->csv = $csv;
        $this->customerRepository = $customerRepository;
        $this->paymentRepository = $paymentRepository;
        $this->courseRepository = $courseRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @Route("/search", methods={"POST"})
     */
    public function search(Request $request)
    {
        $state = State::fromDatagrid($request->request->all());
        $customers = $this->db->findAll(
            'select * from customer ' . $state->toQuery(),
            $state->toQueryParams()
        );

        $items = [];
        foreach ($customers as $customer) {
            $items[] = (Customer::fromDatabase($customer))->jsonSerialize();
        }

        return new JsonResponse([
            'items' => $items,
            'total' => (int) $this->db->execute(
                'select * from customer ' . $state->toQuery(false, false),
                $state->toQueryParams()
            ),
            'alive' => (int) $this->db->execute(
                'select * from customer ' . $state->toQuery(true, false),
                $state->toQueryParams()
            )
        ]);
    }

    /**
     * @Route("/instructors/search", methods={"POST"})
     */
    public function searchInstructors(Request $request)
    {
        $customers = $this->db->findAll("select * from customer where type='instructor' and deleted_at is null");

        $items = [];
        foreach ($customers as $customer) {
            $items[] = (Customer::fromDatabase($customer))->jsonSerialize();
        }

        return new JsonResponse([
            'items' => $items
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
            $uploadedFile->move($tempDirectory, $id . ".jpg");

            $customer = $this->customerRepository->getCustomer($id);

            if (!$customer) {
                throw new NotFoundHttpException();
            }

            $customer = Customer::fromDatabase($customer);

            $sharedConfig = [
                'region' => 'us-east-2',
                'version' => 'latest',
                'credentials' => [
                    'key' => $this->getParameter('env(S3_KEY)'),
                    'secret' => $this->getParameter('env(S3_SECRET)')
                ]
            ];
            $sdk = new Sdk($sharedConfig);

            $s3 = new AwsS3Util($sdk);
            $avatarUrl = $s3->putObject('ootdev', $tempDirectory . $id . ".jpg", "avatars/" . Uuid::uuid4() . '.jpg');

            $customer->setAvatar($avatarUrl);

            $filesystem->remove([$tempDirectory . $id . '.jpg']);

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

        if (!$this->customerRepository->checkPassword($customer, $data['oldPassword'])) {
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
        $myCourses = [];

        if ($customer->getType() == "instructor") {
            $myCourses = $this->customerRepository->findAssignedCourses($customer);
        } else {
            $myCourses = $this->customerRepository->findReservations($customer);
        }

        $courses = [];

        $today = date("Y-m-d");

        foreach ($myCourses as $myCourse) {
            $courses[] = [
                'id' => $myCourse['course_id'],
                'slug' => $myCourse['slug'],
                'title' => $myCourse['title'],
                'city' => $myCourse['city'],
                'start_date' => $myCourse['start_date'],
                'last_date' => $myCourse['last_date'],
                'completed' => $myCourse['status'] == Course::COMPLETED
            ];
        }

        return new JsonResponse([
            'success' => true,
            'error' => null,
            'courses' => $courses
        ]);
    }

    /**
     * @Route("/client-secret", methods={"POST"})
     */
    public function getClientSecret(Request $request)
    {
        $userId = $request->get('userId');
        $customer = $this->customerRepository->getCustomer($userId);

        if (!$customer) {
            return new JsonResponse([
                'success' => false,
                'error' => 'User not found!'
            ]);
        }

        $customer = Customer::fromDatabase($customer);

        $skey = $this->getParameter('env(STRIPE_SKEY)');
        $result = $this->paymentRepository->getClientSecret(
            $customer,
            $skey,
            $request->get('billing'),
            $request->get('attendee')
        );

        if ($result == "") {
            return new JsonResponse([
                'success' => false,
                'error' => "Error occured!"
            ]);
        }

        return new JsonResponse([
            'success' => true,
            'secret' => $result,
            'error' => null
        ]);
    }

    /**
     * @Route("/payment-method", methods={"POST"})
     */
    public function addPaymentMethod(Request $request)
    {
        $userId = $request->get('userId');
        $customer = $this->customerRepository->getCustomer($userId);

        if (!$customer) {
            return new JsonResponse([
                'success' => false,
                'error' => 'User not found!'
            ]);
        }

        $customer = Customer::fromDatabase($customer);

        $skey = $this->getParameter('env(STRIPE_SKEY)');
        $result = $this->paymentRepository->addPaymentInfo(
            $customer,
            $request->get('clientSecret'),
            $request->get('pmToken'),
            $skey
        );

        if ($result == false) {
            return new JsonResponse([
                'success' => false,
                'error' => "Error occured!"
            ]);
        }

        return new JsonResponse([
            'success' => true,
            'methods' => $this->paymentRepository->getPaymentInfo($customer, $skey),
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

        $skey = $this->getParameter('env(STRIPE_SKEY)');
        $result = $this->paymentRepository->getPaymentInfo($customer, $skey);

        if ($result == null) {
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

    /**
     * @Route("/payment-method/{userId}/{methodId}", methods={"DELETE"})
     */
    public function removePaymentMethod(string $userId, string $methodId)
    {
        $customer = $this->customerRepository->getCustomer($userId);

        if (!$customer) {
            return new JsonResponse([
                'success' => false,
                'error' => 'User not found!'
            ]);
        }

        $customer = Customer::fromDatabase($customer);

        $skey = $this->getParameter('env(STRIPE_SKEY)');
        $result = $this->paymentRepository->removePaymentInfo($customer, $methodId, $skey);

        if ($result == null) {
            return new JsonResponse([
                'success' => false,
                'error' => "Error occured!"
            ]);
        }

        return new JsonResponse([
            'success' => true,
            'error' => null
        ]);
    }

    /**
     * @Route("/order", methods={"POST"})
     */
    public function placeOrder(Request $request)
    {
        $userId = $request->get('userId');
        $customer = $this->customerRepository->getCustomer($userId);

        if (!$customer) {
            return new JsonResponse([
                'success' => false,
                'error' => 'User not found!'
            ]);
        }

        $customer = Customer::fromDatabase($customer);

        $skey = $this->getParameter('env(STRIPE_SKEY)');
        $cart = $request->get('cart');
        $courses = [];

        foreach ($cart as $item) {
            $course = $this->courseRepository->getCourseWithPrice($item['id']);
            $course['quantity'] = $item['quantity'];

            $courses[] = $course;
        }

        $result = $this->paymentRepository->placeOrder($customer, $courses, $request->get('paymentMethodId'), $skey);

        if ($result == null) {
            return new JsonResponse([
                'success' => false,
                'paymentIntent' => null,
                'error' => "Unexpected error occured!"
            ]);
        } else if (!$result['success']) {
            return new JsonResponse([
                'success' => false,
                'paymentIntent' => $result['paymentIntent'],
                'error' => "Error occured!"
            ]);
        }

        $coursePayment = $this->customerRepository->savePayment($customer, $result['paymentIntent']['id'], $request->get('paymentMethodId'));
        $this->customerRepository->reserveCourse($customer, $courses, $coursePayment->getId());

        return new JsonResponse([
            'success' => true,
            'error' => null,
            'result' => $result
        ]);
    }

    /**
     * @Route("/order/save", methods={"POST"})
     */
    public function saveOrder(Request $request)
    {

        $userId = $request->get('userId');
        $customer = $this->customerRepository->getCustomer($userId);

        if (!$customer) {
            return new JsonResponse([
                'success' => false,
                'error' => 'User not found!'
            ]);
        }

        $customer = Customer::fromDatabase($customer);

        $cart = $request->get('cart');
        $courses = [];

        foreach ($cart as $item) {
            $course = $this->courseRepository->getCourseWithPrice($item['id']);
            $course['quantity'] = $item['quantity'];

            $courses[] = $course;
        }

        $coursePayment = $this->customerRepository->savePayment($customer, $request->get('paymentIntentId'), $request->get('paymentMethodId'));
        $this->customerRepository->reserveCourse($customer, $courses, $coursePayment->getId());

        return new JsonResponse([
            'success' => true,
            'error' => null
        ]);
    }

    /**
     * @Route("/billings/{userId}", methods={"GET"})
     */
    public function getBillings(string $userId)
    {
        $customer = $this->customerRepository->getCustomer($userId);

        if (!$customer) {
            return new JsonResponse([
                'success' => false,
                'error' => 'User not found!'
            ]);
        }

        $customer = Customer::fromDatabase($customer);

        $payments = $this->customerRepository->getPayments($customer);

        $skey = $this->getParameter('env(STRIPE_SKEY)');
        $billings = $this->paymentRepository->getBillings($payments, $skey);

        return new JsonResponse([
            'success' => true,
            'error' => null,
            'billings' => $billings
        ]);
    }

    /**
     * @Route("/billing/{billingNumber}", methods={"GET"})
     */
    public function getBilling(string $billingNumber)
    {
        $skey = $this->getParameter('env(STRIPE_SKEY)');
        $billing = $this->paymentRepository->getBilling($billingNumber, $skey);

        return new JsonResponse([
            'success' => true,
            'error' => null,
            'billing' => $billing
        ]);
    }
}

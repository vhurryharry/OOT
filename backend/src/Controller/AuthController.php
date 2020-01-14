<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\CustomerRepository;
use App\Security\User;
use App\Event\CustomerRegistered;
use App\Security\Customer;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class AuthController extends AbstractController
{
    /**
     * @var UserRepository
     */
	protected $userRepository;
	
    /**
     * @var CustomerRepository
     */
    protected $customerRepository;

    /**
     * @var UserPasswordEncoderInterface
     */
    protected $encoder;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    public function __construct(UserRepository $userRepository, CustomerRepository $customerRepository, UserPasswordEncoderInterface $encoder, EventDispatcherInterface $eventDispatcher)
    {
        $this->userRepository = $userRepository;
		$this->encoder = $encoder;
		$this->customerRepository = $customerRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @Route("/login", methods={"POST"})
     */
    public function login(Request $request)
    {
		$email = $request->get('email');
		$password = $request->get('password');

		$user = User::fromDatabase($this->userRepository->findByEmail($email));

		if(!$user) {
			return new JsonResponse([
				'success' => false,
				'error' => "User does not exist for that email!",
				'user' => null
			]);;
		}

        if(!$this->encoder->isPasswordValid($user, $password)) {
			return new JsonResponse([
				'success' => false,
				'error' => "Invalid password!",
				'user' => null
			]);
		}
		
        return new JsonResponse([
			'success' => true,
			'error' => null,
			'user' => [
				'id' => $user->getId(),
  				'name' => $user->getName(),
				'email' => $user->getEmail(),
				'firstName' => $user->getFirstName(),
				'lastName' => $user->getLastName(),
				'permissions' => $user->getPermissions(),
				'status' => $user->getStatus(),
				'createdAt' => $user->getCreatedAt(),
				'updatedAt' => $user->getUpdatedAt()  
			]
		]);
    }

	/**
     * @Route("/customer-login", methods={"POST"})
     */
    public function customerLogin(Request $request)
    {
		$email = $request->get('email');
		$password = $request->get('password');

		$customer = $this->customerRepository->findByLogin($email);

		if(!$customer) {
			return new JsonResponse([
				'success' => false,
				'error' => "User does not exist for that email!",
				'user' => null
			]);;
		}

		$customer = Customer::fromDatabase($customer);

        if(!$this->encoder->isPasswordValid($customer, $password)) {
			return new JsonResponse([
				'success' => false,
				'error' => "Invalid password!",
				'user' => null
			]);
		}
		
        return new JsonResponse([
			'success' => true,
			'error' => null,
			'user' => [
				'id' => $customer->getId(),
				'metadata' => $customer->getMetadata(),
				'type' => $customer->getType(),
				'status' => $customer->getStatus(),
				'acceptsMarketing' => $customer->acceptsMarketing(),
				'email' => $customer->getLogin(),
				'firstName' => $customer->getFirstName(),
				'lastName' => $customer->getLastName(),
				'tagline' => $customer->getTagline(),
				'occupation' => $customer->getOccupation(),
				'birthDate' => $customer->getBirthDate(),
				'mfa' => $customer->getMfa(),
			]
		]);
	}

	/**
     * @Route("/customer-register", methods={"POST"})
     */
    public function customerRegister(Request $request)
    {
		$data = $request->get("user");

		$customer = $this->customerRepository->findByLogin($data['email']);

		if($customer) {
			return new JsonResponse([
				'success' => false,
				'error' => "User already exists for this email!",
				'user' => null
			]);;
		}

		$customer = $this->customerRepository->register($data);
		
		$this->eventDispatcher->dispatch(new CustomerRegistered($customer));
		
		return new JsonResponse([
			'success' => true,
			'error' => null,
			'user' => [
				'id' => $customer->getId(),
				'metadata' => $customer->getMetadata(),
				'type' => $customer->getType(),
				'status' => $customer->getStatus(),
				'acceptsMarketing' => $customer->acceptsMarketing(),
				'email' => $customer->getLogin(),
				'firstName' => $customer->getFirstName(),
				'lastName' => $customer->getLastName(),
				'tagline' => $customer->getTagline(),
				'occupation' => $customer->getOccupation(),
				'birthDate' => $customer->getBirthDate(),
				'mfa' => $customer->getMfa(),
			]
		]);
	}	
	
}

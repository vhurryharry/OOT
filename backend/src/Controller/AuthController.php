<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\CustomerRepository;
use App\Security\User;
use App\Security\Customer;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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

    public function __construct(UserRepository $userRepository, CustomerRepository $customerRepository, UserPasswordEncoderInterface $encoder)
    {
        $this->userRepository = $userRepository;
		$this->encoder = $encoder;
		$this->customerRepository = $customerRepository;
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

		$customer = Customer::fromDatabase($this->customerRepository->findByLogin($email));

		if(!$customer) {
			return new JsonResponse([
				'success' => false,
				'error' => "User does not exist for that email!",
				'user' => null
			]);;
		}

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
				'confirmationToken' => $customer->getConfirmationToken(),
				'acceptsMarketing' => $customer->acceptsMarketing(),
				'createdAt' => $customer->getCreatedAt(),
				'registeredAt' => $customer->getRegisteredAt(),
				'updatedAt' => $customer->getUpdatedAt(),
				'deletedAt' => $customer->getDeletedAt(),
				'expiresAt' => $customer->getExpiresAt(),
				'email' => $customer->getLogin(),
				'passwordExpiresAt' => $customer->getPasswordExpiresAt(),
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

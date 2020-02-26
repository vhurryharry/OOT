<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\CustomerRepository;
use App\Security\User;
use App\Event\CustomerRegistered;
use App\Event\CustomerResetPasswordRequested;
use App\Event\CustomerConfirmationPended;
use App\Security\Customer;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\SignatureInvalidException;

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
        
        if($customer->getStatus() == Customer::PENDING_CONFIRMATION) {
            return new JsonResponse([
				'success' => false,
                'error' => "Your account hasn't been confirmed yet!",
				'user' => null
			]);
        }
		
        return new JsonResponse([
			'success' => true,
			'error' => null,
			'user' => [
				'id' => $customer->getId(),
				'type' => $customer->getType(),
				'login' => $customer->getLogin(),
				'firstName' => $customer->getFirstName(),
				'lastName' => $customer->getLastName(),
				'occupation' => $customer->getOccupation(),
				'birthDate' => $customer->getBirthDate(),
				'bio' => $customer->getBio(),
				'website' => $customer->getWebsite(),
				'instagram' => $customer->getInstagram(),
				'twitter' => $customer->getTwitter(),
				'facebook' => $customer->getFacebook(),
				'avatar' => $customer->getAvatar(),
			]
		]);
	}
    
    private $customerConfirmationSigningKey = "olive_oil_school_customer_confirmation_signing_key";

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
				'error' => "User already exists for that email!"
			]);
		}

		$customer = $this->customerRepository->register($data);

        $this->sendConfirmation($request, $customer);

		return new JsonResponse([
			'success' => true,
			'error' => null
		]);
    }

    /**
     * @Route("/resend-confirmation", methods={"POST"})
     */
    public function resendConfirmation(Request $request)
    {
		$email = $request->get("email");

		$customer = $this->customerRepository->findByLogin($email);

		if(!$customer) {
			return new JsonResponse([
				'success' => false,
				'error' => "User doesn't exist for that email!"
			]);
        }

		$customer = Customer::fromDatabase($customer);
        $this->sendConfirmation($request, $customer);

		return new JsonResponse([
			'success' => true,
			'error' => null
		]);
    }

    private function sendConfirmation(Request $request, Customer $customer)
    {
        // Send confirmation email
        $payload = array(
            "email" => $customer->getLogin(),
            "type" => "customer",
            "msgType" => 'customer.confirmationPended',
            "iat" => time()
        );

        $jwt = JWT::encode($payload, $this->customerConfirmationSigningKey);
        $confirmationUri = $request->getSchemeAndHttpHost()."/email-confirmation?token=".$jwt;

        $this->eventDispatcher->dispatch(new CustomerConfirmationPended($customer, $confirmationUri));
    }

	/**
     * @Route("/customer-confirmation-validate-token", methods={"POST"})
     */
    public function customerConfirmationValidateToken(Request $request)
    {
        $token = $request->get("token");
        $decoded = "";

        try {
            $decoded = JWT::decode($token, $this->customerConfirmationSigningKey, array('HS256'));

            if($decoded->type == "customer" && $decoded->msgType == "customer.confirmationPended") {
                $email = $decoded->email;

                $customer = $this->customerRepository->findByLogin($email);
                if(!$customer) {
                    return new JsonResponse([
                        'success' => false,
                        'error' => "Invalid token provided!"
                    ]);
                }
                $customer = Customer::fromDatabase($customer);
                $this->customerRepository->confirmUser($customer);
		
                $this->eventDispatcher->dispatch(new CustomerRegistered($customer));

                return new JsonResponse([
                    'success' => true,
                    'user' => [
                        'id' => $customer->getId(),
                        'type' => $customer->getType(),
                        'login' => $customer->getLogin(),
                        'firstName' => $customer->getFirstName(),
                        'lastName' => $customer->getLastName(),
                        'occupation' => $customer->getOccupation(),
                        'birthDate' => $customer->getBirthDate(),
                        'bio' => $customer->getBio(),
                        'website' => $customer->getWebsite(),
                        'instagram' => $customer->getInstagram(),
                        'twitter' => $customer->getTwitter(),
                        'facebook' => $customer->getFacebook(),
                        'avatar' => $customer->getAvatar(),
                    ],
                    'error' => null
                ]);
            }
        }
        catch(ExpiredException $ex) {
            return new JsonResponse([
                'success' => false,
                'error' => "This token has been expired!"
            ]);
        }
        catch(SignatureInvalidException $ex) {
            return new JsonResponse([
                'success' => false,
                'error' => "Invalid token provided!"
            ]);
        }
        catch(\Exception $ex) {
            return new JsonResponse([
                'success' => false,
                'error' => "Unexpected error occured while validating the token!"
            ]);
        }

		return new JsonResponse([
            'success' => true,
            'email' => '',
			'error' => null
		]);
	}
    
    private $resetPwdSigningKey = "olive_oil_school_reset_password_key";

	/**
     * @Route("/customer-reset-password-requested", methods={"POST"})
     */
    public function customerResetPasswordRequested(Request $request)
    {
		$email = $request->get("email");

        $customer = $this->customerRepository->findByLogin($email);

		if($customer) {
            $customer = Customer::fromDatabase($customer);

            $payload = array(
                "email" => $email,
                "type" => "customer",
                "msgType" => 'customer.resetPasswordRequested',
                "iat" => time()
            );

            JWT::$leeway = 600;
            $jwt = JWT::encode($payload, $this->resetPwdSigningKey);
            $resetUri = $request->getSchemeAndHttpHost()."/reset-pwd?token=".$jwt;
            
            //$decoded = JWT::decode($jwt, $key, array('HS256'));

            $this->eventDispatcher->dispatch(new CustomerResetPasswordRequested($customer, $resetUri));
        } else {
            return new JsonResponse([
                'success' => false,
                'error' => "User does not exist for that email!"
            ]);
        }
		
		return new JsonResponse([
			'success' => true,
			'error' => null
		]);
    }    

	/**
     * @Route("/customer-reset-password", methods={"POST"})
     */
    public function customerResetPassword(Request $request)
    {
        $email = $request->get("email");
        $password = $request->get("password");

        $customer = $this->customerRepository->findByLogin($email);

		if($customer) {
            $customer = Customer::fromDatabase($customer);
            $customer = $this->customerRepository->resetPassword($customer, $password);
        } else {
            return new JsonResponse([
                'success' => false,
                'error' => "User does not exist for that email!"
            ]);
        }
		
		return new JsonResponse([
			'success' => true,
			'error' => null
		]);
    }
	
	/**
     * @Route("/customer-reset-password-validate-token", methods={"POST"})
     */
    public function customerResetPasswordValidateToken(Request $request)
    {
        $token = $request->get("token");

        try {
            $decoded = JWT::decode($token, $this->resetPwdSigningKey, array('HS256'));

            if($decoded->type == "customer" && $decoded->msgType == "customer.resetPasswordRequested") {
                $email = $decoded->email;

                $customer = $this->customerRepository->findByLogin($email);
                if(!$customer) {
                    return new JsonResponse([
                        'success' => false,
                        'error' => "Invalid token provided!"
                    ]);
                }
                $customer = Customer::fromDatabase($customer);

                return new JsonResponse([
                    'success' => true,
                    'email' => $customer->getEmail(),
                    'error' => null
                ]);
            }
        }
        catch(ExpiredException $ex) {
            return new JsonResponse([
                'success' => false,
                'error' => "This token has been expired!"
            ]);
        }
        catch(SignatureInvalidException $ex) {
            return new JsonResponse([
                'success' => false,
                'error' => "Invalid token provided!"
            ]);
        }
        catch(\Exception $ex) {
            return new JsonResponse([
                'success' => false,
                'error' => "Unexpected error occured while validating the token!"
            ]);
        }

		return new JsonResponse([
            'success' => true,
            'email' => '',
			'error' => null
		]);
	}
}

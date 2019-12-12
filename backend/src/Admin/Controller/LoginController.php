<?php

declare(strict_types=1);

namespace App\Admin\Controller;

use App\Repository\UserRepository;
use App\Admin\Security\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LoginController extends AbstractController
{
    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * @var UserPasswordEncoderInterface
     */
    protected $encoder;

    public function __construct(UserRepository $userRepository, UserPasswordEncoderInterface $encoder)
    {
        $this->userRepository = $userRepository;
        $this->encoder = $encoder;
    }

    /**
     * @Route("/", methods={"POST"})
     */
    public function login(Request $request)
    {
		$email = $request->get('email');
		$password = $request->get('password');

		$user = $this->userRepository->findByEmail($email);
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
				'email' => $user->getEmil(),
				'firstName' => $user->getFirstName(),
				'lastName' => $user->getLastName(),
				'permissions' => $user->getPermissions(),
				'status' => $user->getStatus(),
				'createdAt' => $user->getCreatedAt(),
				'updatedAt' => $user->getUpdatedAt()  
			]
		]);
    }
}

<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\LoginType;
use App\Form\RegisterType;
use App\Repository\CustomerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class CustomerController extends AbstractController
{
    /**
     * @var CustomerRepository
     */
    protected $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request)
    {
        $form = $this->createForm(RegisterType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->customerRepository->register($form->getData());
            $this->addFlash('info', 'You will receive a confirmation email shortly.');

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
}

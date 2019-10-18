<?php

declare(strict_types=1);

namespace App\Controller;

use App\Database;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @Route("/order", name="order")
     */
    public function order(Request $request)
    {
        return $this->render('order.html.twig');
    }
}

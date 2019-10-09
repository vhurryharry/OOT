<?php

declare(strict_types=1);

namespace App\Controller;

use App\Database;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
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
     * @Route("/", name="homepage")
     */
    public function homepage()
    {
        return $this->render('homepage.html.twig');
    }

    /**
     * @Route("/{slug}", name="page", requirements={"slug"="[a-zA-Z0-9\-]+"})
     */
    public function page(string $slug)
    {
        $page = $this->db->find("select * from entity where slug = ? and type = 'page' and deleted_at is null", [$slug]);

        if (!$page) {
            throw new NotFoundHttpException();
        }

        $template = $this->container->get('twig')->createTemplate($page['content']);

        return $this->render(sprintf('page/%s.html.twig', $page['type']), [
            'page' => $page,
            'content' => $template->render($page),
        ]);
    }
}

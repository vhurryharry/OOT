<?php

declare(strict_types=1);

namespace App\Template;

use App\Database;
use Symfony\Component\Routing\RouterInterface;
use Throwable;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MenuExtension extends AbstractExtension
{
    /**
     * @var Database
     */
    protected $db;

    /**
     * @var RouterInterface
     */
    protected $router;

    public function __construct(Database $db, RouterInterface $router)
    {
        $this->db = $db;
        $this->router = $router;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('getMenu', [$this, 'getMenu']),
            new TwigFunction('getMenuLink', [$this, 'getMenuLink']),
        ];
    }

    public function getMenu(): array
    {
        return $this->db->findAll('select title, link from menu where deleted_at is null order by display_order asc');
    }

    public function getMenuLink(array $item): string
    {
        $link = $item['link'] ?? '';

        if (empty($link)) {
            return '';
        }

        $link = '';
        try {
            $link = $this->router->generate($item['link']);
        } catch (Throwable $e) {
        }

        return $link;
    }
}

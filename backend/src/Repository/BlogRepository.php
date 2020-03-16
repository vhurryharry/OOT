<?php

declare(strict_types=1);

namespace App\Repository;

use App\Database;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BlogRepository
{
    /**
     * @var Database
     */
    protected $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function findAll(): array
    {
        $blogs = $this->db->findAll(
            'select bc.category, b.title, b.subtitle, b.slug, b.author, b.cover_image from blog_category as bc join blog as b on b.category = bc.id where b.deleted_at is null'
        );

        if (!$blogs) {
            throw new NotFoundHttpException();
        }

        return $blogs;
    }

    public function getCategories(): array 
    {
        $availableCategories = $this->db->findAll(
            'select * from blog_category where deleted_at is null'
        );

        return $availableCategories;
    }

    public function findBySlug(string $slug): array
    {
        $blog = $this->db->find(
            'select bc.category, b.title, b.subtitle, b.author, b.cover_image, b.content, b.created_at from blog_category as bc join blog as b on b.category = bc.id where b.deleted_at is null and b.slug = ?',
            [$slug]
        );

        if (!$blog) {
            throw new NotFoundHttpException();
        }

        return $blog;
    }
}

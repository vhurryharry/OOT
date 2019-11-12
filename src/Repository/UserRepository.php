<?php

declare(strict_types=1);

namespace App\Repository;

use App\Database;

class UserRepository
{
    /**
     * @var Database
     */
    protected $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function findByEmail(string $email)
    {
        return $this->db->find(
            'select * from "user" where email = ? and deleted_at is null',
            [$email]
        );
    }
}

<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;
use Symfony\Component\Security\Core\Encoder\SodiumPasswordEncoder;

class Security extends AbstractSeed
{
    public function run(): void
    {
        $this->setupAcl();
    }

    protected function setupAcl(): void
    {
        $this
            ->table('user')
            ->insert([
                [
                    'name' => 'admin',
                    'email' => 'admin@oliveoilschool.org',
                    'password' => (new SodiumPasswordEncoder())->encodePassword('123456', 'foo'),
                    'status' => 'active',
                    'permissions' => json_encode(['ROLE_ADMIN']),
                ],
            ])
            ->save();
    }
}

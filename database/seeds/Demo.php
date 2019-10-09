<?php

declare(strict_types=1);

use Faker\Factory;
use Phinx\Seed\AbstractSeed;
use Ramsey\Uuid\Uuid;

class Demo extends AbstractSeed
{
    public function run(): void
    {
        $faker = Factory::create();

        // Demo customers
        foreach (range(1, 500) as $index) {
            $customers[] = [
                'id' => Uuid::uuid4(),
                'login' => $faker->email,
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'password' => password_hash('123456', PASSWORD_DEFAULT),
                'type' => 'student',
                'status' => $faker->randomElement(['active', 'pending_confirmation', 'locked']),
                'accepts_marketing' => $faker->boolean,
            ];
        }

        // Demo instructors
        foreach (range(1, 20) as $index) {
            $customers[] = [
                'id' => Uuid::uuid4(),
                'login' => $faker->email,
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'password' => password_hash('123456', PASSWORD_DEFAULT),
                'type' => 'instructor',
                'status' => 'active',
                'accepts_marketing' => $faker->boolean,
            ];
        }

        $this
            ->table('customer')
            ->insert($customers)
            ->save();
    }
}

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
        $hash = password_hash('123456', PASSWORD_DEFAULT);

        // Students
        foreach (range(1, 500) as $index) {
            $customers[] = [
                'id' => Uuid::uuid4()->toString(),
                'login' => $faker->email,
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'password' => $hash,
                'type' => 'student',
                'status' => $faker->randomElement(['active', 'pending_confirmation', 'locked']),
                'accepts_marketing' => $faker->boolean,
                'tagline' => $faker->bs,
                'occupation' => $faker->jobTitle,
            ];
        }

        // Instructors
        foreach (range(1, 20) as $index) {
            $instructors[] = [
                'id' => Uuid::uuid4()->toString(),
                'login' => $faker->email,
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'password' => $hash,
                'type' => 'instructor',
                'status' => 'active',
                'accepts_marketing' => $faker->boolean,
                'tagline' => $faker->bs,
                'occupation' => 'Instructor',
            ];
        }

        $this
            ->table('customer')
            ->insert(array_merge($customers, $instructors))
            ->save();

        // Courses
        foreach (range(1, 5) as $index) {
            $id = Uuid::uuid4()->toString();
            $combo = [];
            $date = $faker->dateTimeThisMonth();
            $categories = "{";

            $catCount = rand(1,5);
            for($i = 0; $i < $catCount; $i ++) {
                $categories .= rand(1,5);
                if($i + 1 < $catCount) {
                    $categories .= ", ";
                }
            }
            $categories .= "}";

            $courses[] = [
                'id' => $id,
                'slug' => $faker->slug,
                'title' => $faker->catchPhrase,
                'meta_title' => $faker->catchPhrase,
                'meta_description' => $faker->sentence(),
                'content' => $faker->paragraphs(1, true),
                'program' => $faker->paragraphs(3, true),
                'display_order' => $faker->numberBetween(1, 10),
                'spots' => 32,
                'location' => sprintf('(%s,%s)', $faker->latitude, $faker->longitude),
                'start_date' => $date->format('Y-m-d'),
                'city' => $faker->city,
                'address' => $faker->address,
                'hero' => $faker->bs,
                'tagline' => $faker->catchPhrase,
                'categories' => $categories
            ];

            // Options
            foreach (range(1, 3) as $index) {
                $firstDate = $date->format('Y-m-d H:i:s');
                $date->modify('+1 day');
                $secondDate = $date->format('Y-m-d H:i:s');

                if ($index == 3) {
                    $courseOptions[] = [
                        'title' => 'Level 1 & 2',
                        'price' => $faker->numberBetween(80000, 500000),
                        'dates' => json_encode($combo),
                        'course' => $id,
                        'combo' => true,
                    ];
                } else {
                    $combo[] = $firstDate;
                    $combo[] = $secondDate;
                    $courseOptions[] = [
                        'title' => 'Level ' . $index,
                        'price' => $faker->numberBetween(80000, 500000),
                        'dates' => json_encode([$firstDate, $secondDate]),
                        'course' => $id,
                        'combo' => false,
                    ];
                }
            }

            // Reviews
            foreach (range(1, $faker->numberBetween(5, 20)) as $index) {
                $courseReviews[] = [
                    'title' => $faker->sentence(),
                    'content' => $faker->paragraphs(1, true),
                    'rating' => $faker->numberBetween(1, 5),
                    'course' => $id,
                    'author' => $faker->randomElement($customers)['id'],
                ];
            }

            // Testimonial
            foreach (range(1, $faker->numberBetween(2, 8)) as $index) {
                $courseTestimonials[] = [
                    'title' => $faker->sentence(),
                    'content' => $faker->paragraphs(1, true),
                    'course' => $id,
                    'author' => $faker->randomElement($customers)['id'],
                ];
            }

            // Instructors
            foreach (range(1, $faker->numberBetween(1, 3)) as $index) {
                $courseInstructor[] = [
                    'course_id' => $id,
                    'customer_id' => $faker->randomElement($instructors)['id'],
                ];
            }

            // Reservation
            foreach ($faker->randomElements($customers, rand(1, 32)) as $customer) {
                $courseReservation[] = [
                    'number' => Uuid::uuid4()->toString(),
                    'course_id' => $id,
                    'customer_id' => $customer['id'],
                    'status' => $faker->randomElement(['pending_confirmation', 'paid', 'finished']),
                    'option_title' => $faker->sentence(),
                    'option_price' => $faker->numberBetween(80000, 500000),
                ];
            }
        }

        // Course Categories
        $categories = [
            [
                'category' => 'Quality Assessment'
            ],
            [
                'category' => 'Farming and Production'
            ],
            [
                'category' => 'Culinary'
            ],
            [
                'category' => 'Marketing'
            ],
            [
                'category' => 'Health'
            ]
        ];

        foreach (range(1, 20) as $index) {
            $topics[] = [
                'topic' => $faker->sentence() . '?',
                'description' => $faker->paragraphs(1, true)
            ];
        }

        foreach (range(1, 20) as $index) {
            $faq[] = [
                'title' => $faker->sentence() . '?',
                'content' => $faker->paragraphs(1, true),
                'course' => $faker->randomElement($courses)['id'],
            ];
        }

        $this
            ->table('course')
            ->insert($courses)
            ->save();
        $this
            ->table('course_option')
            ->insert($courseOptions)
            ->save();
        $this
            ->table('course_review')
            ->insert($courseReviews)
            ->save();
        $this
            ->table('course_testimonial')
            ->insert($courseTestimonials)
            ->save();
        $this
            ->table('course_instructor')
            ->insert($courseInstructor)
            ->save();
        $this
            ->table('course_reservation')
            ->insert($courseReservation)
            ->save();
        $this
            ->table('faq')
            ->insert($faq)
            ->save();

        $this
            ->table('course_category')
            ->insert($categories)
            ->save();
        $this
            ->table('course_topic')
            ->insert($topics)
            ->save();
    }
}

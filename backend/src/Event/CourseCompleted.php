<?php

declare(strict_types=1);

namespace App\Event;

use App\Entity\Course;
use App\Security\Customer;
use Symfony\Contracts\EventDispatcher\Event;

class CourseCompleted extends Event
{
    /**
     * @var Customer
     */
    protected $customer;

    /**
     * @var Course
     */
    protected $course;


    public function __construct(Customer $customer, Course $course)
    {
        $this->customer = $customer;
        $this->course = $course;
    }

    public function getCourse()
    {
        return $this->course;
    }

    public function getCustomer()
    {
        return $this->customer;
    }

    public function getName()
    {
        return 'course.completed';
    }
}

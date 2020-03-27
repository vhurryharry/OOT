<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\State;
use App\CsvExporter;
use App\Database;
use App\Repository\CourseRepository;
use App\Repository\CustomerRepository;
use App\Repository\SurveyRepository;
use App\Entity\Course;
use App\Security\Customer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Ramsey\Uuid\Uuid;

class CronController extends AbstractController
{
    /**
     * @var Database
     */
    protected $db;

    /**
     * @var CsvExporter
     */
    protected $csv;

    /**
     * @var CustomerRepository
     */
    protected $customerRepository;

    /**
     * @var CourseRepository
     */
    protected $courseRepository;

    /**
     * @var SurveyRepository
     */
    protected $surveyRepository;

    public function __construct(
        Database $db,
        CsvExporter $csv,
        CourseRepository $courseRepository,
        CustomerRepository $customerRepository,
        SurveyRepository $surveyRepository
    ) {
        $this->db = $db;
        $this->csv = $csv;
        $this->courseRepository = $courseRepository;
        $this->customerRepository = $customerRepository;
        $this->surveyRepository = $surveyRepository;
    }

    /**
     * @Route("/update-course-status", methods={"POST"})
     */
    public function updateCourseStatus()
    {
        $this->courseRepository->updateCourseStatus();

        return new JsonResponse();
    }
}

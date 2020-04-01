<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\State;
use App\CsvExporter;
use App\Database;
use App\Security\Customer;
use App\Event\CustomerRegistered;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\LoginType;
use App\Form\RegisterType;
use App\Repository\CustomerRepository;
use App\Repository\PaymentRepository;
use App\Repository\CourseRepository;
use App\Entity\Course;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Filesystem\Filesystem;
use Aws\Sdk;
use App\Utils\Aws\AwsS3Util;
use Ramsey\Uuid\Uuid;

class UploadController extends AbstractController
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
     * @var PaymentRepository
     */
    protected $paymentRepository;

    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var CourseRepository
     */
    protected $courseRepository;

    public function __construct(
        Database $db,
        CsvExporter $csv,
        CustomerRepository $customerRepository,
        PaymentRepository $paymentRepository,
        CourseRepository $courseRepository,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->db = $db;
        $this->csv = $csv;
        $this->customerRepository = $customerRepository;
        $this->paymentRepository = $paymentRepository;
        $this->courseRepository = $courseRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @Route("/file", methods={"POST"})
     */
    public function updateUserAvatar(Request $request)
    {
        if ($request->files->get('upload')) {
            $tempDirectory = "temp/";

            $filesystem = new Filesystem();
            $filesystem->mkdir($tempDirectory);

            $uploadedFile = $request->files->get('upload');
            $uploadedFile->move($tempDirectory, "upload.jpg");

            $sharedConfig = [
                'region' => 'us-east-2',
                'version' => 'latest',
                'credentials' => [
                    'key' => $this->getParameter('env(S3_KEY)'),
                    'secret' => $this->getParameter('env(S3_SECRET)')
                ]
            ];
            $sdk = new Sdk($sharedConfig);

            $s3 = new AwsS3Util($sdk);
            $uploadedUrl = $s3->putObject('ootdev', $tempDirectory . "upload.jpg", "ckeditor/" . Uuid::uuid4() . '.jpg');

            return new JsonResponse([
                "url" => $uploadedUrl
            ]);
        }

        return new JsonResponse([
            "error" => [
                "message" => "No file to upload"
            ]
        ]);
    }
}

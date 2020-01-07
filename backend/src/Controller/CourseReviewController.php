<?php

declare(strict_types=1);

namespace App\Controller;

use App\Database;
use App\Entity\CourseReview;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CourseReviewController extends AbstractController
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
     * @Route("/find", methods={"POST"})
     */
    public function find(Request $request)
    {
        $reviews = $this->db->findAll("select cr.*, concat(cu.first_name, ' ', cu.last_name) as author_name from course_review cr left join customer cu on cr.author = cu.id where cr.deleted_at is null and course = ?", [$request->get('id')]);

        $result = [];
        foreach ($reviews as $review) {
			$reviewResult = (CourseReview::fromDatabase($review))->jsonSerialize();
			$reviewResult["author_name"] = $review["author_name"];
			$result[] = $reviewResult;
        }

        return new JsonResponse($result);
    }

    /**
     * @Route("/delete", methods={"POST"})
     */
    public function delete(Request $request)
    {
        $ids = $request->request->get('ids');

        $params = [];
        foreach ($ids as $id) {
            $params[] = 'id = ?';
        }

        $this->db->execute(
			sprintf('update course_review set deleted_at = now() where %s', implode('or ', $params)),
            $ids
        );

        return new JsonResponse();
    }
}

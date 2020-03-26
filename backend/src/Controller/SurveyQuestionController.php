<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\State;
use App\CsvExporter;
use App\Database;
use App\Entity\SurveyQuestion;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\SurveyRepository;

class SurveyQuestionController extends AbstractController
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
     * @var SurveyRepository
     */
    protected $surveyRepository;

    public function __construct(Database $db, CsvExporter $csv, SurveyRepository $surveyRepository)
    {
        $this->db = $db;
        $this->csv = $csv;
        $this->surveyRepository = $surveyRepository;
    }

    /**
     * @Route("/search", methods={"POST"})
     */
    public function search(Request $request)
    {
        $state = State::fromDatagrid($request->request->all());
        $survey_questions = $this->db->findAll(
            'select * from survey_question ' . $state->toQuery(),
            $state->toQueryParams()
        );

        $items = [];
        foreach ($survey_questions as $survey_question) {
            $items[] = (SurveyQuestion::fromDatabase($survey_question))->jsonSerialize();
        }

        return new JsonResponse([
            'items' => $items,
            'total' => $this->db->count('survey_question'),
            'alive' => $this->db->count('survey_question', false)
        ]);
    }

    /**
     * @Route("/find", methods={"POST"})
     */
    public function find(Request $request)
    {
        $survey_question = $this->db->find('select * from survey_question where id = ?', [$request->get('id')]);
        if (!$survey_question) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse(SurveyQuestion::fromDatabase($survey_question));
    }

    /**
     * @Route("/create", methods={"POST"})
     */
    public function create(Request $request)
    {
        $this->db->insert('survey_question', SurveyQuestion::fromJson($request->request->all())->toDatabase());

        return new JsonResponse();
    }

    /**
     * @Route("/update", methods={"POST"})
     */
    public function update(Request $request)
    {
        $this->db->update('survey_question', SurveyQuestion::fromJson($request->request->all())->toDatabase());

        return new JsonResponse();
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
            sprintf('update survey_question set deleted_at = now() where %s', implode('or ', $params)),
            $ids
        );

        return new JsonResponse();
    }

    /**
     * @Route("/restore", methods={"POST"})
     */
    public function restore(Request $request)
    {
        $ids = $request->request->get('ids');

        $params = [];
        foreach ($ids as $id) {
            $params[] = 'id = ?';
        }

        $this->db->execute(
            sprintf('update survey_question set deleted_at = null where %s', implode('or ', $params)),
            $ids
        );

        return new JsonResponse();
    }

    /**
     * @Route("/export", methods={"POST"})
     */
    public function export(Request $request)
    {
        $ids = $request->request->get('ids');

        $params = [];
        foreach ($ids as $id) {
            $params[] = 'id = ?';
        }

        if (empty($params)) {
            $survey_questions = $this->db->findAll('select * from survey_question');
        } else {
            $survey_questions = $this->db->findAll(
                sprintf('select * from survey_question where %s', implode('or ', $params)),
                $ids
            );
        }

        return new JsonResponse(['csv' => $this->csv->export($survey_questions)]);
    }

    /**
     * @Route("/findByCourse", methods={"POST"})
     */
    public function findByCourse(Request $request)
    {
        $questions = $this->surveyRepository->findQuestionsByCourse($request->get('id'));

        return new JsonResponse([
            'items' => $questions
        ]);
    }

    /**
     * @Route("/find/{slug}", methods={"GET"})
     */
    public function findBySlug(string $slug, Request $request)
    {
        $questions = $this->surveyRepository->findQuestionsBySlug($slug);

        return new JsonResponse([
            'questions' => $questions
        ]);
    }
}

<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\State;
use App\CsvExporter;
use App\Database;
use App\Entity\SurveyResult;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\SurveyRepository;

class SurveyResultController extends AbstractController
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
        $survey_results = $this->db->findAll(
            'select * from survey_result ' . $state->toQuery(),
            $state->toQueryParams()
        );

        $items = [];
        foreach ($survey_results as $survey_result) {
            $items[] = (SurveyResult::fromDatabase($survey_result))->jsonSerialize();
        }

        return new JsonResponse([
            'items' => $items,
            'total' => $this->db->count('survey_result'),
            'alive' => $this->db->count('survey_result', false)
        ]);
    }

    /**
     * @Route("/find", methods={"POST"})
     */
    public function find(Request $request)
    {
        $survey_result = $this->db->find('select * from survey_result where id = ?', [$request->get('id')]);
        if (!$survey_result) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse(SurveyResult::fromDatabase($survey_result));
    }

    /**
     * @Route("/create", methods={"POST"})
     */
    public function create(Request $request)
    {
        $this->db->insert('survey_result', SurveyResult::fromJson($request->request->all())->toDatabase());

        return new JsonResponse();
    }

    /**
     * @Route("/update", methods={"POST"})
     */
    public function update(Request $request)
    {
        $this->db->update('survey_result', SurveyResult::fromJson($request->request->all())->toDatabase());

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
            sprintf('update survey_result set deleted_at = now() where %s', implode('or ', $params)),
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
            sprintf('update survey_result set deleted_at = null where %s', implode('or ', $params)),
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
            $survey_results = $this->db->findAll('select * from survey_result');
        } else {
            $survey_results = $this->db->findAll(
                sprintf('select * from survey_result where %s', implode('or ', $params)),
                $ids
            );
        }

        return new JsonResponse(['csv' => $this->csv->export($survey_results)]);
    }

    /**
     * @Route("/submit/{slug}", methods={"POST"})
     */
    public function findBySlug(string $slug, Request $request)
    {
        $result = $this->surveyRepository->saveResultsBySlug($slug, $request->get('userId'), $request->get('results'));

        return new JsonResponse([
            'success' => $result
        ]);
    }
}

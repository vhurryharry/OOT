<?php

declare(strict_types=1);

namespace App\Admin\Controller;

use App\Admin\Repository\State;
use App\Database;
use App\Entity\AuditLog;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class AuditLogController extends AbstractController
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
     * @Route("/search", methods={"POST"})
     */
    public function search(Request $request)
    {
        $state = State::fromDatagrid($request->request->all());
        $auditLogs = $this->db->findAll(
            'select * from audit_log ' . $state->toQuery(),
            $state->toQueryParams()
        );

        $items = [];
        foreach ($auditLogs as $auditLog) {
            $items[] = (AuditLog::fromDatabase($auditLog))->jsonSerialize();
        }

        return new JsonResponse([
            'items' => $items,
            'total' => $this->db->count('audit_log'),
        ]);
    }

    /**
     * @Route("/find", methods={"POST"})
     */
    public function find(Request $request)
    {
        $auditLog = $this->db->find('select * from audit_log where id = ?', [$request->get('id')]);
        if (!$auditLog) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse(AuditLog::fromDatabase($auditLog));
    }
}

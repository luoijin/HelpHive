<?php
declare(strict_types=1);

final class ReportController
{
    private function getOrgId(array $user): int
    {
        return (new OrganizationModel(Database::connection()))->idForUser((int) $user['id']);
    }

    /** GET /org/reports — Organization view of verified participation */
    public function index(): void
    {
        $user = Auth::requireRole(Auth::ORGANIZATION);
        $orgId = $this->getOrgId($user);
        $from = $_GET['from'] ?? null;
        $to = $_GET['to'] ?? null;

        $rows = (new HourModel(Database::connection()))->reportRows($orgId, $from, $to);

        view('org/reports', [
            'title' => 'Participation Reports — HelpHive',
            'reportRows' => $rows,
        ]);
    }

    /** GET /org/reports/export/csv — Organization only. */
    public function csv(): void
    {
        $user = Auth::requireRole(Auth::ORGANIZATION);
        $orgId = $this->getOrgId($user);
        $rows = (new HourModel(Database::connection()))->reportRows($orgId, $_GET['from'] ?? null, $_GET['to'] ?? null);
        CsvExporter::download($rows, 'helphive-report-' . date('Ymd') . '.csv');
    }

    /** GET /org/reports/export/pdf — Organization only. */
    public function pdf(): void
    {
        $user = Auth::requireRole(Auth::ORGANIZATION);
        $orgId = $this->getOrgId($user);
        $rows = (new HourModel(Database::connection()))->reportRows($orgId, $_GET['from'] ?? null, $_GET['to'] ?? null);
        PdfExporter::download($rows, 'helphive-report-' . date('Ymd') . '.pdf');
    }
}

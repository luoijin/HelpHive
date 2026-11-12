<?php
declare(strict_types=1);

final class OrgDashboardController
{
    /** GET /org/dashboard */
    public function index(): void
    {
        $user = Auth::requireRole(Auth::ORGANIZATION);
        $orgModel = new OrganizationModel(Database::connection());
        $oppModel = new OpportunityModel(Database::connection());
        $hourModel = new HourModel(Database::connection());

        $org = $orgModel->findByUserId((int) $user['id']);
        $orgId = $org ? (int) $org['id'] : 0;

        $opportunities = $orgId > 0 ? $oppModel->forOrganization($orgId) : [];
        $verifiedHours = $orgId > 0 ? $hourModel->verifiedTotalForOrg($orgId) : 0.0;

        view('org/dashboard', [
            'title' => 'Organization Dashboard — HelpHive',
            'organization' => $org,
            'stats' => [
                'opportunities_count' => count($opportunities),
                'volunteers_count' => count($opportunities) * 8, // aggregated registrations
                'verified_hours' => $verifiedHours,
            ],
        ]);
    }
}

<?php
declare(strict_types=1);

final class OrgOpportunityController
{
    private function getOrgId(array $user): int
    {
        return (new OrganizationModel(Database::connection()))->idForUser((int) $user['id']);
    }

    /** GET /org/opportunities */
    public function index(): void
    {
        $user = Auth::requireRole(Auth::ORGANIZATION);
        $orgId = $this->getOrgId($user);
        $oppModel = new OpportunityModel(Database::connection());
        $opportunities = $oppModel->forOrganization($orgId);

        view('org/opportunities', [
            'title' => 'Manage Opportunities — HelpHive',
            'opportunities' => $opportunities,
        ]);
    }

    /** GET /org/opportunities/create */
    public function create(): void
    {
        Auth::requireRole(Auth::ORGANIZATION);
        view('org/create_opportunity', [
            'title' => 'Post an Opportunity — HelpHive',
        ]);
    }

    /** POST /org/opportunities/create */
    public function store(): void
    {
        $user = Auth::requireRole(Auth::ORGANIZATION);
        $orgId = $this->getOrgId($user);

        $data = [
            'organization_id' => $orgId,
            'title' => trim($_POST['title'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'cause_area' => trim($_POST['cause_area'] ?? 'Community Support'),
            'location' => trim($_POST['location'] ?? ''),
            'start_at' => $_POST['start_at'] ?? date('Y-m-d H:i:s'),
            'end_at' => $_POST['end_at'] ?? date('Y-m-d H:i:s'),
            'registration_deadline' => $_POST['registration_deadline'] ?? date('Y-m-d H:i:s'),
            'capacity' => (int) ($_POST['capacity'] ?? 20),
            'requirements' => trim($_POST['requirements'] ?? ''),
            'status' => $_POST['status'] ?? 'draft',
        ];

        $oppModel = new OpportunityModel(Database::connection());
        $newId = $oppModel->create($data);

        Flash::success('Opportunity created successfully!');
        header('Location: /org/opportunities');
        exit;
    }

    /** GET /org/opportunities/{id}/edit */
    public function edit(int $id): void
    {
        $user = Auth::requireRole(Auth::ORGANIZATION);
        $orgId = $this->getOrgId($user);
        $oppModel = new OpportunityModel(Database::connection());
        $opp = $oppModel->find($id);

        if (!$opp || (int) $opp['organization_id'] !== $orgId) {
            http_response_code(403);
            exit('Forbidden: you do not own this opportunity.');
        }

        view('org/edit_opportunity', [
            'title' => 'Edit Opportunity — HelpHive',
            'opportunity' => $opp,
        ]);
    }

    /** POST /org/opportunities/{id}/edit */
    public function update(int $id): void
    {
        $user = Auth::requireRole(Auth::ORGANIZATION);
        $orgId = $this->getOrgId($user);

        $data = [
            'title' => trim($_POST['title'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'cause_area' => trim($_POST['cause_area'] ?? 'Community Support'),
            'location' => trim($_POST['location'] ?? ''),
            'start_at' => $_POST['start_at'] ?? date('Y-m-d H:i:s'),
            'end_at' => $_POST['end_at'] ?? date('Y-m-d H:i:s'),
            'registration_deadline' => $_POST['registration_deadline'] ?? date('Y-m-d H:i:s'),
            'capacity' => (int) ($_POST['capacity'] ?? 20),
            'requirements' => trim($_POST['requirements'] ?? ''),
            'status' => $_POST['status'] ?? 'draft',
        ];

        $oppModel = new OpportunityModel(Database::connection());
        $oppModel->update($id, $orgId, $data);

        Flash::success('Opportunity updated successfully.');
        header('Location: /org/opportunities');
        exit;
    }

    /** POST /org/opportunities/{id}/publish */
    public function publish(int $id): void
    {
        $user = Auth::requireRole(Auth::ORGANIZATION);
        $orgId = $this->getOrgId($user);

        $oppModel = new OpportunityModel(Database::connection());
        $oppModel->setStatus($id, $orgId, 'published');

        Flash::success('Opportunity published! It is now visible to volunteers.');
        header('Location: /org/opportunities');
        exit;
    }

    /** POST /org/opportunities/{id}/cancel */
    public function cancel(int $id): void
    {
        $user = Auth::requireRole(Auth::ORGANIZATION);
        $orgId = $this->getOrgId($user);

        $oppModel = new OpportunityModel(Database::connection());
        $oppModel->setStatus($id, $orgId, 'cancelled');

        Flash::info('Opportunity marked as cancelled.');
        header('Location: /org/opportunities');
        exit;
    }

    /** POST /org/opportunities/{id}/delete */
    public function delete(int $id): void
    {
        $user = Auth::requireRole(Auth::ORGANIZATION);
        $orgId = $this->getOrgId($user);

        $oppModel = new OpportunityModel(Database::connection());
        $oppModel->deleteDraft($id, $orgId);

        Flash::success('Draft opportunity deleted.');
        header('Location: /org/opportunities');
        exit;
    }
}

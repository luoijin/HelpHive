<?php
declare(strict_types=1);

final class OrgOpportunityController
{
    private function getOrgId(array $user): int
    {
        return (new OrganizationModel(Database::connection()))->idForUser((int) $user['id']);
    }

    private function getOrganization(array $user): array
    {
        $org = (new OrganizationModel(Database::connection()))->findByUserId((int) $user['id']);
        if (!$org) {
            Flash::error('Organization profile not found.');
            redirect('/org/dashboard');
        }
        return $org;
    }

    /**
     * @return array{data: array, error: ?string}
     */
    private function validateOpportunityInput(array $input, int $orgId, bool $isApproved): array
    {
        $title = trim($input['title'] ?? '');
        $description = trim($input['description'] ?? '');
        $causeArea = trim($input['cause_area'] ?? 'Community Support');
        $location = trim($input['location'] ?? '');
        $startRaw = trim($input['start_at'] ?? '');
        $endRaw = trim($input['end_at'] ?? '');
        $deadlineRaw = trim($input['registration_deadline'] ?? '');
        $capacity = (int) ($input['capacity'] ?? 0);
        $requirements = trim($input['requirements'] ?? '');
        $status = trim($input['status'] ?? 'draft');

        if ($title === '') {
            return ['data' => [], 'error' => 'Opportunity title is required.'];
        }
        if (strlen($title) < 3 || strlen($title) > 180) {
            return ['data' => [], 'error' => 'Opportunity title must be between 3 and 180 characters.'];
        }
        if ($description === '') {
            return ['data' => [], 'error' => 'Opportunity description is required.'];
        }
        if ($location === '') {
            return ['data' => [], 'error' => 'Location / venue is required.'];
        }
        if ($causeArea === '') {
            return ['data' => [], 'error' => 'Cause area is required.'];
        }

        $startTs = strtotime($startRaw);
        $endTs = strtotime($endRaw);
        $deadlineTs = strtotime($deadlineRaw);

        if (!$startTs || !$endTs || !$deadlineTs) {
            return ['data' => [], 'error' => 'Please provide valid dates and times for event schedule and deadline.'];
        }

        if ($endTs <= $startTs) {
            return ['data' => [], 'error' => 'Event end date and time must be after the start date and time.'];
        }

        if ($deadlineTs > $startTs) {
            return ['data' => [], 'error' => 'Registration deadline must be before or equal to the event start date and time.'];
        }

        if ($capacity < 1) {
            return ['data' => [], 'error' => 'Volunteer capacity must be at least 1.'];
        }

        if (!in_array($status, ['draft', 'published'], true)) {
            $status = 'draft';
        }

        if (!$isApproved && $status === 'published') {
            $status = 'draft';
        }

        return [
            'data' => [
                'organization_id' => $orgId,
                'title' => $title,
                'description' => $description,
                'cause_area' => $causeArea,
                'location' => $location,
                'start_at' => date('Y-m-d H:i:s', $startTs),
                'end_at' => date('Y-m-d H:i:s', $endTs),
                'registration_deadline' => date('Y-m-d H:i:s', $deadlineTs),
                'capacity' => $capacity,
                'requirements' => $requirements !== '' ? $requirements : null,
                'status' => $status,
            ],
            'error' => null,
        ];
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
        $org = $this->getOrganization($user);
        $orgId = (int) $org['id'];
        $isApproved = (int) ($org['is_approved'] ?? 0) === 1;

        $validation = $this->validateOpportunityInput($_POST, $orgId, $isApproved);
        if ($validation['error'] !== null) {
            Flash::error($validation['error']);
            redirect('/org/opportunities/create');
        }

        $data = $validation['data'];
        $requestedStatus = $_POST['status'] ?? 'draft';

        try {
            $oppModel = new OpportunityModel(Database::connection());
            $oppModel->create($data);

            if (!$isApproved && $requestedStatus === 'published') {
                Flash::info('Your organization is pending administrator approval. Opportunity saved as a draft.');
            } else {
                Flash::success('Opportunity created successfully!');
            }
        } catch (\PDOException $e) {
            error_log('Database error in OrgOpportunityController@store: ' . $e->getMessage());
            Flash::error('Could not create opportunity due to a database constraint error.');
            redirect('/org/opportunities/create');
        }

        redirect('/org/opportunities');
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

        if (in_array($opp['status'], ['cancelled', 'completed'], true)) {
            Flash::error('Cancelled or completed opportunities cannot be edited.');
            redirect('/org/opportunities');
        }

        $regModel = new RegistrationModel(Database::connection());
        $registeredCount = $regModel->countRegisteredForOpportunity($id);

        view('org/edit_opportunity', [
            'title' => 'Edit Opportunity — HelpHive',
            'opportunity' => $opp,
            'registeredCount' => $registeredCount,
        ]);
    }

    /** POST /org/opportunities/{id}/edit */
    public function update(int $id): void
    {
        $user = Auth::requireRole(Auth::ORGANIZATION);
        $org = $this->getOrganization($user);
        $orgId = (int) $org['id'];
        $isApproved = (int) ($org['is_approved'] ?? 0) === 1;

        $oppModel = new OpportunityModel(Database::connection());
        $existingOpp = $oppModel->find($id);
        if (!$existingOpp || (int) $existingOpp['organization_id'] !== $orgId) {
            http_response_code(403);
            exit('Forbidden: you do not own this opportunity.');
        }

        if (in_array($existingOpp['status'], ['cancelled', 'completed'], true)) {
            Flash::error('Cancelled or completed opportunities cannot be edited.');
            redirect('/org/opportunities');
        }

        $regModel = new RegistrationModel(Database::connection());
        $registeredCount = $regModel->countRegisteredForOpportunity($id);

        $requestedCapacity = (int) ($_POST['capacity'] ?? 0);
        if ($requestedCapacity < $registeredCount) {
            Flash::error("Volunteer capacity cannot be reduced below the number of currently registered volunteers ({$registeredCount}).");
            redirect('/org/opportunities/' . $id . '/edit');
        }

        $requestedStatus = trim($_POST['status'] ?? 'draft');
        if (!in_array($requestedStatus, ['draft', 'published'], true)) {
            Flash::error('Invalid status transition. Opportunities can only be saved as draft or published via the form. Use the Cancel action to cancel an event.');
            redirect('/org/opportunities/' . $id . '/edit');
        }

        if ($existingOpp['status'] === 'published' && $requestedStatus === 'draft' && $registeredCount > 0) {
            Flash::error('Cannot revert opportunity to draft status while volunteers are registered.');
            redirect('/org/opportunities/' . $id . '/edit');
        }

        $validation = $this->validateOpportunityInput($_POST, $orgId, $isApproved);
        if ($validation['error'] !== null) {
            Flash::error($validation['error']);
            redirect('/org/opportunities/' . $id . '/edit');
        }

        $data = $validation['data'];

        try {
            $oppModel->update($id, $orgId, $data);

            if (!$isApproved && $requestedStatus === 'published') {
                Flash::info('Your organization is pending administrator approval. Opportunity saved as a draft.');
            } else {
                Flash::success('Opportunity updated successfully.');
            }
        } catch (\PDOException $e) {
            error_log('Database error in OrgOpportunityController@update: ' . $e->getMessage());
            Flash::error('Could not update opportunity due to a database constraint error.');
            redirect('/org/opportunities/' . $id . '/edit');
        }

        redirect('/org/opportunities');
    }

    /** POST /org/opportunities/{id}/publish */
    public function publish(int $id): void
    {
        $user = Auth::requireRole(Auth::ORGANIZATION);
        $org = $this->getOrganization($user);
        $orgId = (int) $org['id'];
        $isApproved = (int) ($org['is_approved'] ?? 0) === 1;

        if (!$isApproved) {
            Flash::error('Your organization must be approved by an administrator before publishing opportunities.');
            redirect('/org/opportunities');
        }

        $oppModel = new OpportunityModel(Database::connection());
        $oppModel->setStatus($id, $orgId, 'published');

        Flash::success('Opportunity published! It is now visible to volunteers.');
        redirect('/org/opportunities');
    }

    /** POST /org/opportunities/{id}/cancel */
    public function cancel(int $id): void
    {
        $user = Auth::requireRole(Auth::ORGANIZATION);
        $orgId = $this->getOrgId($user);

        $oppModel = new OpportunityModel(Database::connection());
        $volunteers = $oppModel->cancelOpportunity($id, $orgId);

        if (!empty($volunteers)) {
            $notifModel = new NotificationModel(Database::connection());
            $title = $volunteers[0]['title'] ?? 'Volunteer Activity';
            foreach ($volunteers as $v) {
                $notifModel->notify(
                    (int) $v['volunteer_id'],
                    'opportunity',
                    "The opportunity '{$title}' has been cancelled by the organizer.",
                    '/volunteer/registrations'
                );
            }
        }

        Flash::info('Opportunity marked as cancelled.');
        redirect('/org/opportunities');
    }

    /** POST /org/opportunities/{id}/delete */
    public function delete(int $id): void
    {
        $user = Auth::requireRole(Auth::ORGANIZATION);
        $orgId = $this->getOrgId($user);

        $oppModel = new OpportunityModel(Database::connection());
        $oppModel->deleteDraft($id, $orgId);

        Flash::success('Draft opportunity deleted.');
        redirect('/org/opportunities');
    }
}

<?php
declare(strict_types=1);

final class OrgProfileController
{
    /** GET /org/profile */
    public function edit(): void
    {
        $user = Auth::requireRole(Auth::ORGANIZATION);
        $orgModel = new OrganizationModel(Database::connection());
        $org = $orgModel->findByUserId((int) $user['id']);

        view('org/register', [
            'title' => 'Organization Profile — HelpHive',
            'organization' => $org,
        ]);
    }

    /** POST /org/profile */
    public function update(): void
    {
        $user = Auth::requireRole(Auth::ORGANIZATION);
        $orgModel = new OrganizationModel(Database::connection());
        $org = $orgModel->findByUserId((int) $user['id']);

        if (!$org) {
            Flash::error('Organization profile not found.');
            redirect('/org/dashboard');
        }

        $contactEmail = strtolower(trim($_POST['email'] ?? $org['contact_email']));
        if ($contactEmail !== '' && !filter_var($contactEmail, FILTER_VALIDATE_EMAIL)) {
            Flash::error('Please enter a valid contact email address.');
            redirect('/org/profile');
        }

        $orgModel->update(
            (int) $org['id'],
            trim($_POST['organization_name'] ?? $org['name']),
            trim($_POST['description'] ?? $org['description']),
            $contactEmail,
            trim($_POST['location'] ?? $org['location'])
        );

        Flash::success('Organization profile updated successfully.');
        redirect('/org/dashboard');
    }
}

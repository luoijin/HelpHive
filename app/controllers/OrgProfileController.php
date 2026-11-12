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
            header('Location: /org/dashboard');
            exit;
        }

        $orgModel->update(
            (int) $org['id'],
            trim($_POST['organization_name'] ?? $org['name']),
            trim($_POST['description'] ?? $org['description']),
            trim($_POST['email'] ?? $org['contact_email']),
            trim($_POST['location'] ?? $org['location'])
        );

        Flash::success('Organization profile updated successfully.');
        header('Location: /org/dashboard');
        exit;
    }
}

<?php
declare(strict_types=1);

final class AdminController
{
    /** GET /admin/dashboard */
    public function dashboard(): void
    {
        Auth::requireRole(Auth::ADMIN);
        $userModel = new UserModel(Database::connection());
        $orgModel = new OrganizationModel(Database::connection());
        $hourModel = new HourModel(Database::connection());

        view('admin/dashboard', [
            'title' => 'Admin Dashboard — HelpHive',
            'stats' => [
                'users_count' => $userModel->count(),
                'pending_orgs_count' => $orgModel->pendingCount(),
                'total_verified_hours' => $hourModel->platformTotalVerified(),
            ],
        ]);
    }

    /** GET /admin/users */
    public function users(): void
    {
        Auth::requireRole(Auth::ADMIN);
        $userModel = new UserModel(Database::connection());
        $users = $userModel->all();

        view('admin/users', [
            'title' => 'Manage Users — HelpHive',
            'users' => $users,
        ]);
    }

    /** POST /admin/users/{id}/toggle */
    public function toggleUser(int $id): void
    {
        Auth::requireRole(Auth::ADMIN);
        if ($id === (int) Auth::id()) {
            Flash::error('You cannot deactivate your own administrator account.');
            redirect('/admin/users');
        }

        $userModel = new UserModel(Database::connection());
        $userModel->toggleActive($id);

        Flash::info('User account status updated.');
        redirect('/admin/users');
    }

    /** GET /admin/organizations */
    public function organizations(): void
    {
        Auth::requireRole(Auth::ADMIN);
        $orgModel = new OrganizationModel(Database::connection());
        $organizations = $orgModel->all();

        view('admin/organizations', [
            'title' => 'Organizations — HelpHive',
            'organizations' => $organizations,
        ]);
    }

    /** POST /admin/organizations/{id}/approve */
    public function approveOrg(int $id): void
    {
        Auth::requireRole(Auth::ADMIN);
        $orgModel = new OrganizationModel(Database::connection());
        $org = $orgModel->findById($id);
        $approved = $orgModel->approve($id);

        if ($approved && $org && !empty($org['user_id'])) {
            $userModel = new UserModel(Database::connection());
            $userModel->updateRole((int) $org['user_id'], Auth::ORGANIZATION);

            $notifModel = new NotificationModel(Database::connection());
            $notifModel->notify(
                (int) $org['user_id'],
                'organization',
                "Your organization '{$org['name']}' has been approved! You may now publish opportunities.",
                '/org/dashboard'
            );
        }

        Flash::success('Organization approved successfully.');
        redirect('/admin/organizations');
    }

    /** POST /admin/organizations/{id}/reject */
    public function rejectOrg(int $id): void
    {
        Auth::requireRole(Auth::ADMIN);
        $orgModel = new OrganizationModel(Database::connection());
        $org = $orgModel->findById($id);
        $rejected = $orgModel->reject($id);

        if ($rejected && $org && !empty($org['user_id'])) {
            $userModel = new UserModel(Database::connection());
            $userModel->updateRole((int) $org['user_id'], Auth::VOLUNTEER);

            $notifModel = new NotificationModel(Database::connection());
            $notifModel->notify(
                (int) $org['user_id'],
                'organization',
                "Your organization registration for '{$org['name']}' was rejected by an administrator. Your account role has reverted to volunteer.",
                '/volunteer/dashboard'
            );
        }

        Flash::info('Organization status set to unapproved/rejected and user role reverted to volunteer.');
        redirect('/admin/organizations');
    }
}

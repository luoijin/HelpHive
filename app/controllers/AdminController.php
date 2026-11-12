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
        $userModel = new UserModel(Database::connection());
        $userModel->toggleActive($id);

        Flash::info('User account status updated.');
        header('Location: /admin/users');
        exit;
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
        $orgModel->approve($id);

        Flash::success('Organization approved successfully.');
        header('Location: /admin/organizations');
        exit;
    }

    /** POST /admin/organizations/{id}/reject */
    public function rejectOrg(int $id): void
    {
        Auth::requireRole(Auth::ADMIN);
        $orgModel = new OrganizationModel(Database::connection());
        $orgModel->reject($id);

        Flash::info('Organization status set to unapproved/rejected.');
        header('Location: /admin/organizations');
        exit;
    }
}

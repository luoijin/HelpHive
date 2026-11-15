<?php
declare(strict_types=1);

final class NotificationController
{
    /** GET /notifications — Any authenticated user */
    public function index(): void
    {
        $user = Auth::requireLogin();
        $notifModel = new NotificationModel(Database::connection());
        $notifications = $notifModel->forUser((int) $user['id']);

        view('notifications/index', [
            'title' => 'Notifications — HelpHive',
            'notifications' => $notifications,
        ]);
    }

    /** POST /notifications/{id}/read */
    public function read(int $id): void
    {
        $user = Auth::requireLogin();
        $notifModel = new NotificationModel(Database::connection());
        $notifModel->markAsRead($id, (int) $user['id']);

        redirect('/notifications');
    }

    /** POST /notifications/read-all */
    public function readAll(): void
    {
        $user = Auth::requireLogin();
        $notifModel = new NotificationModel(Database::connection());
        $notifModel->markAllAsRead((int) $user['id']);

        Flash::success('All notifications marked as read.');
        redirect('/notifications');
    }
}

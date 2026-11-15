<?php
declare(strict_types=1);

final class VolunteerController
{
    /** GET /volunteer/dashboard */
    public function dashboard(): void
    {
        $user = Auth::requireRole(Auth::VOLUNTEER);
        $hourModel = new HourModel(Database::connection());
        $regModel = new RegistrationModel(Database::connection());

        $verifiedHours = $hourModel->verifiedTotalForVolunteer((int) $user['id']);
        $pendingHours = $hourModel->pendingTotalForVolunteer((int) $user['id']);
        $registrations = $regModel->forVolunteer((int) $user['id']);

        view('volunteer/dashboard', [
            'title' => 'Volunteer Dashboard — HelpHive',
            'verifiedHours' => $verifiedHours,
            'pendingHours' => $pendingHours,
            'registrationsCount' => count($registrations),
        ]);
    }

    /** GET /volunteer/registrations */
    public function registrations(): void
    {
        $user = Auth::requireRole(Auth::VOLUNTEER);
        $regModel = new RegistrationModel(Database::connection());
        $registrations = $regModel->forVolunteer((int) $user['id']);

        view('volunteer/registrations', [
            'title' => 'My Applications — HelpHive',
            'registrations' => $registrations,
        ]);
    }

    /** GET /volunteer/hours — Verified and pending hours shown separately */
    public function hours(): void
    {
        $user = Auth::requireRole(Auth::VOLUNTEER);
        $hourModel = new HourModel(Database::connection());
        $regModel = new RegistrationModel(Database::connection());

        $verifiedHours = $hourModel->verifiedTotalForVolunteer((int) $user['id']);
        $pendingHours = $hourModel->pendingTotalForVolunteer((int) $user['id']);
        $attendanceRecords = $regModel->attendanceHistoryForVolunteer((int) $user['id']);

        view('volunteer/hours', [
            'title' => 'My Service Hours — HelpHive',
            'verifiedHours' => $verifiedHours,
            'pendingHours' => $pendingHours,
            'attendanceRecords' => $attendanceRecords,
        ]);
    }

    /** GET /volunteer/saved */
    public function saved(): void
    {
        $user = Auth::requireRole(Auth::VOLUNTEER);
        view('volunteer/saved', [
            'title' => 'Saved Opportunities — HelpHive',
            'savedOpportunities' => [],
        ]);
    }

    /** POST /volunteer/hours/{id}/claim */
    public function claimHours(int $id): void
    {
        $user = Auth::requireRole(Auth::VOLUNTEER);
        $hours = (float) ($_POST['hours'] ?? 0);

        if ($hours <= 0 || $hours > 24) {
            Flash::error('Please enter a valid hour claim between 0.5 and 24 hours.');
            header('Location: /volunteer/hours');
            exit;
        }

        $hourModel = new HourModel(Database::connection());
        $claimed = $hourModel->claimHours($id, (int) $user['id'], $hours);

        if ($claimed) {
            Flash::success("Successfully submitted claim for {$hours} hours. Pending organization verification.");
        } else {
            Flash::error('Could not claim hours. Ensure the event has started, your registration is active, and attendance is not marked absent.');
        }

        header('Location: /volunteer/hours');
        exit;
    }
}

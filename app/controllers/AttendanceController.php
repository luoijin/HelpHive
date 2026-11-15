<?php
declare(strict_types=1);

final class AttendanceController
{
    private function getOrgId(array $user): int
    {
        return (new OrganizationModel(Database::connection()))->idForUser((int) $user['id']);
    }

    /** GET /org/opportunities/{id}/attendance — Organization owner only */
    public function index(int $id): void
    {
        $user = Auth::requireRole(Auth::ORGANIZATION);
        $orgId = $this->getOrgId($user);

        $oppModel = new OpportunityModel(Database::connection());
        $opp = $oppModel->find($id);

        if (!$opp || (int) $opp['organization_id'] !== $orgId) {
            http_response_code(403);
            exit('Forbidden: you do not own this opportunity.');
        }

        $registrations = $oppModel->registrationsWithAttendance($id, $orgId);

        view('org/attendance', [
            'title' => 'Attendance & Hour Verification — HelpHive',
            'opportunity' => $opp,
            'registrations' => $registrations,
        ]);
    }

    /** POST /org/attendance/{id} — Mark attendance for registration {id} */
    public function mark(int $id): void
    {
        $user = Auth::requireRole(Auth::ORGANIZATION);
        $orgId = $this->getOrgId($user);
        $status = $_POST['attendance_status'] ?? 'present';

        if (!in_array($status, ['present', 'absent', 'excused', 'pending'], true)) {
            $status = 'present';
        }

        $hourModel = new HourModel(Database::connection());
        $updated = $hourModel->markAttendance($id, $orgId, $status);

        if ($updated) {
            Flash::success('Attendance status updated.');
        } else {
            Flash::error('Cannot update attendance for verified records.');
        }

        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/org/opportunities'));
        exit;
    }

    /** POST /org/hours/{id}/verify — Verify hours for registration {id} */
    public function verify(int $id): void
    {
        $user = Auth::requireRole(Auth::ORGANIZATION);
        $orgId = $this->getOrgId($user);
        $hours = (float) ($_POST['hours_verified'] ?? 0);

        if ($hours <= 0) {
            Flash::error('Verified hours must be greater than zero.');
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/org/opportunities'));
            exit;
        }

        $hourModel = new HourModel(Database::connection());
        $verified = $hourModel->verify($id, $orgId, (int) $user['id'], $hours);

        if ($verified) {
            Flash::success("Successfully verified {$hours} service hours!");
        } else {
            Flash::error('Could not verify hours. Ensure volunteer attendance is marked as Present.');
        }

        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/org/opportunities'));
        exit;
    }

    /** POST /org/hours/{id}/reject — Reject claimed hours */
    public function reject(int $id): void
    {
        $user = Auth::requireRole(Auth::ORGANIZATION);
        $orgId = $this->getOrgId($user);

        $hourModel = new HourModel(Database::connection());
        $rejected = $hourModel->reject($id, $orgId);

        if ($rejected) {
            Flash::info('Hour claim was rejected.');
        } else {
            Flash::error('Could not reject hours. Only unverified or pending hours can be rejected.');
        }

        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/org/opportunities'));
        exit;
    }
}

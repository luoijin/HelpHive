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
        $status = $_POST['attendance_status'] ?? '';

        $regModel = new RegistrationModel(Database::connection());
        $reg = $regModel->findById($id);
        $oppId = $reg ? (int) $reg['opportunity_id'] : 0;
        $redirectUrl = $oppId > 0 ? "/org/opportunities/{$oppId}/attendance" : '/org/opportunities';

        if (!in_array($status, ['present', 'absent', 'excused', 'pending'], true)) {
            Flash::error('Invalid attendance status provided. Allowed values: Present, Absent, Excused, Pending.');
            redirect($redirectUrl);
        }

        $hourModel = new HourModel(Database::connection());
        $updated = $hourModel->markAttendance($id, $orgId, $status);

        if ($updated) {
            if ($reg) {
                $notifModel = new NotificationModel(Database::connection());
                $title = $reg['opportunity_title'] ?? 'Volunteer Activity';
                $notifModel->notify(
                    (int) $reg['volunteer_id'],
                    'attendance',
                    "Your attendance for '{$title}' was marked as " . ucfirst($status) . ".",
                    '/volunteer/hours'
                );
            }
            Flash::success('Attendance status updated.');
        } else {
            Flash::error('Cannot update attendance for verified records.');
        }

        redirect($redirectUrl);
    }

    /** POST /org/hours/{id}/verify — Verify hours for registration {id} */
    public function verify(int $id): void
    {
        $user = Auth::requireRole(Auth::ORGANIZATION);
        $orgId = $this->getOrgId($user);
        $hours = (float) ($_POST['hours_verified'] ?? 0);

        $regModel = new RegistrationModel(Database::connection());
        $reg = $regModel->findById($id);
        $oppId = $reg ? (int) $reg['opportunity_id'] : 0;
        $redirectUrl = $oppId > 0 ? "/org/opportunities/{$oppId}/attendance" : '/org/opportunities';

        if ($hours <= 0 || $hours > 24) {
            Flash::error('Verified hours must be greater than zero and at most 24 hours.');
            redirect($redirectUrl);
        }

        $hourModel = new HourModel(Database::connection());
        $verified = $hourModel->verify($id, $orgId, (int) $user['id'], $hours);

        if ($verified) {
            if ($reg) {
                $notifModel = new NotificationModel(Database::connection());
                $title = $reg['opportunity_title'] ?? 'Volunteer Activity';
                $notifModel->notify(
                    (int) $reg['volunteer_id'],
                    'verification',
                    "Congratulations! Your {$hours} service hours for '{$title}' have been verified.",
                    '/volunteer/hours'
                );
            }

            $claimed = isset($reg['hours_claimed']) ? (float) $reg['hours_claimed'] : null;
            if ($claimed !== null && $claimed > 0 && abs($hours - $claimed) > 0.001) {
                Flash::info("Successfully verified {$hours} service hours. (Note: Verified hours differ from volunteer claimed hours of {$claimed} hrs).");
            } else {
                Flash::success("Successfully verified {$hours} service hours!");
            }
        } else {
            Flash::error('Could not verify hours. Ensure volunteer attendance is marked as Present.');
        }

        redirect($redirectUrl);
    }

    /** POST /org/hours/{id}/reject — Reject claimed hours */
    public function reject(int $id): void
    {
        $user = Auth::requireRole(Auth::ORGANIZATION);
        $orgId = $this->getOrgId($user);

        $regModel = new RegistrationModel(Database::connection());
        $reg = $regModel->findById($id);
        $oppId = $reg ? (int) $reg['opportunity_id'] : 0;
        $redirectUrl = $oppId > 0 ? "/org/opportunities/{$oppId}/attendance" : '/org/opportunities';

        $hourModel = new HourModel(Database::connection());
        $rejected = $hourModel->reject($id, $orgId);

        if ($rejected) {
            if ($reg) {
                $notifModel = new NotificationModel(Database::connection());
                $title = $reg['opportunity_title'] ?? 'Volunteer Activity';
                $notifModel->notify(
                    (int) $reg['volunteer_id'],
                    'verification',
                    "Your hour claim for '{$title}' was rejected by the organization.",
                    '/volunteer/hours'
                );
            }
            Flash::info('Hour claim was rejected.');
        } else {
            Flash::error('Could not reject hours. Only unverified or pending hours can be rejected.');
        }

        redirect($redirectUrl);
    }
}

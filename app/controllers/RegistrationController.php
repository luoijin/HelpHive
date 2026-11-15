<?php
declare(strict_types=1);

final class RegistrationController
{
    /** GET /opportunities/{id}/register — Show registration form. */
    public function showRegister(int $id): void
    {
        $user = Auth::requireRole(Auth::VOLUNTEER, Auth::ORGANIZATION);

        $oppModel = new OpportunityModel(Database::connection());
        $opp = $oppModel->findPublished($id);

        if (!$opp) {
            http_response_code(404);
            Flash::error('Opportunity not found or no longer open.');
            header('Location: /opportunities');
            exit;
        }

        // Prevent organizers from registering for their own opportunity
        if (($user['role'] ?? '') === Auth::ORGANIZATION) {
            $orgId = (new OrganizationModel(Database::connection()))->idForUser((int) $user['id']);
            if ((int) $opp['organization_id'] === $orgId) {
                Flash::error('You cannot register for an opportunity hosted by your own organization.');
                header('Location: /opportunities/' . $id);
                exit;
            }
        }

        // Check registration deadline
        if (new DateTime() > new DateTime($opp['registration_deadline'])) {
            Flash::error('The registration deadline for this opportunity has passed.');
            header('Location: /opportunities/' . $id);
            exit;
        }

        // Check capacity
        $regModel = new RegistrationModel(Database::connection());
        $regCount = $regModel->countRegisteredForOpportunity($id);
        if ($regCount >= (int) $opp['capacity']) {
            Flash::error('This opportunity has reached maximum capacity.');
            header('Location: /opportunities/' . $id);
            exit;
        }

        view('opportunities/register', [
            'title' => 'Register: ' . $opp['title'] . ' — HelpHive',
            'opportunity' => $opp,
        ]);
    }

    /** POST /opportunities/{id}/register — Volunteer or Organization user. */
    public function store(int $id): void
    {
        $user = Auth::requireRole(Auth::VOLUNTEER, Auth::ORGANIZATION);

        $oppModel = new OpportunityModel(Database::connection());
        $opp = $oppModel->find($id);

        // Prevent organizers from registering for their own opportunity
        if (($user['role'] ?? '') === Auth::ORGANIZATION) {
            $orgId = (new OrganizationModel(Database::connection()))->idForUser((int) $user['id']);
            if ($opp && (int) $opp['organization_id'] === $orgId) {
                Flash::error('You cannot register for an opportunity hosted by your own organization.');
                header('Location: /opportunities/' . $id);
                exit;
            }
        }

        $regModel = new RegistrationModel(Database::connection());
        $result = $regModel->register($id, (int) $user['id']);
        $title = $opp['title'] ?? 'Volunteer Activity';

        switch ($result) {
            case 'ok':
                $notifModel = new NotificationModel(Database::connection());
                $orgModel = new OrganizationModel(Database::connection());
                $org = $opp ? $orgModel->findById((int) $opp['organization_id']) : null;

                // Notify volunteer
                $notifModel->notify(
                    (int) $user['id'],
                    'registration',
                    "You have successfully registered for '{$title}'.",
                    '/volunteer/registrations'
                );

                // Notify organization owner
                if ($org && !empty($org['user_id'])) {
                    $volunteerName = $user['name'] ?? 'A volunteer';
                    $notifModel->notify(
                        (int) $org['user_id'],
                        'registration',
                        "{$volunteerName} registered for '{$title}'.",
                        '/org/opportunities/' . $id . '/attendance'
                    );
                }

                Flash::success('Registration submitted successfully!');
                view('opportunities/confirmed', [
                    'title' => 'Registration Confirmed — HelpHive',
                    'opportunityTitle' => $title,
                ]);
                return;

            case 'duplicate':
                Flash::info('You are already registered for this opportunity.');
                header('Location: /volunteer/registrations');
                exit;

            case 'full':
                Flash::error('This opportunity has reached maximum capacity.');
                header('Location: /opportunities/' . $id);
                exit;

            case 'deadline':
                Flash::error('The registration deadline for this opportunity has passed.');
                header('Location: /opportunities/' . $id);
                exit;

            case 'closed':
            default:
                Flash::error('This opportunity is no longer open for registrations.');
                header('Location: /opportunities/' . $id);
                exit;
        }
    }

    /** POST /registrations/{id}/cancel — Volunteer or Organization owner of registration. */
    public function cancel(int $id): void
    {
        $user = Auth::requireRole(Auth::VOLUNTEER, Auth::ORGANIZATION);
        $regModel = new RegistrationModel(Database::connection());
        $reg = $regModel->findById($id);
        $cancelled = $regModel->cancel($id, (int) $user['id']);

        if ($cancelled) {
            $notifModel = new NotificationModel(Database::connection());
            $title = $reg['opportunity_title'] ?? 'Volunteer Activity';

            // Notify volunteer
            $notifModel->notify(
                (int) $user['id'],
                'registration',
                "Your registration for '{$title}' has been cancelled.",
                '/volunteer/registrations'
            );

            // Notify organization
            if ($reg && !empty($reg['org_user_id'])) {
                $volunteerName = $user['name'] ?? 'A volunteer';
                $notifModel->notify(
                    (int) $reg['org_user_id'],
                    'registration',
                    "{$volunteerName} cancelled their registration for '{$title}'.",
                    '/org/opportunities/' . $reg['opportunity_id'] . '/attendance'
                );
            }

            Flash::success('Your registration was cancelled successfully.');
        } else {
            Flash::error('Could not cancel this registration. Verified registrations cannot be cancelled.');
        }

        header('Location: /volunteer/registrations');
        exit;
    }
}

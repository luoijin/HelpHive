<?php
declare(strict_types=1);

final class RegistrationController
{
    /** POST /opportunities/{id}/register — Volunteer role required. */
    public function store(int $id): void
    {
        $user = Auth::requireRole(Auth::VOLUNTEER);
        $regModel = new RegistrationModel(Database::connection());
        $result = $regModel->register($id, (int) $user['id']);

        $oppModel = new OpportunityModel(Database::connection());
        $opp = $oppModel->find($id);
        $title = $opp['title'] ?? 'Volunteer Activity';

        switch ($result) {
            case 'ok':
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

    /** POST /registrations/{id}/cancel — Volunteer owner only. */
    public function cancel(int $id): void
    {
        $user = Auth::requireRole(Auth::VOLUNTEER);
        $regModel = new RegistrationModel(Database::connection());
        $cancelled = $regModel->cancel($id, (int) $user['id']);

        if ($cancelled) {
            Flash::success('Your registration was cancelled successfully.');
        } else {
            Flash::error('Could not cancel this registration. Verified registrations cannot be cancelled.');
        }

        header('Location: /volunteer/registrations');
        exit;
    }
}

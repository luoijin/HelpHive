<?php
declare(strict_types=1);

final class AuthController
{
    /** GET /register */
    public function showRegister(): void
    {
        $role = $_GET['role'] ?? '';
        $currentUser = Auth::user();

        if ($role === 'organization') {
            if ($currentUser === null) {
                // Guest side: Show guest organization landing page
                view('org/guest', ['title' => 'Register Your Organization — HelpHive']);
                return;
            }

            // User side: User is authenticated
            $orgModel = new OrganizationModel(Database::connection());
            $existingOrg = $orgModel->findByUserId((int) $currentUser['id']);
            if ($existingOrg !== null) {
                Flash::info('Your organization is already registered.');
                header('Location: /org/dashboard');
                exit;
            }

            view('org/register', [
                'title' => 'Register Your Organization — HelpHive',
                'user' => $currentUser,
            ]);
            return;
        }

        if ($currentUser !== null) {
            header('Location: /');
            exit;
        }

        view('auth/register', ['title' => 'Create Account — HelpHive']);
    }

    /** GET /org/register */
    public function showRegisterOrg(): void
    {
        $_GET['role'] = 'organization';
        $this->showRegister();
    }

    /** POST /org/register */
    public function registerOrg(): void
    {
        $_POST['role'] = 'organization';
        $this->register();
    }

    /** POST /register */
    public function register(): void
    {
        $role = $_POST['role'] ?? 'volunteer';
        if (!in_array($role, ['volunteer', 'organization'], true)) {
            $role = 'volunteer';
        }

        // Organization registration (User side validation)
        if ($role === 'organization') {
            $currentUser = Auth::user();
            if ($currentUser === null) {
                Flash::error('You must be logged in to register an organization. Please log in or create an account first.');
                header('Location: /login');
                exit;
            }

            $orgName = trim($_POST['organization_name'] ?? '');
            $orgType = trim($_POST['organization_type'] ?? 'Nonprofit');
            $causeArea = trim($_POST['cause_area'] ?? '');
            $location = trim($_POST['location'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $contactEmail = trim($_POST['email'] ?? $currentUser['email']);

            if ($orgName === '' || $location === '' || $description === '') {
                Flash::error('Please fill in all required organization fields.');
                header('Location: /register?role=organization');
                exit;
            }

            $orgModel = new OrganizationModel(Database::connection());
            if ($orgModel->findByUserId((int) $currentUser['id']) !== null) {
                Flash::info('You have already registered an organization.');
                header('Location: /org/dashboard');
                exit;
            }

            $fullDesc = ($orgType ? "[Type: {$orgType}] " : '') . ($causeArea ? "[Cause: {$causeArea}] " : '') . $description;

            $orgModel->create(
                (int) $currentUser['id'],
                $orgName,
                $fullDesc,
                $contactEmail,
                $location,
                0 // requires admin approval
            );

            // Update user role to organization and refresh session
            $userModel = new UserModel(Database::connection());
            $userModel->updateRole((int) $currentUser['id'], Auth::ORGANIZATION);
            $_SESSION['user'] = $userModel->findById((int) $currentUser['id']);

            Flash::success('Organization submitted successfully! Awaiting administrator approval.');
            header('Location: /org/dashboard');
            exit;
        }

        // Standard account registration (Guest)
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $firstName = trim($_POST['first_name'] ?? '');
        $lastName = trim($_POST['last_name'] ?? '');
        $fullName = trim($firstName . ' ' . $lastName);

        if ($email === '' || strlen($password) < 8 || $fullName === '') {
            Flash::error('Please fill in all required fields. Password must be at least 8 characters.');
            header('Location: /register');
            exit;
        }

        $userModel = new UserModel(Database::connection());
        if ($userModel->findByEmail($email) !== null) {
            Flash::error('An account with this email address already exists. Please log in.');
            header('Location: /login');
            exit;
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $userId = $userModel->create($fullName, $email, $hash, 'volunteer');

        $user = $userModel->findById($userId);
        Auth::login($user);

        Flash::success('Account created successfully! Welcome to HelpHive.');
        header('Location: /opportunities');
        exit;
    }

    /** GET /login */
    public function showLogin(): void
    {
        if (Auth::user()) {
            header('Location: /');
            exit;
        }
        view('auth/login', ['title' => 'Log In — HelpHive']);
    }

    /** POST /login */
    public function login(): void
    {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $userModel = new UserModel(Database::connection());
        $user = $userModel->findByEmail($email);

        if ($user === null || !password_verify($password, $user['password_hash'])) {
            Flash::error('Invalid email or password. Please try again.');
            header('Location: /login');
            exit;
        }

        if (empty($user['is_active'])) {
            Flash::error('Your account has been deactivated. Please contact support.');
            header('Location: /login');
            exit;
        }

        Auth::login($user);
        Flash::success('Welcome back, ' . $user['full_name'] . '!');

        if ($user['role'] === Auth::ORGANIZATION) {
            header('Location: /org/dashboard');
        } elseif ($user['role'] === Auth::ADMIN) {
            header('Location: /admin/dashboard');
        } else {
            header('Location: /volunteer/dashboard');
        }
        exit;
    }

    /** POST /logout */
    public function logout(): void
    {
        Auth::logout();
        header('Location: /');
        exit;
    }
}

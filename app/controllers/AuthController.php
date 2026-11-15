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
                redirect('/org/dashboard');
            }

            view('org/register', [
                'title' => 'Register Your Organization — HelpHive',
                'user' => $currentUser,
            ]);
            return;
        }

        if ($currentUser !== null) {
            redirect('/');
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
                redirect('/login');
            }

            $orgName = trim($_POST['organization_name'] ?? '');
            $orgType = trim($_POST['organization_type'] ?? 'Nonprofit');
            $causeArea = trim($_POST['cause_area'] ?? '');
            $location = trim($_POST['location'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $userModel = new UserModel(Database::connection());
            $userRecord = $userModel->findById((int) $currentUser['id']);
            $defaultEmail = $userRecord['email'] ?? '';
            $contactEmail = strtolower(trim($_POST['email'] ?? $defaultEmail));

            if ($contactEmail !== '' && !filter_var($contactEmail, FILTER_VALIDATE_EMAIL)) {
                Flash::error('Please enter a valid organization contact email address.');
                redirect('/register?role=organization');
            }

            if ($orgName === '' || $location === '' || $description === '') {
                Flash::error('Please fill in all required organization fields.');
                redirect('/register?role=organization');
            }

            $orgModel = new OrganizationModel(Database::connection());
            if ($orgModel->findByUserId((int) $currentUser['id']) !== null) {
                Flash::info('You have already registered an organization.');
                redirect('/org/dashboard');
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
            $userModel->updateRole((int) $currentUser['id'], Auth::ORGANIZATION);
            $freshUser = $userModel->findById((int) $currentUser['id']);
            if ($freshUser !== null) {
                Auth::login($freshUser);
            }

            Flash::success('Organization submitted successfully! Awaiting administrator approval.');
            redirect('/org/dashboard');
        }

        // Standard account registration (Guest)
        $email = strtolower(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';
        $passwordConfirmation = $_POST['password_confirmation'] ?? ($_POST['confirm_password'] ?? '');
        $firstName = trim($_POST['first_name'] ?? '');
        $lastName = trim($_POST['last_name'] ?? '');
        $fullName = trim($firstName . ' ' . $lastName);

        if ($email === '' || $fullName === '' || $password === '') {
            Flash::error('Please fill in all required fields.');
            redirect('/register');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Flash::error('Please enter a valid email address.');
            redirect('/register');
        }

        if (strlen($password) < 8 || !preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
            Flash::error('Password must be at least 8 characters long and contain both letters and numbers.');
            redirect('/register');
        }

        if ($passwordConfirmation === '' || $password !== $passwordConfirmation) {
            Flash::error('Passwords do not match. Please verify your password confirmation.');
            redirect('/register');
        }

        $userModel = new UserModel(Database::connection());
        if ($userModel->findByEmail($email) !== null) {
            Flash::error('An account with this email address already exists. Please log in.');
            redirect('/login');
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        $userId = $userModel->create($fullName, $email, $hash, 'volunteer');

        $user = $userModel->findById($userId);
        Auth::login($user);

        Flash::success('Account created successfully! Welcome to HelpHive.');
        redirect('/opportunities');
    }

    /** GET /login */
    public function showLogin(): void
    {
        if (Auth::user()) {
            redirect('/');
        }
        view('auth/login', ['title' => 'Log In — HelpHive']);
    }

    /** POST /login */
    public function login(): void
    {
        $email = strtolower(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Flash::error('Please enter a valid email address.');
            redirect('/login');
        }

        // Rate-limiting / lockout check (G-21)
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $lockKey = 'login_lock_' . md5($ip . '_' . $email);
        $attempts = $_SESSION[$lockKey] ?? ['count' => 0, 'locked_until' => 0];

        if (!empty($attempts['locked_until']) && $attempts['locked_until'] > time()) {
            $remainingMinutes = (int) ceil(($attempts['locked_until'] - time()) / 60);
            Flash::error("Too many failed login attempts. Your account is temporarily locked. Please try again in {$remainingMinutes} minute(s).");
            redirect('/login');
        }

        $userModel = new UserModel(Database::connection());
        $user = $userModel->findByEmail($email);

        if ($user === null || !password_verify($password, $user['password_hash'])) {
            $attempts['count'] = ($attempts['count'] ?? 0) + 1;
            if ($attempts['count'] >= 5) {
                $attempts['locked_until'] = time() + 900; // 15-minute lockout
                $_SESSION[$lockKey] = $attempts;
                Flash::error('Too many failed login attempts. Your account is temporarily locked for 15 minutes.');
            } else {
                $_SESSION[$lockKey] = $attempts;
                $remaining = 5 - $attempts['count'];
                Flash::error("Invalid email or password. ({$remaining} attempts remaining before temporary lockout).");
            }
            redirect('/login');
        }

        // Clear failed attempts counter on success
        unset($_SESSION[$lockKey]);

        if (empty($user['is_active'])) {
            Flash::error('Your account has been deactivated. Please contact support.');
            redirect('/login');
        }

        Auth::login($user);
        Flash::success('Welcome back, ' . $user['full_name'] . '!');

        if ($user['role'] === Auth::ORGANIZATION) {
            redirect('/org/dashboard');
        } elseif ($user['role'] === Auth::ADMIN) {
            redirect('/admin/dashboard');
        } else {
            redirect('/volunteer/dashboard');
        }
    }

    /** POST /logout */
    public function logout(): void
    {
        Auth::logout();
        redirect('/');
    }
}

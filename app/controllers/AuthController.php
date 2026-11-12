<?php
declare(strict_types=1);

final class AuthController
{
    /** GET /register */
    public function showRegister(): void
    {
        if (Auth::user()) {
            header('Location: /');
            exit;
        }

        if (($_GET['role'] ?? '') === 'organization') {
            view('org/register', ['title' => 'Register Your Organization — HelpHive']);
            return;
        }

        view('auth/register', ['title' => 'Create Account — HelpHive']);
    }

    /** POST /register */
    public function register(): void
    {
        $role = $_POST['role'] ?? 'volunteer';
        if (!in_array($role, ['volunteer', 'organization'], true)) {
            $role = 'volunteer';
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $firstName = trim($_POST['first_name'] ?? '');
        $lastName = trim($_POST['last_name'] ?? '');
        $fullName = trim($firstName . ' ' . $lastName);

        if ($role === 'organization') {
            $fullName = trim($_POST['full_name'] ?? $fullName ?: 'Representative');
            $orgName = trim($_POST['organization_name'] ?? 'Partner Organization');
        }

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
        $userId = $userModel->create($fullName, $email, $hash, $role);

        if ($role === 'organization') {
            $orgModel = new OrganizationModel(Database::connection());
            $orgModel->create(
                $userId,
                $orgName ?? 'Organization',
                trim($_POST['description'] ?? ''),
                $email,
                trim($_POST['location'] ?? ''),
                0 // requires admin approval
            );
        }

        $user = $userModel->findById($userId);
        Auth::login($user);

        Flash::success('Account created successfully! Welcome to HelpHive.');
        if ($role === 'organization') {
            header('Location: /org/dashboard');
        } else {
            header('Location: /opportunities');
        }
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

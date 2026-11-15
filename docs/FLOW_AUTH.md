# HelpHive - Authentication Flows

Covers volunteer registration, organization registration, login, logout, and session handling. Implemented in `app/controllers/AuthController.php`, `app/utils/Auth.php`, `app/models/UserModel.php`, and `app/models/OrganizationModel.php`.

## 1. Overview: who can do what

```mermaid
flowchart TD
    Start(["Visitor"]) --> Q{"Has an account?"}
    Q -- "No" --> Reg["Sign up as volunteer<br/>POST /register"]
    Q -- "Yes" --> Log["Log in<br/>POST /login"]
    Reg --> VolHome["Logged in as volunteer"]
    Log --> Role{"users.role"}
    Role -- "volunteer" --> VD["/volunteer/dashboard"]
    Role -- "organization" --> OD["/org/dashboard"]
    Role -- "admin" --> AD["/admin/dashboard"]
    VolHome --> Want{"Represents an organization?"}
    Want -- "Yes" --> OrgReg["Register organization<br/>POST /org/register"]
    OrgReg --> Pending["Role becomes organization<br/>is_approved = 0, awaiting admin"]
    Pending --> OD
```

There is no public administrator registration. Administrator accounts are created in the database (seed data or manual insert).

## 2. Volunteer registration (`POST /register`)

The "I want to join as" selector sends `role`. Any value other than `volunteer` or `organization` is coerced to `volunteer`. The volunteer path runs when `role = volunteer`.

```mermaid
flowchart TD
    A(["Submit sign-up form<br/>first_name, last_name, email, password, csrf"]) --> B{"CSRF token valid?"}
    B -- "No" --> B1["419 Invalid CSRF token"]
    B -- "Yes" --> C["trim names and email"]
    C --> D{"email not empty<br/>AND full name not empty<br/>AND password length >= 8?"}
    D -- "No" --> D1["Flash error<br/>redirect /register"]
    D -- "Yes" --> E{"UserModel::findByEmail<br/>already exists?"}
    E -- "Yes" --> E1["Flash error<br/>redirect /login"]
    E -- "No" --> F["password_hash(PASSWORD_DEFAULT)"]
    F --> G["UserModel::create(role = volunteer)<br/>INSERT users"]
    G --> H["UserModel::findById"]
    H --> I["Auth::login<br/>session_regenerate_id(true)<br/>store id, name, role"]
    I --> J["Flash success<br/>redirect /opportunities"]
```

Rules applied:

| Field | Rule |
|---|---|
| `first_name` + `last_name` | Joined and trimmed; must not be empty |
| `email` | Trimmed, lowercased, and validated with `filter_var(..., FILTER_VALIDATE_EMAIL)`; must be unique (checked in code and by the `UNIQUE` index) |
| `password` | Minimum 8 characters containing both letters and numbers; must match `password_confirmation`; stored only as a bcrypt hash |
| `role` | Forced to `volunteer` for this path; the client cannot create an admin |

## 3. Organization registration (`POST /org/register`)

Organization registration is a second step for an account that already exists. It requires a logged-in user.

```mermaid
flowchart TD
    A(["Visitor opens /org/register<br/>or /register?role=organization"]) --> B{"Logged in?"}
    B -- "No" --> B1["Show org/guest page<br/>explains: create account or log in first"]
    B -- "Yes" --> C{"User already has<br/>an organization row?"}
    C -- "Yes" --> C1["Flash info<br/>redirect /org/dashboard"]
    C -- "No" --> D["Show org/register form"]
    D --> E(["Submit organization form<br/>POST /org/register, csrf"])
    E --> F{"CSRF valid?"}
    F -- "No" --> F1["419"]
    F -- "Yes" --> G{"Session user present?"}
    G -- "No" --> G1["Flash error<br/>redirect /login"]
    G -- "Yes" --> H{"organization_name, location,<br/>description all non-empty?"}
    H -- "No" --> H1["Flash error<br/>redirect /register?role=organization"]
    H -- "Yes" --> I{"findByUserId<br/>already exists?"}
    I -- "Yes" --> I1["Flash info<br/>redirect /org/dashboard"]
    I -- "No" --> J["Build description:<br/>[Type: ...] [Cause: ...] text"]
    J --> K["OrganizationModel::create<br/>is_approved = 0"]
    K --> L["UserModel::updateRole -> organization"]
    L --> M["Refresh session user"]
    M --> N["Flash success: awaiting administrator approval<br/>redirect /org/dashboard"]
```

Effects to be aware of:

- The user's role changes from `volunteer` to `organization` immediately, before approval. From that moment the user can open `/org/*` pages and can no longer use volunteer-only actions, because `requireRole(volunteer)` rejects them.
- `organization_type` and `cause_area` are not separate columns; they are prepended to `organizations.description` as `[Type: ...]` and `[Cause: ...]`.
- Approval state lives in `organizations.is_approved` (0 until an administrator approves).

## 4. Login (`POST /login`)

```mermaid
flowchart TD
    A(["Submit login form<br/>email, password, csrf"]) --> B{"CSRF valid?"}
    B -- "No" --> B1["419"]
    B -- "Yes" --> C["trim email"]
    C --> D["UserModel::findByEmail"]
    D --> E{"user found<br/>AND password_verify OK?"}
    E -- "No" --> E1["Flash: Invalid email or password<br/>redirect /login"]
    E -- "Yes" --> F{"users.is_active = 1?"}
    F -- "No" --> F1["Flash: account deactivated<br/>redirect /login"]
    F -- "Yes" --> G["Auth::login<br/>session_regenerate_id(true)<br/>session user = id, name, role"]
    G --> H["Flash: Welcome back"]
    H --> I{"role"}
    I -- "organization" --> I1["redirect /org/dashboard"]
    I -- "admin" --> I2["redirect /admin/dashboard"]
    I -- "volunteer" --> I3["redirect /volunteer/dashboard"]
```

Design notes:

- A single generic message is shown for an unknown email and for a wrong password, so the form does not reveal which emails exist.
- The active check runs after the password check, so a deactivated user sees the deactivation message only with the correct password.
- `GET /login` redirects to `/` when a session already exists.

## 5. Logout (`POST /logout`)

```mermaid
flowchart LR
    A(["POST /logout + csrf"]) --> B["Auth::logout<br/>clear $_SESSION<br/>session_destroy"]
    B --> C["redirect /"]
```

Logout is a POST so that a link on another site cannot log a user out.

## 6. Session lifecycle and access checks

```mermaid
sequenceDiagram
    participant B as Browser
    participant S as Session store
    participant C as Controller

    B->>C: POST /login (valid credentials)
    C->>S: session_regenerate_id(true)
    C->>S: set user = id, name, role
    C-->>B: Set-Cookie (HttpOnly, SameSite=Lax) + redirect
    B->>C: GET /volunteer/dashboard (cookie)
    C->>S: read user
    alt no user
        C-->>B: redirect /login
    else role not allowed
        C-->>B: 403 Forbidden
    else allowed
        C-->>B: page
    end
    B->>C: POST /logout
    C->>S: clear and destroy
    C-->>B: redirect /
```

| Helper | Behavior |
|---|---|
| `Auth::boot()` | Sets cookie params (HttpOnly, SameSite=Lax), starts the session, and re-verifies session user against DB; called once in `public/index.php` |
| `Auth::checkSessionUser()` | Re-reads user row from database; immediately logs out deactivated users (`is_active = 0`) and synchronizes role and name updates (G-23) |
| `Auth::login($user)` | Regenerates the session id and stores `id`, `name`, `role` |
| `Auth::user()` / `Auth::id()` | Read the session user |
| `Auth::requireLogin()` | Re-verifies user and redirects guests or deactivated users to `/login` |
| `Auth::requireRole(...$roles)` | Calls `requireLogin`, then returns 403 if the role is not in the list |
| `Auth::logout()` | Clears and destroys the session |

## 7. Role and landing summary

| Role | Can register via | Lands on after login | Typical area |
|---|---|---|---|
| Volunteer | `/register` | `/volunteer/dashboard` | `/volunteer/*`, `/opportunities`, `/notifications` |
| Organization | `/org/register` after logging in | `/org/dashboard` | `/org/*`, `/notifications` |
| Admin | database only | `/admin/dashboard` | `/admin/*`, `/notifications` |
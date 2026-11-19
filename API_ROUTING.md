# HelpHive - API Routing

Generated from `config/routes.php` (snapshot 2026-11-13). If the route table changes, regenerate this file.

## Conventions

- Route entries are `[HTTP method, URL pattern, 'Controller@action']`.
- `{id}` matches digits only and is passed to the action as a named integer argument. Every action with a URL parameter declares `int $id`.
- All `POST` routes require a valid CSRF token (`Csrf::verify()` in `public/index.php`, field name `csrf`). Failure returns 419.
- Authentication and role checks are enforced inside each controller action with `Auth::requireLogin()` or `Auth::requireRole()`. The access column below documents that enforcement; it is not performed by the router.
- Access legend: G = guest, V = volunteer, O = organization, A = administrator, AUTH = any authenticated user, (owner) = also checked against record ownership.
- Failure responses: guest on a protected route redirects to `/login`; wrong role returns 403; unknown route returns 404.

## Meaning of `{id}` per route

| Route family | `{id}` refers to |
|---|---|
| `/opportunities/{id}`, `/opportunities/{id}/register` | `opportunities.id` |
| `/registrations/{id}/cancel`, `/volunteer/hours/{id}/claim`, `/org/attendance/{id}`, `/org/hours/{id}/verify`, `/org/hours/{id}/reject` | `registrations.id` |
| `/org/opportunities/{id}/...` | `opportunities.id` |
| `/notifications/{id}/read` | `notifications.id` |
| `/admin/users/{id}/toggle` | `users.id` |
| `/admin/organizations/{id}/...` | `organizations.id` |

## Public and authentication

| Method | Path | Controller action | Access |
|---|---|---|---|
| GET | `/` | `HomeController@index` | G |
| GET | `/register` | `AuthController@showRegister` | G |
| POST | `/register` | `AuthController@register` | G |
| GET | `/org/register` | `AuthController@showRegisterOrg` | G, AUTH |
| POST | `/org/register` | `AuthController@registerOrg` | AUTH |
| GET | `/login` | `AuthController@showLogin` | G |
| POST | `/login` | `AuthController@login` | G |
| POST | `/logout` | `AuthController@logout` | AUTH |

## Opportunity discovery

| Method | Path | Controller action | Access |
|---|---|---|---|
| GET | `/opportunities` | `OpportunityController@index` | G, V, O |
| GET | `/opportunities/{id}` | `OpportunityController@show` | G, V, O |
| GET | `/opportunities/{id}/register` | `RegistrationController@showRegister` | V, O |
| POST | `/opportunities/{id}/save` | `VolunteerController@saveOpportunity` | V, O |
| POST | `/opportunities/{id}/unsave` | `VolunteerController@unsaveOpportunity` | V, O |

## Volunteer & Community

| Method | Path | Controller action | Access |
|---|---|---|---|
| POST | `/opportunities/{id}/register` | `RegistrationController@store` | V, O |
| POST | `/registrations/{id}/cancel` | `RegistrationController@cancel` | V, O (owner) |
| GET | `/volunteer/dashboard` | `VolunteerController@dashboard` | V, O |
| GET | `/volunteer/registrations` | `VolunteerController@registrations` | V, O |
| GET | `/volunteer/saved` | `VolunteerController@saved` | V, O |
| GET | `/volunteer/hours` | `VolunteerController@hours` | V, O (verified and pending shown separately) |
| POST | `/volunteer/hours/{id}/claim` | `VolunteerController@claimHours` | V, O (owner of registration {id}) |

## Organization

| Method | Path | Controller action | Access |
|---|---|---|---|
| GET | `/org/dashboard` | `OrgDashboardController@index` | O |
| GET | `/org/profile` | `OrgProfileController@edit` | O |
| POST | `/org/profile` | `OrgProfileController@update` | O |
| GET | `/org/opportunities` | `OrgOpportunityController@index` | O |
| GET | `/org/opportunities/create` | `OrgOpportunityController@create` | O |
| POST | `/org/opportunities/create` | `OrgOpportunityController@store` | O |
| GET | `/org/opportunities/{id}/edit` | `OrgOpportunityController@edit` | O (owner) |
| POST | `/org/opportunities/{id}/edit` | `OrgOpportunityController@update` | O (owner) |
| POST | `/org/opportunities/{id}/publish` | `OrgOpportunityController@publish` | O (owner) |
| POST | `/org/opportunities/{id}/cancel` | `OrgOpportunityController@cancel` | O (owner) |
| POST | `/org/opportunities/{id}/delete` | `OrgOpportunityController@delete` | O (owner, drafts only) |
| GET | `/org/opportunities/{id}/attendance` | `AttendanceController@index` | O (owner) |
| POST | `/org/attendance/{id}` | `AttendanceController@mark` | O (owner of registration {id}'s opportunity) |
| POST | `/org/hours/{id}/verify` | `AttendanceController@verify` | O (owner), requires attendance = present |
| POST | `/org/hours/{id}/reject` | `AttendanceController@reject` | O (owner) |
| GET | `/org/reports` | `ReportController@index` | O |
| GET | `/org/reports/export/csv` | `ReportController@csv` | O |
| GET | `/org/reports/export/pdf` | `ReportController@pdf` | O |

## Notifications

| Method | Path | Controller action | Access |
|---|---|---|---|
| GET | `/notifications` | `NotificationController@index` | AUTH |
| POST | `/notifications/{id}/read` | `NotificationController@read` | AUTH (owner) |
| POST | `/notifications/read-all` | `NotificationController@readAll` | AUTH |

## Administrator

| Method | Path | Controller action | Access |
|---|---|---|---|
| GET | `/admin/dashboard` | `AdminController@dashboard` | A |
| GET | `/admin/users` | `AdminController@users` | A |
| POST | `/admin/users/{id}/toggle` | `AdminController@toggleUser` | A |
| GET | `/admin/organizations` | `AdminController@organizations` | A |
| POST | `/admin/organizations/{id}/approve` | `AdminController@approveOrg` | A |
| POST | `/admin/organizations/{id}/reject` | `AdminController@rejectOrg` | A |

## Route to view map

| Route | View |
|---|---|
| `GET /` | `home/index` |
| `GET /login` | Redirects to `/?modal=login` (modal-based authentication) |
| `GET /register` | With `?role=organization`: `org/guest` (guest) or `org/register` (logged in); volunteer sign-up redirects to `/?modal=signup` (modal-based) |
| `GET /opportunities` | `opportunities/index` |
| `GET /opportunities/{id}` | `opportunities/show` |
| `GET /opportunities/{id}/register` | `opportunities/register` |
| `POST /opportunities/{id}/register` | `opportunities/confirmed` on success |
| `GET /volunteer/dashboard` | `volunteer/dashboard` |
| `GET /volunteer/registrations` | `volunteer/registrations` |
| `GET /volunteer/saved` | `volunteer/saved` |
| `GET /volunteer/hours` | `volunteer/hours` |
| `GET /org/dashboard` | `org/dashboard` |
| `GET /org/profile` | `org/register` (profile mode) |
| `GET /org/opportunities` | `org/opportunities` |
| `GET /org/opportunities/create` | `org/create_opportunity` |
| `GET /org/opportunities/{id}/edit` | `org/edit_opportunity` |
| `GET /org/opportunities/{id}/attendance` | `org/attendance` |
| `GET /org/reports` | `org/reports` |
| `GET /notifications` | `notifications/index` |
| `GET /admin/dashboard` | `admin/dashboard` |
| `GET /admin/users` | `admin/users` |
| `GET /admin/organizations` | `admin/organizations` |

## Notes

- `GET /register?role=organization` and `GET /org/register` are equivalent; both call `AuthController::showRegister`.
- `POST /org/register` sets `role = organization` and then runs the same `register()` method as `POST /register`.
- `/volunteer/saved` renders bookmarked opportunities persisted in the `saved_opportunities` database table (G-17).
- The view `opportunities/register` is rendered by `GET /opportunities/{id}/register` (G-20).
- Users with the `organization` role hold dual capacity and can access `/volunteer/*` routes as well as save and register for community opportunities outside their own organization (G-19).
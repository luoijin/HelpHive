# HelpHive - Known Gaps

Differences between the intended design (project rules, requirements) and the code in the 2026-11-13 snapshot, found while writing the flow documents. Priorities:

- P1: breaks a hard rule, exposes data, or crashes a feature
- P2: incorrect or incomplete behavior users will notice
- P3: cleanup and hardening

Update this file as items are fixed, and move the fix into `CHANGELOG.md`.

## P1 - Fix first

All P1 items have been addressed and moved to the Resolved section below.

## Resolved

| ID | Area | Previous behavior | Resolution |
|---|---|---|---|
| G-01 | Visibility | `OpportunityModel::find()` had no status filter, so `GET /opportunities/{id}` showed draft and cancelled opportunities to anyone who knew the id | Added `findPublished($id)` for the public detail route (`OpportunityController::show`); kept `find()` for owner pages that check `organization_id` |
| G-02 | Approval | `OrgOpportunityController` never checked `organizations.is_approved`, so unapproved organizations could publish | In `store` and `update`, forced `status = 'draft'` with flash message when `is_approved = 0`; in `publish`, blocked publishing with flash error |
| G-03 | Exports | `ReportController::pdf` called `PdfExporter::download($rows, $filename)`, but the method expected `($rows, $orgName, $filename)` throwing an argument error | Loaded the organization name and passed `download($rows, (string) $org['name'], $filename)` |
| G-04 | Session | `AuthController::register` (organization path) set `$_SESSION['user']` to full `users` row, leaking `password_hash` and causing name fallback to "Volunteer" | After `updateRole`, refreshed session via `Auth::login($userModel->findById($id))` so session contains only `id`, `name`, and `role` |
| G-05 | Hour integrity | `HourModel::markAttendance` had no state guard; marking Absent after verification left `verified` hours intact | `HourModel::markAttendance` restricts updates to `verification_status <> 'verified'`, blocking attendance changes once hours are verified |
| G-06 | Hour integrity | `HourModel::reject` could flip a `verified` record to `rejected` with no audit trail | `HourModel::reject` restricted to `verification_status IN ('unverified', 'pending')` |
| G-07 | Hour integrity | `claimHours` did not check registration status, event start time, or absence | `HourModel::claimHours` joins `opportunities` and requires `r.status = 'registered'`, `o.start_at <= NOW()`, and `ah.attendance_status <> 'absent'` |
| G-08 | Hour integrity | Verified totals and report queries did not filter `registrations.status`, counting cancelled registrations | Added `AND r.status = 'registered'` to `verifiedTotalForVolunteer`, `pendingTotalForVolunteer`, `platformTotalVerified`, `verifiedTotalForOrg`, and `reportRows`. Also guarded `RegistrationModel::cancel` against cancelling verified records |
| G-09 | Registration | Cancelling then registering again returned `duplicate` ("already registered") because the cancelled row still existed | In `RegistrationModel::register`, if a cancelled registration row exists for the pair, it is re-activated (`status = 'registered'`) and its attendance/hours record is reset, allowing re-registration |
| G-10 | Opportunity input | `store` and `update` only trimmed inputs; constraint violations or invalid dates/capacity raised uncaught `PDOException` | Added comprehensive server-side input validation (`validateOpportunityInput`) for required fields, title length, capacity range, status whitelist, and date constraints (`end > start`, `deadline <= start`); caught `PDOException` with friendly flash errors |
| G-12 | Notifications | `NotificationModel::notify()` had no callers, so `/notifications` was always empty | Wired `NotificationModel::notify()` into registration success (volunteer & org), registration cancellation (volunteer & org), attendance marking (volunteer), hour verification and rejection (volunteer), and organization approval and rejection (organization user) |
| G-13 | Cancel opportunity | Cancelling an opportunity left registrations as `registered` and sent no notification | Implemented atomic `OpportunityModel::cancelOpportunity()` transaction setting opportunity and all active registrations to `cancelled`, and notified all affected registered volunteers |
| G-14 | Search | Date filter was missing from server and client queries | Added `date_from` and `date_to` parameters to `OpportunityModel::allPublished`, `OpportunityController::index`, UI date range picker, and `app.js` filtering pipeline |
| G-15 | Search | Search mixed real and hard-coded sample data; `OpportunityController` fell back to 8 sample opportunities and `app.js` merged a static dataset | Removed the hard-coded sample opportunity fallback lists from `OpportunityController` (`index` and `show`), updated `app.js` `getAllOpportunities()` to consume only `window.SERVER_OPPORTUNITIES`, and added clean empty-state UI for zero search results |
| G-11 | Opportunity edit | Editing was allowed after volunteers registered; capacity could drop below registrations; status could be set to completed/cancelled via form | Enforced capacity constraint (`capacity >= registered_count`), whitelisted edit form statuses to `draft` and `published`, and blocked reverting to draft when volunteers are registered |
| G-16 | Search | Results were not paginated; pagination was static; LIKE wildcards were unescaped | Escaped SQL LIKE wildcards (`%`, `_`, `\`) in keyword and location search queries, added server-side LIMIT/OFFSET pagination in `OpportunityModel` and `OpportunityController`, and implemented dynamic client-side pagination in `app.js` and `index.php` |
| G-17 | Saved | Bookmarks lived only in JS memory; `/volunteer/saved` received an empty list | Created `saved_opportunities` table with composite PK and foreign keys, created `SavedOpportunityModel`, wired `POST /opportunities/{id}/save` and `/unsave` routes, updated `VolunteerController@saved` to query database rows, and synced persistence with client-side UI |
| G-18 | Dashboard | Organization dashboard "volunteers" figure was `opportunity count * 8` | Replaced hardcoded formula with `RegistrationModel::totalRegisteredForOrg($orgId)`, accurately counting active (`status = 'registered'`) registrations |
| G-19 | Roles | Registering an organization caused user to lose access to `/volunteer/*` and their volunteer history | Implemented dual capacity policy allowing organization users to access `/volunteer/*` routes and volunteer for community opportunities outside their own organization, and added volunteer links to their profile dropdown |
| G-20 | Views | `opportunities/register.php` was not rendered by any controller | Wired `opportunities/register.php` (Figma 381:11859) to `GET /opportunities/{id}/register` with capacity, deadline, and ownership checks, pre-populating volunteer account details |
| G-21 | Login | No rate limiting or lockout; no email format validation; email not lowercased before lookup | Added `filter_var` email validation, normalized email to lowercase on create and lookup, and implemented failed-attempt counter per IP and email (5 attempts before 15-minute temporary lockout) in `AuthController::login` |
| G-22 | Password | Only a length of 8 was enforced; no confirmation field check on the server; no reset flow | Enforced password complexity (min 8 characters containing letters and numbers), added confirmation field check on server and forms, and documented password reset as out of scope for current sprint (directing to support) |
| G-23 | Session | Role and `is_active` were read at login and trusted afterwards; a deactivated user kept a working session | Added `Auth::checkSessionUser()` called during `Auth::boot()` and `Auth::requireLogin()` to re-verify `is_active` against database in real time, terminate session for deactivated accounts, and synchronize role changes |
| G-24 | Admin | An administrator could deactivate their own account; rejecting an organization left the user's role as `organization` | Blocked self-deactivation in `AdminController::toggleUser`; in `AdminController::rejectOrg`, reverted user role to `volunteer` with notification, and ensured role synchronization in `approveOrg` |
| G-25 | Redirects | Several actions redirected to `$_SERVER['HTTP_REFERER']`; redirects used unmanaged paths | Replaced all `$_SERVER['HTTP_REFERER']` redirects with deterministic fixed routes (e.g. `/org/opportunities/{id}/attendance`), and created `url()` and `redirect()` helpers supporting configurable base paths |
| G-26 | Errors | Uncaught exceptions were not handled; behavior depended on PHP `display_errors` | Registered global exception handler in `public/index.php` that logs errors, sets HTTP 500, and renders a custom styled `app/views/errors/500.php` page with stack trace details displayed only in local environment (`APP_ENV=local`) |
| G-27 | Repository | `.gitignore` was empty | Configured `.gitignore` to exclude `.env`, `.env.*`, `/vendor/`, IDE/editor files, caches, and logs while preserving `.env.example` |
| G-28 | Attendance | An invalid `attendance_status` value was silently changed to `present` | In `AttendanceController::mark`, rejected invalid attendance status values with an error flash message instead of silently coercing to `present` |
| G-29 | Verify | `verify` accepted any positive hours with no relation to claimed hours or event length | Enforced `0 < hours_verified <= 24` in `AttendanceController::verify` and added an informational note when verified hours differ from volunteer claimed hours |
| G-30 | Messages | Claim error text said "between 0.5 and 24" but the code accepted any value above 0 up to 24 | Enforced `$hours >= 0.5 && $hours <= 24` in `VolunteerController::claimHours`, aligning server validation rule with view input constraints and error message |

## P2 - Correct behavior

All P2 items have been addressed and moved to the Resolved section below.

## P3 - Hardening

All P3 items have been addressed and moved to the Resolved section above.

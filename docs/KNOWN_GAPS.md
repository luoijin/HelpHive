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

## P2 - Correct behavior

| ID | Area | Current behavior | Fix |
|---|---|---|---|
| G-11 | Opportunity edit | Editing is allowed after volunteers registered; capacity can drop below current registrations; `status` can be set to `completed` or `cancelled` through the form | Reject capacity below the registered count; accept only whitelisted status transitions |
| G-16 | Search | Results are not paginated; the pagination control is static; `LIKE` wildcards are unescaped; FULLTEXT index unused | Add `LIMIT/OFFSET`, escape `%` and `_`, optionally switch the keyword to `MATCH ... AGAINST` |
| G-17 | Saved | Bookmarks live only in JavaScript memory; `/volunteer/saved` always receives an empty list | Create `saved_opportunities (user_id, opportunity_id, created_at)` with a composite primary key and two routes (save, unsave) |
| G-18 | Dashboard | Organization dashboard "volunteers" figure is `opportunity count * 8` | Replace with a `COUNT` of `registered` registrations for the organization's opportunities |
| G-19 | Roles | Registering an organization changes the user's role from `volunteer` to `organization`; the person loses access to `/volunteer/*` and their existing registrations and hours | Decide the policy: separate accounts for organizations, or allow a user to hold both capacities |
| G-20 | Views | `opportunities/register.php` is not rendered by any controller | Wire it to a `GET /opportunities/{id}/register` route or remove it |

## P3 - Hardening

| ID | Area | Current behavior | Fix |
|---|---|---|---|
| G-21 | Login | No rate limiting or lockout; no email format validation; email not lowercased before lookup | `filter_var($email, FILTER_VALIDATE_EMAIL)`, store and compare lowercase, add a failed-attempt counter per IP and email |
| G-22 | Password | Only a length of 8 is enforced; no confirmation field check on the server; no reset flow | Add a confirmation match and basic complexity guidance; plan a password reset or document it as out of scope |
| G-23 | Session | Role and `is_active` are read at login and trusted afterwards; a deactivated user keeps a working session | Re-read the user row in `Auth::boot()` (or on each protected action) and log out inactive users |
| G-24 | Admin | An administrator can deactivate their own account; rejecting an organization leaves the user's role as `organization` | Block self-deactivation; decide what happens to a rejected organization's role |
| G-25 | Redirects | Several actions redirect to `$_SERVER['HTTP_REFERER']`; redirects use absolute paths such as `/login` | Redirect to fixed routes (for example `/org/opportunities/{id}/attendance`); build URLs through a helper if the app will ever run in a subfolder |
| G-26 | Errors | Uncaught exceptions are not handled; behavior depends on PHP `display_errors` | Add a global exception handler that logs the error and renders a generic 500 page; show details only when `APP_ENV=local` |
| G-27 | Repository | `.gitignore` is empty | Add `.env`, `vendor/`, and editor folders; keep `.env.example` tracked |
| G-28 | Attendance | An invalid `attendance_status` value is silently changed to `present` | Reject invalid values with an error instead of coercing |
| G-29 | Verify | `verify` accepts any positive hours (up to the column limit) with no relation to claimed hours or event length | Require `hours_verified <= 24`, and optionally warn when it differs from `hours_claimed` |
| G-30 | Messages | Claim error text says "between 0.5 and 24" but the code accepts any value above 0 up to 24 | Make the rule and the message agree |

## Suggested fix order

1. G-01, G-02, G-03, G-04 (visibility, approval, PDF export, session)
2. G-05, G-06, G-07, G-08 (hour-integrity rule)
3. G-09, G-10, G-11 (registration and opportunity correctness)
4. G-12, G-13 (notifications and cancellation)
5. G-14 to G-17 (search and saved)
6. Remaining P2 and P3 items
# HelpHive - Known Gaps

Differences between the intended design (project rules, requirements) and the code in the 2026-11-13 snapshot, found while writing the flow documents. Priorities:

- P1: breaks a hard rule, exposes data, or crashes a feature
- P2: incorrect or incomplete behavior users will notice
- P3: cleanup and hardening

Update this file as items are fixed, and move the fix into `CHANGELOG.md`.

## P1 - Fix first

| ID | Area | Current behavior | Fix |
|---|---|---|---|
| G-01 | Visibility | `OpportunityModel::find()` has no status filter, so `GET /opportunities/{id}` shows draft and cancelled opportunities to anyone who knows the id | Add `findPublished($id)` for the public detail route; keep `find()` for owner pages that already check `organization_id` |
| G-02 | Approval | `OrgOpportunityController` never checks `organizations.is_approved`, so unapproved organizations can publish | In `store`, `update`, `publish`: if `is_approved = 0`, force `draft` or block with a flash message |
| G-03 | Exports | `ReportController::pdf` calls `PdfExporter::download($rows, $filename)`, but the method is `download(array $rows, string $orgName, string $filename)`; the PDF route throws an argument error | Load the organization name and call `download($rows, $org['name'], $filename)` |
| G-04 | Session | `AuthController::register` (organization path) sets `$_SESSION['user']` to the full `users` row. That stores `password_hash` in the session and uses `full_name`, while the header reads `name`, so the display name falls back to "Volunteer" | After `updateRole`, call `Auth::login($userModel->findById($id))` so the session has `id`, `name`, `role` only |
| G-05 | Hour integrity | `HourModel::markAttendance` has no state guard: marking a volunteer Absent after verification leaves `verified` hours in place | Allow attendance changes only while `verification_status <> 'verified'`, or reset the hours record to `unverified` in the same statement |
| G-06 | Hour integrity | `HourModel::reject` can turn a `verified` record into `rejected` with no audit trail | Restrict to `unverified` and `pending`, or add an explicit "revoke" action that logs who did it |
| G-07 | Hour integrity | `claimHours` does not check that the registration is `registered`, that the event has started, or that attendance is not `absent` | Join `registrations` and require `r.status = 'registered'` and `o.start_at <= NOW()` |
| G-08 | Hour integrity | Verified totals (`verifiedTotalForVolunteer`, `verifiedTotalForOrg`, `reportRows`) do not filter `registrations.status`; a registration cancelled after verification still counts | Add `AND r.status = 'registered'` to those queries, or block cancelling once hours are verified |

## P2 - Correct behavior

| ID | Area | Current behavior | Fix |
|---|---|---|---|
| G-09 | Registration | Cancelling then registering again returns `duplicate` ("already registered") because the cancelled row still exists | In `RegistrationModel::register`, after the capacity check, if a `cancelled` row exists for the pair, `UPDATE ... SET status = 'registered'` instead of inserting |
| G-10 | Opportunity input | `store` and `update` only `trim`; missing values become defaults (dates default to now, capacity 20). Constraint violations (`chk_opp_dates`, `chk_opp_capacity`) or an invalid `status` raise uncaught `PDOException` | Add validation (required fields, `end > start`, `deadline <= start`, capacity range, status whitelist of `draft`/`published`) and catch `PDOException` with a flash error |
| G-11 | Opportunity edit | Editing is allowed after volunteers registered; capacity can drop below current registrations; `status` can be set to `completed` or `cancelled` through the form | Reject capacity below the registered count; accept only whitelisted status transitions |
| G-12 | Notifications | `NotificationModel::notify()` has no callers, so `/notifications` is always empty | Call `notify` on: registration success (volunteer and organization), registration cancelled, attendance marked, hours verified or rejected, organization approved or rejected, opportunity cancelled |
| G-13 | Cancel opportunity | Cancelling an opportunity leaves volunteer registrations as `registered` and sends no message | In one transaction set the opportunity to `cancelled`, set its registrations to `cancelled`, and notify volunteers |
| G-14 | Search | Date filter is missing; `distance` is not applied by SQL; filters for type, good for, duration, and skills have no database columns | Add `date_from` and `date_to` parameters; decide whether to add columns (`location_type`, `duration`, `skills`) or remove the controls |
| G-15 | Search | `OpportunityController` falls back to 8 hard-coded sample opportunities when the query returns zero rows or fails, and `app.js` merges a static `OPPORTUNITIES_DATA` array into the client list | Remove both fallbacks once seed data is loaded; show an empty-state message; log DB errors |
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
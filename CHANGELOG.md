# Changelog

## [Unreleased]

### Fixed
- **G-03 (Exports)**: Fixed argument count mismatch in `ReportController::pdf` by loading organization details and passing `$org['name']` to `PdfExporter::download($rows, $orgName, $filename)`.
- **G-01 (Visibility)**: Added `OpportunityModel::findPublished($id)` to restrict public `GET /opportunities/{id}` views to opportunities with status `published`, keeping drafts and cancelled opportunities inaccessible by direct URL while preserving `find()` for owner routes.
- **G-02 (Approval)**: Enforced organization approval checks in `OrgOpportunityController`. In `store` and `update`, unapproved organizations have opportunities forced to `draft` with an informational flash message; in `publish`, publishing is blocked with an error flash until administrator approval.
- **G-04 (Session)**: Fixed session corruption upon organization registration in `AuthController::register` by calling `Auth::login($userModel->findById($id))` after `updateRole`, ensuring the session stores sanitized `id`, `name`, and `role` attributes and header displays the user's name properly.
- **G-05 (Hour integrity)**: Added verification state check in `HourModel::markAttendance` restricting modifications to `verification_status <> 'verified'`, preventing verified hours from being compromised if attendance is changed.
- **G-06 (Hour integrity)**: Guarded `HourModel::reject` to only accept unverified or pending hours (`verification_status IN ('unverified', 'pending')`), preventing arbitrary modification of already verified records without an audit trail.
- **G-07 (Hour integrity)**: Hardened `HourModel::claimHours` by joining `opportunities` and enforcing `r.status = 'registered'`, `o.start_at <= NOW()`, and `ah.attendance_status <> 'absent'`, preventing volunteers from claiming hours on cancelled registrations, future events, or when marked absent.
- **G-08 (Hour integrity)**: Filtered verified totals and reports with `AND r.status = 'registered'` across `verifiedTotalForVolunteer`, `pendingTotalForVolunteer`, `platformTotalVerified`, `verifiedTotalForOrg`, and `reportRows`. Also guarded `RegistrationModel::cancel` to disallow cancellation once hours are verified.
- **G-09 (Re-registration)**: Fixed re-registration failure in `RegistrationModel::register` by checking for existing cancelled registrations and re-activating them (`status = 'registered'`) while resetting the attendance and hours record to default unverified state.
- **G-12 (Notifications)**: Wired in-app alerts via `NotificationModel::notify()` for registration confirmations (volunteer & organization), registration cancellations (volunteer & organization), attendance marked (volunteer), hours verified and rejected (volunteer), and organization approval and rejection (organization user).
- **G-13 (Opportunity Cancellation)**: Created atomic `OpportunityModel::cancelOpportunity` transaction that sets the opportunity status to `cancelled`, cascades cancellation to active registrations, and notifies all affected volunteers.
- **G-10 (Opportunity Input Validation)**: Implemented server-side validation in `OrgOpportunityController` (`store` and `update`) validating required fields, title length (3-180), capacity range (>= 1), status whitelist, and date constraints (`end_at > start_at`, `registration_deadline <= start_at`), catching `PDOException` with friendly flash errors.
- **G-14 (Search Date Filter)**: Added `date_from` and `date_to` parameters to `OpportunityModel::allPublished`, `OpportunityController::index`, Search Opportunities UI (`search-date-from` and `search-date-to`), and client-side filtering in `app.js`.
- **G-15 (Search Sample Data Fallbacks)**: Eliminated hard-coded 8 sample opportunities fallbacks from `OpportunityController` (`index` and `show`), updated `app.js` `getAllOpportunities()` to strictly load server data from `window.SERVER_OPPORTUNITIES`, and added clean empty-state UI for zero search results.
- **G-11 (Opportunity Edit Constraints)**: Enforced strict validation in `OrgOpportunityController` preventing volunteer capacity from being reduced below the count of currently registered volunteers, disallowing status transitions to `cancelled` or `completed` via the edit form (directing organizers to the atomic Cancel action), and blocking reversion of published opportunities to `draft` when volunteers are already registered.
- **G-16 (Search Pagination & Wildcard Escaping)**: Escaped SQL `LIKE` wildcards (`%`, `_`, `\`) in keyword and location search queries across `OpportunityModel::allPublished` and `countPublished`, resolved named parameter repetition in prepared statements (`:kw1`, `:kw2`), added server-side `LIMIT`/`OFFSET` pagination, and implemented interactive client-side pagination in `app.js` and `index.php`.
- **G-17 (Saved Opportunities Persistence)**: Created `saved_opportunities` database table with foreign keys and composite primary key `(user_id, opportunity_id)`, implemented `SavedOpportunityModel`, wired `POST /opportunities/{id}/save` and `/unsave` routes, updated `VolunteerController@saved` to query database rows, and synced persistence with client-side UI bookmark toggles and pre-loaded states.
- **G-18 (Organization Dashboard Volunteer Count)**: Replaced hardcoded formula `opportunity count * 8` in `OrgDashboardController` with actual count of active registrations (`status = 'registered'`) across all opportunities owned by the organization via `RegistrationModel::totalRegisteredForOrg()`.
- **G-19 (Roles & Dual Capacity)**: Implemented dual capacity policy allowing organization users to retain full access to their volunteer dashboard, service hours, applications, and saved opportunities (`/volunteer/*`), while allowing them to volunteer for community opportunities outside their own organization, adding volunteer links to their profile dropdown navigation.
- **G-20 (Volunteer Registration Form View)**: Wired `opportunities/register.php` (Figma 381:11859) to `GET /opportunities/{id}/register` with capacity, deadline, and ownership checks, pre-populating volunteer account details and providing a complete structured registration experience.
- **G-21 (Login Security & Rate Limiting)**: Added `filter_var` email format validation, normalized email addresses to lowercase on account registration and user lookup, and implemented a failed-login attempt counter per IP address and email with temporary 15-minute account lockouts after 5 consecutive failures.
- **G-22 (Password Complexity & Confirmation)**: Enforced password complexity requirements (minimum 8 characters, containing both letters and digits), added server-side password confirmation matching check on registration, added confirmation inputs to registration page and sign up modal, and documented password reset workflow as out of scope for the current sprint with support assistance guidance.
- **G-23 (Session Integrity & Real-Time Deactivation)**: Added `Auth::checkSessionUser()` called during session bootstrap (`Auth::boot()`) and protected request authorization (`Auth::requireLogin()`), re-verifying `is_active` against the database to instantly terminate sessions for deactivated accounts and automatically synchronize role or profile changes without requiring re-login.
- **G-24 (Admin Account Safety & Organization Rejection Role)**: Prevented self-deactivation of logged-in administrator accounts in `AdminController::toggleUser()`, and configured `AdminController::rejectOrg()` to automatically revert the organization user's role to `volunteer` while dispatching in-app notification alerts.
- **G-25 (Deterministic Route Redirects & URL Helper)**: Replaced unpredictable `$_SERVER['HTTP_REFERER']` redirects across attendance, bookmarking, and opportunity management with deterministic fixed application routes (e.g. `/org/opportunities/{id}/attendance`), and implemented `url()` and `redirect()` helpers supporting configurable subfolders and base paths.
- **G-26 (Global Exception Handling & Error Pages)**: Implemented a global exception handler in `public/index.php` that logs uncaught exceptions to system logs, sets HTTP 500 response codes, and renders a custom styled error template (`app/views/errors/500.php`), exposing detailed error traces only in local environments (`APP_ENV=local`).
- **G-27 (Repository Hardening & Git Ignore)**: Configured comprehensive `.gitignore` rules excluding secrets (`.env`), dependencies (`/vendor/`), IDE configurations, caches, and temporary logs while preserving `.env.example`.
- **G-28 (Attendance Input Validation)**: Replaced silent fallback coercion in `AttendanceController::mark()` with strict input validation, returning a user-friendly error flash when an invalid attendance status is supplied.
- **G-29 (Verified Hours Bounds & Discrepancy Warnings)**: Enforced a 24-hour upper bound on hour verification (`0 < hours_verified <= 24`) in `AttendanceController::verify()`, and added an informational note when verified hours differ from volunteer claimed hours.
- **G-30 (Hour Claim Range & Message Consistency)**: Aligned the server-side validation rule (`0.5 <= hours <= 24`) in `VolunteerController::claimHours()` with the client form constraints and error flash message.

### Documentation
- Rewrote `ARCHITECTURE.md` to match the current codebase: layers, request lifecycle, roles, invariants and where they are enforced, security controls, frontend structure, repository layout, document map.
- Regenerated `API_ROUTING.md` from `config/routes.php` (44 routes, access levels, `{id}` meaning per route, route to view map).
- Updated `DATABASE_SCHEMA.md`: relationship overview, indexing strategy, query rules for hour integrity, transactions, seed data, planned schema changes.
- Added `docs/ERD.md` (full ERD with attributes, relationship table, four state machines, read/write map).
- Added flow documents: `docs/FLOW_AUTH.md`, `docs/FLOW_VALIDATION.md`, `docs/FLOW_SEARCH.md`, `docs/FLOW_REGISTRATION_AND_HOURS.md`, `docs/FLOW_ORGANIZATION_AND_ADMIN.md`.
- Added `docs/KNOWN_GAPS.md` listing 30 differences between intended and current behavior, with priorities and fixes.

### Added
- Direct page view redirection on clicking opportunity cards to `/opportunities/{id}` for complete event details.
- Added "Duration" info box to the Opportunity Details view (`show.php`) and controllers.
- Prominent and functional "Reset All Filters" control on Search Opportunities, restoring default states across keyword, location, distance, and all category chips.
- Philippine City / Province filter dropdown on Search Opportunities covering major NCR, Visayas, Mindanao, and Luzon regions with live autocomplete filtering and default "Any" selection.
- Distance radius filter on Search Opportunities with localized proximity clustering (5 km, 10 km, 15 km, 25 km, 50 km, and provincial radius).
- Search button event handling with dropdown dismissal, real-time filtering, URL sync, and smooth scrolling to results.
- Custom-designed, branded scrollbar UI replacing all default operating system scrollbars globally.
- Custom interactive dropdown UI for the "I want to join as" role selection on Sign Up modal and register views with role icons, titles, and descriptions.

### Changed
- Removed standalone login and register pages (`app/views/auth/login.php` and `app/views/auth/register.php`) in favor of a unified modal-based authentication architecture. Fixed authentication and validation failure handling to retain users on their current page with the modal automatically reopened and flash errors displayed.
- Opportunity cards now remain completely static in position on hover (removed physical movement / translate animation).
- Aligned the divider line below the HelpHive logo on Login and Sign Up modals to span the full width of the form content for a clean, professional aesthetic.
- Login and Sign Up buttons now maintain static dimensions on hover without any scaling or enlargement.
- Integrated Figma prototype design into modular PHP architecture (`app/views/`):
  - Shared layout modules: `layouts/header.php`, `layouts/footer.php`, `layouts/modals.php`, `layouts/main.php`.
  - Public views: `home/index.php`, `opportunities/index.php`, `opportunities/show.php`.
  - Volunteer modules: `opportunities/register.php`, `opportunities/confirmed.php`, `volunteer/dashboard.php`, `volunteer/registrations.php`, `volunteer/saved.php`, `volunteer/hours.php`.
  - Organization modules: `org/register.php`, `org/dashboard.php`, `org/opportunities.php`, `org/create_opportunity.php`, `org/edit_opportunity.php`, `org/attendance.php`, `org/reports.php`.
  - Administration modules: `admin/dashboard.php`, `admin/users.php`, `admin/organizations.php`.
  - Notification module: `notifications/index.php`.
  - Authentication modules: `auth/login.php`, `auth/register.php`.
- Asset pipeline migration:
  - Migrated `style.css` and `app.js` into `public/assets/css/` and `public/assets/js/`.
  - Synced all Figma icons, images, and brand assets to `public/assets/`.
- MVC Controllers & Models:
  - Implemented `HomeController`, `AuthController`, `OpportunityController`, `RegistrationController`, `VolunteerController`, `OrgDashboardController`, `OrgProfileController`, `OrgOpportunityController`, `AttendanceController`, `ReportController`, `NotificationController`, `AdminController`.
  - Implemented `UserModel`, `OrganizationModel`, `OpportunityModel`, `NotificationModel`, enhanced `HourModel` and `RegistrationModel`.
- Utilities & Database:
  - Implemented `Flash.php` for one-time notification flashes.
  - Implemented `view()`, `render()`, and `asset()` in `app/utils/helpers.php`.
  - Seed data in `database/seed.sql` matching Figma prototype scenarios.
  - Complete documentation in `DATABASE_SCHEMA.md`.
# Changelog

## [Unreleased]
### Added
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

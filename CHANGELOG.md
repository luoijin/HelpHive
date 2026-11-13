# Changelog

## [Unreleased]
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

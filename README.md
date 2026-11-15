# HelpHive

HelpHive is a centralized community volunteering web platform that connects volunteers with local nonprofit organizations. Volunteers browse, register for, and track community service opportunities such as cleanups, donation drives, and outreach programs. Organizations publish opportunities, mark attendance, verify service hours, and export participation reports. Administrators approve organizations and manage accounts.

Project timeline: four Agile Scrum sprints concluding on December 12, 2026.

---

## Table of Contents

1. [Features](#features)
2. [Technology Stack](#technology-stack)
3. [Scope Boundaries](#scope-boundaries)
4. [User Roles](#user-roles)
5. [System Architecture](#system-architecture)
6. [Directory Structure](#directory-structure)
7. [Business Rules](#business-rules)
8. [Security Standards](#security-standards)
9. [Database Overview](#database-overview)
10. [Getting Started](#getting-started)
11. [Configuration](#configuration)
12. [Test Accounts](#test-accounts)
13. [Coding Conventions](#coding-conventions)
14. [Documentation Index](#documentation-index)
15. [Project Status and Roadmap](#project-status-and-roadmap)

---

## Features

- Accounts and authentication: volunteer sign-up, login, logout, session handling, role-based landing pages.
- Organization onboarding: a logged-in user registers an organization, which an administrator approves.
- Opportunity publishing: organizations create, edit, save as draft, publish, cancel, and delete drafts.
- Search and discovery: keyword search plus filters for city or province, distance, cause area, location type, good for, duration, and skills.
- Registration and capacity: volunteers register for open opportunities with capacity limits, registration deadlines, and duplicate prevention.
- Attendance and hour verification: organizations mark attendance (present, absent, excused) and verify or reject claimed hours.
- Reporting and exports: participation reports built only from verified hours, exportable as CSV and PDF.
- In-app notifications: notification list with read and mark-all-read actions.
- Administration: dashboard, user activation, organization approval.

## Technology Stack

| Layer | Technology |
|---|---|
| Frontend | HTML5, CSS3, JavaScript (single stylesheet and single script file) |
| Backend | PHP 8.1+ (vanilla, lightweight MVC) |
| Database | MySQL 8 (InnoDB, utf8mb4) through PDO |
| Web server | Apache or Nginx through Laragon |
| PDF generation | Dompdf (installed with Composer) |
| Design reference | Approved Figma prototype `HelpHive--Copy-`, node `308-39720` |

## Scope Boundaries

Intentionally out of scope:

- Native iOS or Android applications (responsive web only).
- Third-party payment gateways.
- GPS or Google Maps API integration (location filtering is text based).
- Paid third-party SaaS dependencies.

## User Roles

| Role | Capabilities |
|---|---|
| Volunteer | Browse and search, register and cancel, view dashboard, claim hours, view verified and pending hours separately, notifications |
| Organization | Manage organization profile, create, edit, publish, and cancel opportunities, mark attendance, verify or reject hours, reports and exports |
| Administrator | Manage users, approve or reject organizations, view platform totals |

Role enforcement happens on the server in every protected controller action. Hiding a link in the interface is never treated as access control.

---

## System Architecture

HelpHive follows a lightweight MVC pattern with a single front controller (`public/index.php`).

| Layer | Location | Responsibility |
|---|---|---|
| Entry point | `public/` | Front controller and static assets |
| Configuration | `config/` | Environment loading, database settings, route table |
| Controllers | `app/controllers/` | HTTP handling, role and ownership checks, validation |
| Models | `app/models/` | All SQL through PDO prepared statements |
| Views | `app/views/` | PHP templates, output escaped with `e()` |
| Utilities | `app/utils/` | Database wrapper, authentication, CSRF, flash messages, exporters |

Request lifecycle:

```
Browser -> public/index.php (env, autoload, session)
        -> Router (config/routes.php)
        -> CSRF check (POST only)
        -> Controller action (Auth::requireRole, validation)
        -> Model (PDO, transactions)
        -> View, redirect with flash message, or CSV/PDF download
```

The full description, diagrams, and design rules are in `ARCHITECTURE.md`.

## Directory Structure

```
HelpHive/
|-- .env  .env.example  .gitignore  .htaccess
|-- README.md  ARCHITECTURE.md  DATABASE_SCHEMA.md  API_ROUTING.md  CHANGELOG.md
|-- docs/
|   |-- ERD.md
|   |-- FLOW_AUTH.md
|   |-- FLOW_VALIDATION.md
|   |-- FLOW_SEARCH.md
|   |-- FLOW_REGISTRATION_AND_HOURS.md
|   |-- FLOW_ORGANIZATION_AND_ADMIN.md
|   `-- KNOWN_GAPS.md
|-- config/
|   |-- env.php            .env loader and env() helper
|   |-- database.php       Database settings
|   `-- routes.php         Route table
|-- database/
|   |-- schema.sql         Tables, constraints, indexes
|   `-- seed.sql           Sample data
|-- public/                Web document root
|   |-- index.php          Front controller
|   |-- .htaccess          URL rewriting
|   `-- assets/            css/, js/, icons/, images/
`-- app/
    |-- controllers/
    |   |-- HomeController.php
    |   |-- AuthController.php
    |   |-- OpportunityController.php
    |   |-- RegistrationController.php
    |   |-- VolunteerController.php
    |   |-- OrgDashboardController.php
    |   |-- OrgProfileController.php
    |   |-- OrgOpportunityController.php
    |   |-- AttendanceController.php
    |   |-- ReportController.php
    |   |-- NotificationController.php
    |   `-- AdminController.php
    |-- models/
    |   |-- UserModel.php
    |   |-- OrganizationModel.php
    |   |-- OpportunityModel.php
    |   |-- RegistrationModel.php
    |   |-- HourModel.php
    |   `-- NotificationModel.php
    |-- utils/
    |   |-- Database.php   PDO singleton
    |   |-- Auth.php       Session login and role checks
    |   |-- Csrf.php       Token generation and verification
    |   |-- Flash.php      One-time session messages
    |   |-- helpers.php    e(), asset(), view(), render()
    |   `-- exporters/     CsvExporter.php, PdfExporter.php
    `-- views/
        |-- layouts/       main, header, footer, modals
        |-- home/  auth/  opportunities/  notifications/
        |-- volunteer/     dashboard, registrations, saved, hours
        |-- org/           register, guest, dashboard, opportunities, create, edit, attendance, reports
        `-- admin/         dashboard, users, organizations
```

---

## Business Rules

### 1. Service hour integrity

- Unverified hours never merge into verified totals or official participation reports.
- Each attendance record has a `verification_status` of `unverified`, `pending`, `verified`, or `rejected`.
- Volunteers enter `hours_claimed`. Organizations confirm `hours_verified`. Totals and reports sum `hours_verified` only.
- Every total and report query includes `verification_status = 'verified'`.
- A database CHECK constraint prevents a non-verified row from holding verified hours.
- The interface shows verified hours and pending hours as separate figures.

### 2. Role-based access control

- Every protected route verifies the session and the role.
- Ownership is verified separately: an organization acts only on its own opportunities, and a volunteer acts only on their own registrations and hour claims.
- Direct URL manipulation must not grant access to another user's data.

### 3. Registration and capacity integrity

- Capacity is checked inside a transaction that locks the opportunity row (`SELECT ... FOR UPDATE`).
- Registration is rejected after `registration_deadline` and for opportunities that are not published.
- `UNIQUE(opportunity_id, volunteer_id)` is the final guard against duplicate sign-ups.

---

## Security Standards

- SQL injection: PDO with `ERRMODE_EXCEPTION`, `EMULATE_PREPARES = false`, bound parameters only.
- Cross-site scripting: dynamic output escaped with `htmlspecialchars(..., ENT_QUOTES, 'UTF-8')` through `e()`.
- Cross-site request forgery: token in every state-changing POST form, verified before dispatch.
- Passwords: `password_hash(PASSWORD_DEFAULT)` and `password_verify()`.
- Sessions: `session_regenerate_id(true)` on login; HttpOnly and SameSite=Lax cookies.
- CSV export: cells beginning with formula characters are neutralized.
- Exposure: serve `public/` as the document root so `.env`, `app/`, `config/`, and `database/` stay outside the web root.

Open hardening items are listed in `docs/KNOWN_GAPS.md`.

## Database Overview

| Table | Purpose |
|---|---|
| `users` | Accounts for all roles, hashed passwords, active flag |
| `organizations` | Organization profiles, one per user, approval flag |
| `opportunities` | Opportunities with status `draft`, `published`, `cancelled`, `completed` |
| `registrations` | Volunteer sign-ups, unique per opportunity and volunteer |
| `attendance_and_hours` | Attendance, claimed hours, verified hours, verification state |
| `notifications` | In-app alerts per user |

```
users 1 --- 0..1 organizations
organizations 1 --- N opportunities
opportunities 1 --- N registrations N --- 1 users (volunteer)
registrations 1 --- 0..1 attendance_and_hours
users 1 --- N notifications
```

The full ERD, column definitions, and state machines are in `docs/ERD.md` and `DATABASE_SCHEMA.md`.

---

## Getting Started

### Prerequisites

- Laragon (Full edition recommended) with Apache or Nginx, PHP 8.1 or newer, MySQL 8, and HeidiSQL or phpMyAdmin.
- Composer (bundled with Laragon).

### Installation

1. Keep the project at `C:\Github\HelpHive`.
2. Start Laragon with Start All.
3. Create the environment file:
   ```
   cd C:\Github\HelpHive
   copy .env.example .env
   ```
4. Create the database and load the schema, then the seed data:
   ```
   mysql -u root < database\schema.sql
   mysql -u root helphive < database\seed.sql
   ```
5. Install PHP dependencies (required for PDF export):
   ```
   composer install
   ```
6. Serve the project using one of these options:

   Option A - symlink into Laragon's `www` (gives `http://helphive.test`). In an Administrator command prompt:
   ```
   mklink /D C:\laragon\www\helphive C:\Github\HelpHive
   ```
   Reload Apache, then make sure the virtual host document root is `C:\Github\HelpHive\public`.

   Option B - PHP built-in server (no virtual host needed):
   ```
   cd C:\Github\HelpHive
   php -S localhost:8000 -t public public/index.php
   ```
   Then open `http://localhost:8000`.

7. Open the site in a browser and log in with a test account.

### Troubleshooting

- 404 on every route: confirm `public/.htaccess` exists and `mod_rewrite` is enabled, or use the built-in server command above.
- Database connection error: check `.env` values and that MySQL is running in Laragon.
- PDF export fails: run `composer install` and confirm `vendor/autoload.php` exists. See `docs/KNOWN_GAPS.md`, item G-03, for a known argument mismatch in the PDF route.
- Search shows sample opportunities that are not in the database: the controller and `app.js` include built-in sample data (G-15).

## Configuration

Environment values are read from `.env`:

| Key | Description | Default |
|---|---|---|
| `APP_ENV` | Environment name | `local` |
| `DB_HOST` | Database host | `127.0.0.1` |
| `DB_PORT` | Database port | `3306` |
| `DB_NAME` | Database name | `helphive` |
| `DB_USER` | Database user | `root` |
| `DB_PASS` | Database password | empty |

Never commit `.env`. Add `.env` and `vendor/` to `.gitignore`.

## Test Accounts

`database/seed.sql` creates these accounts. All of them share the single password hash stored in the seed file. To set your own password, generate a hash and update the seed:

```
php -r "echo password_hash('YourPassword', PASSWORD_DEFAULT);"
```

| Role | Email |
|---|---|
| Administrator | `admin@helphive.org` |
| Organization | `contact@blueshores.org`, `hello@greenshift.org`, `info@pawscare.org` |
| Volunteer | `johnsmith@example.com`, `maria@example.com` |

---

## Coding Conventions

- PHP files use `declare(strict_types=1);`.
- Every class and public method has a PHPDoc block; JavaScript functions have JSDoc comments.
- Controllers end in `Controller`, models in `Model`.
- SQL lives only in models. Output escaping happens in views through `e()`.
- Dynamic route segments are named `{id}` and received as `int $id`.
- Use post-redirect-get after every successful POST.

### Documentation maintenance

This repository is built with Model Context Protocol (MCP) tooling and multiple agents. To keep everyone synchronized:

1. Update `ARCHITECTURE.md`, `DATABASE_SCHEMA.md`, `API_ROUTING.md`, the relevant `docs/FLOW_*.md`, and `CHANGELOG.md` whenever schemas, routes, file structure, or business rules change.
2. State the exact relative file path for every code change.
3. End each work session with the next sequential tasks.

## Documentation Index

| File | Contents |
|---|---|
| `ARCHITECTURE.md` | System design, layers, request lifecycle, invariants, security |
| `DATABASE_SCHEMA.md` | Tables, constraints, indexes, query rules |
| `docs/ERD.md` | Entity relationship diagram and state machines |
| `API_ROUTING.md` | Endpoint map, controller actions, access levels |
| `docs/FLOW_AUTH.md` | Registration, organization registration, login, logout, sessions |
| `docs/FLOW_VALIDATION.md` | Validation layers, field rules, access decisions |
| `docs/FLOW_SEARCH.md` | Search and filter behavior |
| `docs/FLOW_REGISTRATION_AND_HOURS.md` | Registration, attendance, hour verification, reports |
| `docs/FLOW_ORGANIZATION_AND_ADMIN.md` | Organization lifecycle, opportunity lifecycle, admin actions |
| `docs/KNOWN_GAPS.md` | Differences between intended and current behavior, with fixes |
| `CHANGELOG.md` | History of changes |

---

## Project Status and Roadmap

| Sprint | Focus | Status |
|---|---|---|
| 1 | Requirements, design, Figma prototype | Complete |
| 2 | Environment, database, authentication, base architecture | Complete |
| 3 | Opportunity management, search, registration and capacity, notifications | In progress: views, controllers, and models exist; notification events and several validations remain |
| 4 | Attendance and hour verification, reports and exports, admin tools, testing, security review | Controllers and models exist; hardening and testing remain |

Final delivery date: December 12, 2026.

### Testing priorities

- Concurrent registration for the last available slot.
- Duplicate registration, and re-registration after cancellation.
- Access to protected URLs by the wrong role or a non-owner.
- No report or total query returns non-verified hours.
- CSRF rejection on state-changing requests without a valid token.
- Draft and cancelled opportunities are not visible to the public.
# HelpHive

HelpHive is a centralized community volunteering web platform that connects volunteers with local nonprofit organizations. Volunteers can browse, register for, and track community service opportunities such as cleanups, donation drives, and outreach programs. Organizations can publish opportunities, manage attendance, verify service hours, and export participation reports.

Project timeline: four Agile Scrum sprints concluding on December 12, 2026.

---

## Table of Contents

1. [Features](#features)
2. [Technology Stack](#technology-stack)
3. [Scope Boundaries](#scope-boundaries)
4. [User Roles](#user-roles)
5. [System Architecture](#system-architecture)
6. [Directory Structure](#directory-structure)
7. [Request Lifecycle](#request-lifecycle)
8. [Business Rules](#business-rules)
9. [Security Standards](#security-standards)
10. [Database Overview](#database-overview)
11. [Getting Started (Laragon)](#getting-started-laragon)
12. [Configuration](#configuration)
13. [Routing](#routing)
14. [Coding Conventions](#coding-conventions)
15. [Documentation Index](#documentation-index)
16. [Development Roadmap](#development-roadmap)

---

## Features

- **Accounts and authentication:** secure registration, login, and session management for volunteers and organizations.
- **Opportunity publishing:** organizations create, edit, save as draft, and publish opportunities with title, description, date and time, location, capacity, and requirements.
- **Search and discovery:** keyword search and filtering by cause area, location, and date.
- **Registration and capacity:** authenticated volunteers register for open opportunities, with capacity limits, registration deadlines, and duplicate-registration prevention.
- **Attendance and hour verification:** organizations mark attendance (present, absent, excused) and verify service hours.
- **Reporting and exports:** participation reports built exclusively from verified data, exportable as CSV and PDF.
- **In-app notifications:** alerts for registration updates, approvals, and hour verifications.

## Technology Stack

| Layer | Technology |
|---|---|
| Frontend | HTML5, CSS3, JavaScript |
| Backend | PHP 8.1+ (vanilla, lightweight MVC) |
| Database | MySQL 8 (InnoDB, utf8mb4), accessed through PDO |
| Web server | Apache or Nginx via Laragon |
| PDF generation | Dompdf (open source, installed with Composer) |
| Local environment | Laragon (`C:\laragon\www\helphive`) |
| Design reference | Approved Figma prototype, `HelpHive--Copy-`, node `308-39720` |

All views must mirror the layout, hierarchy, and visual system defined in the Figma prototype.

## Scope Boundaries

The following are intentionally out of scope:

- Native iOS or Android applications (the interface is responsive web only).
- Third-party payment gateways.
- GPS or Google Maps API integration (location filtering is text-based).
- Paid third-party SaaS dependencies.

## User Roles

| Role | Capabilities |
|---|---|
| Volunteer | Browse and search opportunities, register and cancel, view dashboard, claim hours, view verified and pending hours separately, receive notifications |
| Organization | Manage organization profile, create, edit, publish, and cancel opportunities, mark attendance, verify or reject hours, generate and export reports |
| Administrator | Manage users, approve or reject organizations, oversee platform data |

Role enforcement happens on the server for every protected route. Hiding links in the interface is never treated as access control.

---

## System Architecture

HelpHive follows a lightweight MVC pattern with a single front controller.

| Layer | Location | Responsibility |
|---|---|---|
| Entry point | `public/` | Front controller and static assets. The only web-exposed directory. |
| Configuration | `config/` | Environment loading, database settings, route table. |
| Controllers | `app/controllers/` | Handle HTTP requests, enforce authentication and authorization, call models, select views. |
| Models | `app/models/` | All SQL. Every query uses PDO prepared statements. |
| Views | `app/views/` | PHP templates that render data. All dynamic output is escaped with `e()`. |
| Utilities | `app/utils/` | Database wrapper, authentication and RBAC, CSRF protection, flash messages, exporters. |

Design principles:

- **Separation of concerns:** controllers never contain SQL, models never render output, views never make authorization decisions.
- **Single source of truth for hours:** only `HourModel` produces hour totals and report rows.
- **Fail closed:** an unknown route returns 404, a role mismatch returns 403, an invalid CSRF token returns 419.
- **Local-first:** configuration is read from `.env`, so the project runs on Laragon with no external services.

## Directory Structure

```
helphive/
├── .env                          Local environment values (not committed)
├── .env.example                  Template for .env
├── .gitignore
├── composer.json                 Dompdf dependency
├── README.md
├── ARCHITECTURE.md
├── DATABASE_SCHEMA.md
├── API_ROUTING.md
├── CHANGELOG.md
├── database/
│   ├── schema.sql                Tables, constraints, indexes
│   └── seed.sql                  Sample data for local development
├── config/
│   ├── env.php                   .env loader and env() helper
│   ├── database.php              Database configuration
│   └── routes.php                Route table
├── public/                       Web document root
│   ├── index.php                 Front controller
│   ├── .htaccess                 URL rewriting
│   └── assets/
│       ├── css/
│       ├── js/
│       └── img/
└── app/
    ├── controllers/
    │   ├── HomeController.php
    │   ├── AuthController.php
    │   ├── OpportunityController.php
    │   ├── RegistrationController.php
    │   ├── VolunteerController.php
    │   ├── OrgDashboardController.php
    │   ├── OrgProfileController.php
    │   ├── OrgOpportunityController.php
    │   ├── AttendanceController.php
    │   ├── ReportController.php
    │   ├── NotificationController.php
    │   └── AdminController.php
    ├── models/
    │   ├── UserModel.php
    │   ├── OrganizationModel.php
    │   ├── OpportunityModel.php
    │   ├── RegistrationModel.php
    │   ├── HourModel.php
    │   └── NotificationModel.php
    ├── views/
    │   ├── layouts/              Shared header, footer, navigation
    │   ├── auth/
    │   ├── opportunities/
    │   ├── volunteer/
    │   ├── org/
    │   ├── admin/
    │   └── notifications/
    └── utils/
        ├── Database.php          PDO singleton
        ├── Auth.php              Session login and role checks
        ├── Csrf.php              Token generation and verification
        ├── Flash.php             One-time session messages
        ├── helpers.php           e() output escaping
        └── exporters/
            ├── CsvExporter.php
            └── PdfExporter.php
```

## Request Lifecycle

```
Browser
  |
  v
public/index.php            load .env, register autoloader, start hardened session
  |
  v
Router (config/routes.php)  match HTTP method and path, 404 if none
  |
  v
CSRF check                  all POST requests, 419 on failure
  |
  v
Controller action           Auth::requireRole(...), ownership check, input validation
  |
  v
Model (PDO)                 prepared statements, transactions where needed
  |
  v
View, redirect, or file     escaped HTML, redirect after POST, or CSV/PDF download
```

---

## Business Rules

### 1. Service hour integrity

- Unverified hours must never be merged into verified totals or included in official participation reports.
- Each attendance record has a `verification_status` of `unverified`, `pending`, `verified`, or `rejected`.
- Every total and report query includes the predicate `verification_status = 'verified'`.
- A database CHECK constraint prevents a non-verified row from holding a verified-hours value.
- The interface always displays verified hours and pending hours as separate figures.

### 2. Role-based access control

- Every protected route and action verifies session authentication and role authorization.
- Ownership is verified separately. For example, an organization can act only on opportunities it owns, and a volunteer can act only on their own registrations.
- Direct URL manipulation must not grant access to another user's data.

### 3. Registration and capacity integrity

- Capacity is checked inside a transaction that locks the opportunity row (`SELECT ... FOR UPDATE`), preventing
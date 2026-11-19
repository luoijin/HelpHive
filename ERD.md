# HelpHive - Entity Relationship Diagram

Source of truth for the DDL is `database/schema.sql`. Column-level detail is in `DATABASE_SCHEMA.md`.

## 1. ERD with attributes

```mermaid
erDiagram
    users {
        int id PK
        varchar full_name
        varchar email UK
        varchar password_hash
        enum role "volunteer, organization, admin"
        tinyint is_active
        timestamp created_at
        timestamp updated_at
    }
    organizations {
        int id PK
        int user_id FK, UK
        varchar name
        text description
        varchar contact_email
        varchar location
        tinyint is_approved
        timestamp created_at
    }
    opportunities {
        int id PK
        int organization_id FK
        varchar title
        text description
        varchar cause_area
        varchar location
        datetime start_at
        datetime end_at
        datetime registration_deadline
        smallint capacity
        text requirements
        enum status "draft, published, cancelled, completed"
        timestamp created_at
        timestamp updated_at
    }
    registrations {
        int id PK
        int opportunity_id FK
        int volunteer_id FK
        enum status "registered, cancelled, waitlisted"
        timestamp registered_at
    }
    attendance_and_hours {
        int id PK
        int registration_id FK, UK
        enum attendance_status "pending, present, absent, excused"
        decimal hours_claimed
        decimal hours_verified
        enum verification_status "unverified, pending, verified, rejected"
        int verified_by FK
        datetime verified_at
        timestamp updated_at
    }
    notifications {
        int id PK
        int user_id FK
        varchar type
        varchar message
        varchar link
        tinyint is_read
        timestamp created_at
    }

    users ||--o| organizations : "owns (role = organization)"
    organizations ||--o{ opportunities : publishes
    opportunities ||--o{ registrations : receives
    users ||--o{ registrations : "makes (volunteer_id)"
    registrations ||--o| attendance_and_hours : tracks
    users ||--o{ attendance_and_hours : "verifies (verified_by)"
    users ||--o{ notifications : receives
```

## 2. Relationship summary

| Parent | Child | Cardinality | FK | On delete |
|---|---|---|---|---|
| users | organizations | 1 to 0..1 | `organizations.user_id` (unique) | CASCADE |
| organizations | opportunities | 1 to N | `opportunities.organization_id` | CASCADE |
| opportunities | registrations | 1 to N | `registrations.opportunity_id` | CASCADE |
| users (volunteer) | registrations | 1 to N | `registrations.volunteer_id` | CASCADE |
| registrations | attendance_and_hours | 1 to 0..1 | `attendance_and_hours.registration_id` (unique) | CASCADE |
| users (verifier) | attendance_and_hours | 1 to N | `attendance_and_hours.verified_by` | SET NULL |
| users | notifications | 1 to N | `notifications.user_id` | CASCADE |

Notes:

- A user has one role at a time. When a volunteer registers an organization, `users.role` changes to `organization`.
- An `attendance_and_hours` row is created in the same transaction as its registration, so every registration has exactly one hours record from the start.
- `registrations` is the many-to-many join between volunteers and opportunities, made unique by `UNIQUE(opportunity_id, volunteer_id)`.
- There is no table for saved (bookmarked) opportunities. Bookmarks exist only in browser memory.

## 3. State machines

### 3.1 Opportunity status (`opportunities.status`)

```mermaid
stateDiagram-v2
    [*] --> draft : create (default)
    [*] --> published : create with status=published
    draft --> published : publish
    published --> draft : edit with status=draft
    published --> cancelled : cancel
    draft --> cancelled : cancel
    draft --> [*] : delete (drafts only)
    published --> completed : set status (planned, no UI yet)
    cancelled --> [*]
    completed --> [*]
```

Only `published` opportunities appear in search and accept registrations.

### 3.2 Registration status (`registrations.status`)

```mermaid
stateDiagram-v2
    [*] --> registered : register (capacity, deadline, uniqueness checks pass)
    registered --> cancelled : volunteer cancels
    cancelled --> registered : re-register (planned, see gaps)
    [*] --> waitlisted : planned, not implemented
```

### 3.3 Attendance status (`attendance_and_hours.attendance_status`)

```mermaid
stateDiagram-v2
    [*] --> pending : row created with registration
    pending --> present : organization marks
    pending --> absent : organization marks
    pending --> excused : organization marks
    present --> absent : organization corrects
    present --> excused : organization corrects
    absent --> present : organization corrects
    excused --> present : organization corrects
```

### 3.4 Hour verification status (`attendance_and_hours.verification_status`)

```mermaid
stateDiagram-v2
    [*] --> unverified : row created, hours_claimed = 0
    unverified --> pending : volunteer claims hours
    pending --> pending : volunteer updates claim
    pending --> verified : organization verifies (attendance = present)
    unverified --> verified : organization verifies (attendance = present)
    pending --> rejected : organization rejects
    unverified --> rejected : organization rejects (no state guard)
    verified --> rejected : organization rejects (no state guard, see gaps)
    rejected --> pending : volunteer claims again
```

A `verified` row counts toward official totals. `HourModel::reject` has no guard on the current state, so a verified row can currently be moved to `rejected`; this is recorded in `docs/KNOWN_GAPS.md`.

Invariant (`chk_ah_verified`): `hours_verified` and `verified_at` are set if and only if `verification_status = 'verified'`. Official totals and reports read only `verified` rows.

## 4. Where each table is read and written

| Table | Written by | Read by |
|---|---|---|
| users | `AuthController` (register, role change), `AdminController` (toggle active) | `AuthController` (login), `AdminController` |
| organizations | `AuthController` (organization registration), `OrgProfileController`, `AdminController` (approve, reject) | `OrganizationModel` (ownership lookup), `OpportunityModel` (join for `org_name`), `AdminController` |
| opportunities | `OrgOpportunityController` | `OpportunityController` (search, detail), `RegistrationModel`, `HourModel` (joins) |
| registrations | `RegistrationController` | `VolunteerController`, `OpportunityModel::registrationsWithAttendance` |
| attendance_and_hours | `RegistrationModel::register` (insert), `HourModel` (claim, mark, verify, reject) | `HourModel` totals and reports, volunteer hours page, attendance page |
| notifications | `NotificationModel::notify` (no caller yet) | `NotificationController` |
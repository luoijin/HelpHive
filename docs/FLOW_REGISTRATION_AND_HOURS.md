# HelpHive - Registration, Attendance, Hours, and Reports

Covers the volunteer journey from signing up for an opportunity to verified service hours and organization reports. Implemented in `RegistrationController`, `RegistrationModel`, `VolunteerController`, `AttendanceController`, `ReportController`, `HourModel`, and the exporters.

## 1. End-to-end journey

```mermaid
flowchart LR
    A["Volunteer finds<br/>an opportunity"] --> B["Registers<br/>(registrations row +<br/>attendance_and_hours row)"]
    B --> C["Attends the event"]
    C --> D["Organization marks<br/>attendance"]
    C --> E["Volunteer claims hours"]
    D --> F["Organization verifies<br/>or rejects hours"]
    E --> F
    F --> G["Verified hours count in<br/>volunteer total and reports"]
    F --> H["Rejected: volunteer may<br/>claim again"]
```

## 2. Registering for an opportunity (`POST /opportunities/{id}/register`)

`RegistrationModel::register` runs inside one transaction and locks the opportunity row, so two volunteers cannot take the last spot at the same time.

```mermaid
flowchart TD
    A(["Volunteer clicks Confirm Registration"]) --> B{"Logged in as volunteer?"}
    B -- "No" --> B1["redirect /login or 403"]
    B -- "Yes" --> C["BEGIN TRANSACTION"]
    C --> D["SELECT capacity, registration_deadline, status<br/>FROM opportunities WHERE id = ? FOR UPDATE"]
    D --> E{"Row exists<br/>and status = published?"}
    E -- "No" --> E1["ROLLBACK -> 'closed'"]
    E -- "Yes" --> F{"now <= registration_deadline?"}
    F -- "No" --> F1["ROLLBACK -> 'deadline'"]
    F -- "Yes" --> G["COUNT registrations<br/>WHERE status = registered"]
    G --> H{"count < capacity?"}
    H -- "No" --> H1["ROLLBACK -> 'full'"]
    H -- "Yes" --> I["INSERT registrations (opportunity_id, volunteer_id)"]
    I --> J{"UNIQUE violation<br/>(SQLSTATE 23000)?"}
    J -- "Yes" --> J1["ROLLBACK -> 'duplicate'"]
    J -- "No" --> K["INSERT attendance_and_hours (registration_id)<br/>defaults: pending / 0.00 / unverified"]
    K --> L["COMMIT -> 'ok'"]
```

Controller reaction to each result:

| Result | Message | Destination |
|---|---|---|
| `ok` | Registration submitted successfully | Renders `opportunities/confirmed` |
| `duplicate` | You are already registered | `/volunteer/registrations` |
| `full` | Maximum capacity reached | `/opportunities/{id}` |
| `deadline` | Registration deadline has passed | `/opportunities/{id}` |
| `closed` (default) | No longer open for registrations | `/opportunities/{id}` |

Capacity counts only rows with `status = 'registered'`; cancelled rows free their spot.

## 3. Cancelling a registration (`POST /registrations/{id}/cancel`)

```mermaid
flowchart TD
    A(["Volunteer clicks Cancel"]) --> B{"Role = volunteer?"}
    B -- "No" --> B1["403"]
    B -- "Yes" --> C["UPDATE registrations SET status = cancelled<br/>WHERE id = ? AND volunteer_id = session user"]
    C --> D{"1 row updated?"}
    D -- "Yes" --> D1["Flash success"]
    D -- "No" --> D2["Flash error: could not cancel"]
    D1 --> E["redirect /volunteer/registrations"]
    D2 --> E
```

A cancelled registration keeps its row. Because of `UNIQUE(opportunity_id, volunteer_id)`, registering again for the same opportunity currently returns `duplicate` (see `docs/KNOWN_GAPS.md`).

## 4. Volunteer pages and the numbers they show

| Page | Route | Data |
|---|---|---|
| Dashboard | `/volunteer/dashboard` | Verified total, pending total, number of registrations |
| My Applications | `/volunteer/registrations` | All registrations with opportunity and organization details |
| Service Hours | `/volunteer/hours` | Verified total, pending total, history of `registered` records with attendance and verification state |
| Saved | `/volunteer/saved` | Placeholder; always empty list |

The two totals are separate queries:

| Total | Method | Sums | Filter |
|---|---|---|---|
| Verified | `HourModel::verifiedTotalForVolunteer` | `hours_verified` | `verification_status = 'verified'` |
| Pending | `HourModel::pendingTotalForVolunteer` | `hours_claimed` | `verification_status IN ('unverified','pending')` |

The pending total is for display only and must never be added to the verified total.

## 5. Attendance (organization)

```mermaid
flowchart TD
    A(["Organization opens<br/>/org/opportunities/{id}/attendance"]) --> B{"Role = organization<br/>and owns opportunity?"}
    B -- "No" --> B1["403 Forbidden"]
    B -- "Yes" --> C["List registrations with attendance and hours<br/>(LEFT JOIN attendance_and_hours)"]
    C --> D(["Organization chooses a status<br/>POST /org/attendance/{registrationId}"])
    D --> E{"status in<br/>present, absent, excused, pending?"}
    E -- "No" --> E1["Coerced to present"]
    E -- "Yes" --> F["UPDATE attendance_and_hours<br/>JOIN opportunities<br/>WHERE registration_id = ? AND organization_id = ?"]
    E1 --> F
    F --> G["Flash success, redirect back"]
```

## 6. Claiming, verifying, and rejecting hours

```mermaid
sequenceDiagram
    participant V as Volunteer
    participant H as HourModel
    participant DB as attendance_and_hours
    participant O as Organization

    V->>H: claimHours(registrationId, volunteerId, hours)
    H->>DB: set hours_claimed, verification_status = pending<br/>(own record, state unverified/pending/rejected)
    O->>H: markAttendance(registrationId, orgId, present)
    O->>H: verify(registrationId, orgId, userId, hours_verified)
    alt attendance = present AND state unverified/pending
        H->>DB: hours_verified, verification_status = verified,<br/>verified_by, verified_at = NOW()
        H-->>O: true -> success flash
    else conditions not met
        H-->>O: false -> "Ensure attendance is marked Present"
    end
    opt organization disagrees
        O->>H: reject(registrationId, orgId)
        H->>DB: hours_verified = NULL, verification_status = rejected
    end
```

Rules:

| Action | Who | Preconditions | Result |
|---|---|---|---|
| Claim hours | Volunteer, own record | hours greater than 0 and at most 24; state `unverified`, `pending`, or `rejected` | `hours_claimed` set; state `pending` |
| Verify | Organization that owns the opportunity | attendance `present`; state `unverified` or `pending`; hours greater than 0 | `hours_verified`, `verified_by`, `verified_at` set; state `verified` |
| Reject | Organization that owns the opportunity | none enforced on state | `hours_verified` cleared; state `rejected` |

## 7. Reports and exports (organization)

```mermaid
flowchart TD
    A(["Organization opens /org/reports<br/>optional ?from= &to="]) --> B{"Role = organization?"}
    B -- "No" --> B1["403 / redirect"]
    B -- "Yes" --> C["orgId from session user"]
    C --> D["HourModel::reportRows(orgId, from, to)"]
    D --> E["SELECT ... JOIN registrations, opportunities, users<br/>WHERE organization_id = orgId<br/>AND verification_status = 'verified'<br/>[AND start_at >= from] [AND start_at <= to]<br/>ORDER BY start_at DESC, volunteer"]
    E --> F{"Requested output"}
    F -- "page" --> G["view org/reports"]
    F -- "/export/csv" --> H["CsvExporter::download<br/>UTF-8 BOM, formula-safe cells"]
    F -- "/export/pdf" --> I["PdfExporter::download (Dompdf)<br/>A4 table, verified hours only"]
```

Columns in every report: opportunity title, start date, volunteer name and email, attendance status, verified hours, verification time. Only `verified` rows appear; pending, unverified, and rejected hours are never included.

Organization dashboard totals use `HourModel::verifiedTotalForOrg` (verified only). The administrator dashboard total uses `HourModel::platformTotalVerified` (verified only).

## 8. Notifications

```mermaid
flowchart LR
    E1["Registration events"] -.-> N["NotificationModel::notify(userId, type, message, link)"]
    E2["Attendance / hour verification events"] -.-> N
    E3["Organization approval events"] -.-> N
    N --> T[("notifications")]
    T --> P["NotificationController<br/>GET /notifications<br/>mark read / mark all read"]
```

Dotted arrows mean planned: `notify()` exists but no controller calls it yet, so the notifications page has nothing to show until events are wired in. Suggested events and recipients are in `docs/KNOWN_GAPS.md`.
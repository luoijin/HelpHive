# HelpHive — Architecture

## Overview
Vanilla PHP 8.1+ lightweight MVC, MySQL 8 (InnoDB, utf8mb4), served by Laragon (Apache/Nginx).
Document root: `C:\laragon\www\helphive\public`. All requests go through `public/index.php` (front controller).

## Layers
| Layer | Folder | Responsibility |
|---|---|---|
| Entry | `public/` | Front controller, static assets only |
| Config | `config/` | `.env` loading, DB config, route table |
| Controllers | `app/controllers/` | HTTP handling, auth/role checks, call models |
| Models | `app/models/` | All SQL (PDO prepared statements) |
| Views | `app/views/` | PHP templates; escape with `e()` |
| Utils | `app/utils/` | Auth, CSRF, flash, exporters |

## Data flow
Request → `public/index.php` → Router (method + path) → Middleware (`Auth::requireRole`, `Csrf::verify` on POST) → Controller → Model (PDO) → View / redirect / file download.

## Security rules
1. PDO only, `ERRMODE_EXCEPTION`, `EMULATE_PREPARES=false`. No string-concatenated SQL.
2. Output escaped with `htmlspecialchars($v, ENT_QUOTES, 'UTF-8')` via `e()`.
3. CSRF token on every state-changing POST.
4. `password_hash(PASSWORD_DEFAULT)` / `password_verify`; `session_regenerate_id(true)` on login.
5. RBAC enforced in each controller action, never only in the UI.
6. Session cookies: HttpOnly, SameSite=Lax.

## Business invariants
- **Hour integrity:** hours are verified only when `verification_status='verified'`. Every report/total query MUST include that predicate. Only `HourModel` may produce totals.
- **Capacity:** checked inside a transaction with `SELECT ... FOR UPDATE` on the opportunity row.
- **Duplicates:** `UNIQUE(opportunity_id, volunteer_id)` on `registrations`.
- **Deadlines:** registration rejected after `registration_deadline`.

## Out of scope
Native apps, payment gateways, GPS/Maps APIs, paid SaaS.
# HelpHive — Routing & RBAC

Roles: V = volunteer, O = organization, A = admin, G = guest. All POST routes require a CSRF token.

| Method | Path | Controller@action | Access |
|---|---|---|---|
| GET | / | HomeController@index | G |
| GET/POST | /register | AuthController@register | G |
| GET/POST | /login | AuthController@login | G |
| POST | /logout | AuthController@logout | any auth |
| GET | /opportunities | OpportunityController@index (search/filter) | G, V |
| GET | /opportunities/{id} | OpportunityController@show | G, V |
| POST | /opportunities/{id}/register | RegistrationController@store | V |
| POST | /registrations/{id}/cancel | RegistrationController@cancel | V (owner) |
| GET | /volunteer/dashboard | VolunteerController@dashboard | V |
| GET | /org/opportunities | OrgOpportunityController@index | O |
| GET/POST | /org/opportunities/create | OrgOpportunityController@create | O |
| GET/POST | /org/opportunities/{id}/edit | OrgOpportunityController@edit | O (owner) |
| POST | /org/opportunities/{id}/publish | OrgOpportunityController@publish | O (owner) |
| GET | /org/opportunities/{id}/attendance | AttendanceController@index | O (owner) |
| POST | /org/attendance/{registrationId} | AttendanceController@mark | O (owner) |
| POST | /org/hours/{registrationId}/verify | AttendanceController@verify | O (owner) |
| GET | /org/reports | ReportController@index | O |
| GET | /org/reports/export/csv | ReportController@csv | O |
| GET | /org/reports/export/pdf | ReportController@pdf | O |
| GET | /notifications | NotificationController@index | any auth |
| POST | /notifications/{id}/read | NotificationController@read | any auth (owner) |
| GET | /admin/users | AdminController@users | A |
| POST | /admin/organizations/{id}/approve | AdminController@approveOrg | A |

## RBAC rules
1. Role check first (`Auth::requireRole`), then ownership check (e.g. opportunity belongs to the org).
2. Failures: 401 → redirect /login; 403 → forbidden page.
3. Never trust IDs from the URL without an ownership query.
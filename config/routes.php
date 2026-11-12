<?php
declare(strict_types=1);

/**
 * HelpHive route table.
 *
 * Each entry is: [HTTP method, URL pattern, 'Controller@action'].
 *
 * Conventions:
 *  - Dynamic segments are written as {id} and match digits only.
 *  - The segment name must equal the controller method parameter name,
 *    because the front controller passes URL values as named arguments.
 *    Every dynamic segment in this file is {id}, so action signatures
 *    take `int $id`.
 *  - All POST routes are CSRF-verified by public/index.php before dispatch.
 *  - Authentication, role and ownership checks are enforced inside each
 *    controller action (Auth::requireRole plus an ownership query).
 *    The access notes below are documentation, not the enforcement point.
 *
 * Access legend: G = guest, V = volunteer, O = organization, A = admin,
 *                AUTH = any authenticated user.
 *
 * @return array<int, array{0:string,1:string,2:string}>
 */
return [

    // ---------------------------------------------------------------
    // Public
    // ---------------------------------------------------------------
    ['GET',  '/',                               'HomeController@index'],             // G

    // ---------------------------------------------------------------
    // Authentication
    // ---------------------------------------------------------------
    ['GET',  '/register',                       'AuthController@showRegister'],      // G
    ['POST', '/register',                       'AuthController@register'],          // G
    ['GET',  '/org/register',                   'AuthController@showRegisterOrg'],   // G, AUTH
    ['POST', '/org/register',                   'AuthController@registerOrg'],       // AUTH
    ['GET',  '/login',                          'AuthController@showLogin'],         // G
    ['POST', '/login',                          'AuthController@login'],             // G
    ['POST', '/logout',                         'AuthController@logout'],            // AUTH

    // ---------------------------------------------------------------
    // Opportunity discovery (search, filter, detail)
    // ---------------------------------------------------------------
    ['GET',  '/opportunities',                  'OpportunityController@index'],      // G, V
    ['GET',  '/opportunities/{id}',             'OpportunityController@show'],       // G, V

    // ---------------------------------------------------------------
    // Volunteer: registration
    // ---------------------------------------------------------------
    ['POST', '/opportunities/{id}/register',    'RegistrationController@store'],     // V
    ['POST', '/registrations/{id}/cancel',      'RegistrationController@cancel'],    // V (owner)

    // ---------------------------------------------------------------
    // Volunteer: dashboard and hours
    // ---------------------------------------------------------------
    ['GET',  '/volunteer/dashboard',            'VolunteerController@dashboard'],    // V
    ['GET',  '/volunteer/registrations',        'VolunteerController@registrations'],// V
    ['GET',  '/volunteer/hours',                'VolunteerController@hours'],        // V (verified and pending shown separately)
    ['POST', '/volunteer/hours/{id}/claim',     'VolunteerController@claimHours'],   // V (owner of registration {id})

    // ---------------------------------------------------------------
    // Organization: dashboard and profile
    // ---------------------------------------------------------------
    ['GET',  '/org/dashboard',                  'OrgDashboardController@index'],     // O
    ['GET',  '/org/profile',                    'OrgProfileController@edit'],        // O
    ['POST', '/org/profile',                    'OrgProfileController@update'],      // O

    // ---------------------------------------------------------------
    // Organization: opportunity management
    // ---------------------------------------------------------------
    ['GET',  '/org/opportunities',              'OrgOpportunityController@index'],   // O
    ['GET',  '/org/opportunities/create',       'OrgOpportunityController@create'],  // O
    ['POST', '/org/opportunities/create',       'OrgOpportunityController@store'],   // O
    ['GET',  '/org/opportunities/{id}/edit',    'OrgOpportunityController@edit'],    // O (owner)
    ['POST', '/org/opportunities/{id}/edit',    'OrgOpportunityController@update'],  // O (owner)
    ['POST', '/org/opportunities/{id}/publish', 'OrgOpportunityController@publish'], // O (owner)
    ['POST', '/org/opportunities/{id}/cancel',  'OrgOpportunityController@cancel'],  // O (owner)
    ['POST', '/org/opportunities/{id}/delete',  'OrgOpportunityController@delete'],  // O (owner, drafts only)

    // ---------------------------------------------------------------
    // Organization: attendance and hour verification
    //   {id} is the opportunity id for the list, and the registration id
    //   for the per-registration actions.
    // ---------------------------------------------------------------
    ['GET',  '/org/opportunities/{id}/attendance', 'AttendanceController@index'],  // O (owner)
    ['POST', '/org/attendance/{id}',               'AttendanceController@mark'],   // O (owner of registration {id}'s opportunity)
    ['POST', '/org/hours/{id}/verify',             'AttendanceController@verify'], // O (owner), requires attendance = present
    ['POST', '/org/hours/{id}/reject',             'AttendanceController@reject'], // O (owner)

    // ---------------------------------------------------------------
    // Organization: reports and exports (verified data only)
    // ---------------------------------------------------------------
    ['GET',  '/org/reports',                    'ReportController@index'],           // O
    ['GET',  '/org/reports/export/csv',         'ReportController@csv'],             // O
    ['GET',  '/org/reports/export/pdf',         'ReportController@pdf'],             // O

    // ---------------------------------------------------------------
    // Notifications
    // ---------------------------------------------------------------
    ['GET',  '/notifications',                  'NotificationController@index'],     // AUTH
    ['POST', '/notifications/{id}/read',        'NotificationController@read'],      // AUTH (owner)
    ['POST', '/notifications/read-all',         'NotificationController@readAll'],   // AUTH

    // ---------------------------------------------------------------
    // Administrator
    // ---------------------------------------------------------------
    ['GET',  '/admin/dashboard',                'AdminController@dashboard'],        // A
    ['GET',  '/admin/users',                    'AdminController@users'],            // A
    ['POST', '/admin/users/{id}/toggle',        'AdminController@toggleUser'],       // A
    ['GET',  '/admin/organizations',            'AdminController@organizations'],    // A
    ['POST', '/admin/organizations/{id}/approve', 'AdminController@approveOrg'],     // A
    ['POST', '/admin/organizations/{id}/reject',  'AdminController@rejectOrg'],      // A
];
<?php
declare(strict_types=1);

$currentUser = Auth::user();
$isLoggedIn = $currentUser !== null;
$userRole = $currentUser['role'] ?? 'guest';
$userName = $currentUser['name'] ?? 'Volunteer';
?>
<!-- ================= HEADER / NAVBAR ================= -->
<header class="main-header">
  <div class="nav-container">
    <!-- Left Section: Logo + Navigation Links Beside It -->
    <div class="nav-left">
      <!-- Brand Logo: One Help(black)Hive(yellow) -->
      <a href="/" class="brand-logo" aria-label="HelpHive Home">
        <span class="brand-name"><span class="logo-help">Help</span><span class="logo-hive">Hive</span></span>
      </a>

      <!-- Navigation Links (directly beside the logo on the left) -->
      <?php $currentPath = (string) parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH); ?>
      <nav class="nav-links">
        <a href="/" class="nav-item <?= ($currentPath === '/') ? 'active' : '' ?>" id="nav-link-home">Home</a>
        <a href="/opportunities" class="nav-item <?= str_starts_with($currentPath, '/opportunities') ? 'active' : '' ?>" id="nav-link-search">Search Opportunities</a>
        <?php if (!$isLoggedIn || $userRole === Auth::VOLUNTEER): ?>
          <a href="/register?role=organization" class="nav-item <?= (str_contains($_SERVER['REQUEST_URI'] ?? '', 'role=organization') || str_starts_with($currentPath, '/org/register')) ? 'active' : '' ?>" id="nav-link-register-org">Register Organization</a>
        <?php elseif ($userRole === Auth::ORGANIZATION): ?>
          <a href="/org/opportunities/create" class="nav-item">Post Opportunity</a>
        <?php endif; ?>
      </nav>
    </div>

    <!-- Right Section: Actions -->
    <div class="nav-right">
      <?php if (!$isLoggedIn): ?>
        <!-- Guest Actions -->
        <div class="nav-actions guest-only" style="display: flex;">
          <button type="button" class="btn btn-ghost" onclick="openModal('login-modal')">Log In</button>
          <button type="button" class="btn btn-primary" onclick="openModal('signup-modal')">Sign Up</button>
        </div>
      <?php else: ?>
        <!-- Logged-in Actions -->
        <div class="nav-actions user-only" style="display: flex;">
          <div class="profile-trigger-wrapper">
            <button type="button" class="profile-trigger-btn" id="profile-dropdown-btn" onclick="toggleProfileMenu()">
              <img src="<?= asset('assets/images/221bd9d1a676573b8f2e9d5942d9403c38e5a153.png') ?>" alt="Avatar" class="profile-avatar" onerror="this.src='https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80'">
              <span class="profile-name"><?= e($userName) ?></span>
              <i data-lucide="chevron-down" class="profile-chevron"></i>
            </button>

            <!-- Profile / Options Dropdown Menu -->
            <div class="profile-dropdown-menu" id="profile-dropdown">
              <?php if ($userRole === Auth::VOLUNTEER): ?>
                <!-- MY ACTIVITIES -->
                <div class="menu-section-header">MY ACTIVITIES</div>
                <a href="/volunteer/dashboard" class="dropdown-link">
                  <i data-lucide="layout-dashboard" class="menu-icon"></i>
                  <span>My Dashboard</span>
                </a>
                <a href="/volunteer/saved" class="dropdown-link">
                  <i data-lucide="bookmark" class="menu-icon"></i>
                  <span>Saved Opportunities</span>
                </a>
                <a href="/volunteer/registrations" class="dropdown-link">
                  <i data-lucide="clipboard-list" class="menu-icon"></i>
                  <span>My Applications</span>
                </a>
                <a href="/volunteer/hours" class="dropdown-link">
                  <i data-lucide="clock" class="menu-icon"></i>
                  <span>My Service Hours</span>
                </a>
              <?php endif; ?>

              <?php if ($userRole === Auth::ORGANIZATION): ?>
                <!-- ORGANIZATION -->
                <div class="menu-section-header">ORGANIZATION</div>
                <a href="/org/dashboard" class="dropdown-link">
                  <i data-lucide="building-2" class="menu-icon"></i>
                  <span>My Organization</span>
                </a>
                <a href="/org/opportunities/create" class="dropdown-link">
                  <i data-lucide="calendar-plus" class="menu-icon"></i>
                  <span>Post an Opportunity</span>
                </a>
                <a href="/org/opportunities" class="dropdown-link">
                  <i data-lucide="clipboard-check" class="menu-icon"></i>
                  <span>Manage Opportunities & Hours</span>
                </a>
                <a href="/org/reports" class="dropdown-link">
                  <i data-lucide="trending-up" class="menu-icon"></i>
                  <span>Participation Reports</span>
                </a>

                <!-- MY VOLUNTEERING (Dual Capacity) -->
                <div class="menu-section-header">MY VOLUNTEERING</div>
                <a href="/volunteer/dashboard" class="dropdown-link">
                  <i data-lucide="layout-dashboard" class="menu-icon"></i>
                  <span>Volunteer Dashboard</span>
                </a>
                <a href="/volunteer/saved" class="dropdown-link">
                  <i data-lucide="bookmark" class="menu-icon"></i>
                  <span>Saved Opportunities</span>
                </a>
                <a href="/volunteer/registrations" class="dropdown-link">
                  <i data-lucide="clipboard-list" class="menu-icon"></i>
                  <span>My Applications</span>
                </a>
                <a href="/volunteer/hours" class="dropdown-link">
                  <i data-lucide="clock" class="menu-icon"></i>
                  <span>My Service Hours</span>
                </a>
              <?php endif; ?>

              <?php if ($userRole === Auth::ADMIN): ?>
                <!-- ADMIN -->
                <div class="menu-section-header">ADMINISTRATION</div>
                <a href="/admin/dashboard" class="dropdown-link">
                  <i data-lucide="shield" class="menu-icon"></i>
                  <span>Admin Dashboard</span>
                </a>
                <a href="/admin/users" class="dropdown-link">
                  <i data-lucide="users" class="menu-icon"></i>
                  <span>Manage Users</span>
                </a>
                <a href="/admin/organizations" class="dropdown-link">
                  <i data-lucide="check-square" class="menu-icon"></i>
                  <span>Approve Organizations</span>
                </a>
              <?php endif; ?>

              <!-- ACCOUNT -->
              <div class="menu-section-header">ACCOUNT</div>
              <a href="/notifications" class="dropdown-link">
                <i data-lucide="bell" class="menu-icon"></i>
                <span>Notifications</span>
              </a>
              <div class="menu-divider"></div>
              <form action="/logout" method="POST" style="margin: 0;">
                <?= Csrf::field() ?>
                <button type="submit" class="dropdown-link logout-btn" style="width: 100%; text-align: left; background: none; border: none; cursor: pointer;">
                  <i data-lucide="log-out" class="menu-icon"></i>
                  <span>Log Out</span>
                </button>
              </form>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</header>

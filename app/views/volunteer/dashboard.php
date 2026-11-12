<?php
declare(strict_types=1);

$user = Auth::user();
$verifiedTotal = (float) ($verifiedHours ?? 0.0);
$pendingTotal = (float) ($pendingHours ?? 0.0);
$activeRegistrationsCount = (int) ($registrationsCount ?? 0);
?>
<!-- VOLUNTEER DASHBOARD -->
<section id="view-volunteer-dashboard" class="page-view active" style="display: block;">
  <div class="section-container">
    <div class="section-heading">
      <h2 class="section-title">Welcome back, <?= e($user['name'] ?? 'Volunteer') ?>!</h2>
      <p class="section-subtitle">Here is a quick snapshot of your community volunteering journey.</p>
    </div>

    <!-- Stats Grid -->
    <div class="hive-cards-grid" style="margin-bottom: 40px;">
      <div class="hive-feature-card highlighted">
        <div class="hive-card-icon-wrap green">
          <i data-lucide="check-circle" class="hive-icon"></i>
        </div>
        <h3 class="hive-card-title"><?= number_format($verifiedTotal, 2) ?> hrs</h3>
        <p class="hive-card-desc">Verified Service Hours certified by organizations.</p>
        <a href="/volunteer/hours" class="hive-card-arrow"><i data-lucide="arrow-up-right"></i></a>
      </div>

      <div class="hive-feature-card">
        <div class="hive-card-icon-wrap yellow">
          <i data-lucide="clock" class="hive-icon"></i>
        </div>
        <h3 class="hive-card-title"><?= number_format($pendingTotal, 2) ?> hrs</h3>
        <p class="hive-card-desc">Pending Verification awaiting organizer review.</p>
        <a href="/volunteer/hours" class="hive-card-arrow"><i data-lucide="arrow-up-right"></i></a>
      </div>

      <div class="hive-feature-card">
        <div class="hive-card-icon-wrap blue">
          <i data-lucide="calendar" class="hive-icon"></i>
        </div>
        <h3 class="hive-card-title"><?= $activeRegistrationsCount ?> Activities</h3>
        <p class="hive-card-desc">Active registrations and upcoming events.</p>
        <a href="/volunteer/registrations" class="hive-card-arrow"><i data-lucide="arrow-up-right"></i></a>
      </div>
    </div>

    <!-- Quick Action Banner -->
    <div class="banner-section" style="border-radius: var(--radius-lg); margin-top: 24px;">
      <div class="banner-inner">
        <h2 class="banner-title">Find Your Next Impactful Event</h2>
        <p class="banner-sub">Explore dozens of open volunteer initiatives across your local community.</p>
        <a href="/opportunities" class="btn btn-banner" style="text-decoration: none; display: inline-block;">Browse Open Opportunities</a>
      </div>
    </div>
  </div>
</section>

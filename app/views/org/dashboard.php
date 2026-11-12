<?php
declare(strict_types=1);

$user = Auth::user();
$orgName = $organization['name'] ?? $user['name'] ?? 'Organization';
?>
<!-- ORGANIZATION DASHBOARD -->
<section id="view-org-dashboard" class="page-view active" style="display: block;">
  <div class="section-container">
    <div class="section-heading">
      <h2 class="section-title"><?= e($orgName) ?> Dashboard</h2>
      <p class="section-subtitle">Manage your published opportunities, volunteer attendance, and certified hours.</p>
    </div>

    <!-- Stats Grid -->
    <div class="hive-cards-grid" style="margin-bottom: 40px;">
      <div class="hive-feature-card highlighted">
        <div class="hive-card-icon-wrap green">
          <i data-lucide="calendar-plus" class="hive-icon"></i>
        </div>
        <h3 class="hive-card-title"><?= (int) ($stats['opportunities_count'] ?? 0) ?> Opportunities</h3>
        <p class="hive-card-desc">Active and published volunteering initiatives.</p>
        <a href="/org/opportunities" class="hive-card-arrow"><i data-lucide="arrow-up-right"></i></a>
      </div>

      <div class="hive-feature-card">
        <div class="hive-card-icon-wrap yellow">
          <i data-lucide="users" class="hive-icon"></i>
        </div>
        <h3 class="hive-card-title"><?= (int) ($stats['volunteers_count'] ?? 0) ?> Volunteers</h3>
        <p class="hive-card-desc">Total registered participants across all events.</p>
        <a href="/org/opportunities" class="hive-card-arrow"><i data-lucide="arrow-up-right"></i></a>
      </div>

      <div class="hive-feature-card">
        <div class="hive-card-icon-wrap blue">
          <i data-lucide="trending-up" class="hive-icon"></i>
        </div>
        <h3 class="hive-card-title"><?= number_format((float) ($stats['verified_hours'] ?? 0), 2) ?> hrs</h3>
        <p class="hive-card-desc">Verified volunteer hours certified by your organization.</p>
        <a href="/org/reports" class="hive-card-arrow"><i data-lucide="arrow-up-right"></i></a>
      </div>
    </div>

    <!-- Quick Actions Grid -->
    <div style="display: flex; gap: 16px; flex-wrap: wrap; margin-bottom: 32px;">
      <a href="/org/opportunities/create" class="btn btn-primary" style="text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
        <i data-lucide="plus-circle"></i> Create New Opportunity
      </a>
      <a href="/org/opportunities" class="btn btn-ghost" style="text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
        <i data-lucide="clipboard-list"></i> Manage Opportunities & Attendance
      </a>
      <a href="/org/reports" class="btn btn-cause" style="text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
        <i data-lucide="file-text"></i> Participation Reports
      </a>
    </div>
  </div>
</section>

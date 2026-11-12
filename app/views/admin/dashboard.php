<?php
declare(strict_types=1);

$user = Auth::user();
?>
<!-- ADMIN DASHBOARD -->
<section id="view-admin-dashboard" class="page-view active" style="display: block;">
  <div class="section-container">
    <div class="section-heading">
      <h2 class="section-title">Administrator Dashboard</h2>
      <p class="section-subtitle">System oversight, user management, and organization verification.</p>
    </div>

    <!-- Stats Grid -->
    <div class="hive-cards-grid" style="margin-bottom: 40px;">
      <div class="hive-feature-card highlighted">
        <div class="hive-card-icon-wrap green">
          <i data-lucide="users" class="hive-icon"></i>
        </div>
        <h3 class="hive-card-title"><?= (int) ($stats['users_count'] ?? 0) ?> Users</h3>
        <p class="hive-card-desc">Total registered volunteer and partner accounts.</p>
        <a href="/admin/users" class="hive-card-arrow"><i data-lucide="arrow-up-right"></i></a>
      </div>

      <div class="hive-feature-card">
        <div class="hive-card-icon-wrap yellow">
          <i data-lucide="building-2" class="hive-icon"></i>
        </div>
        <h3 class="hive-card-title"><?= (int) ($stats['pending_orgs_count'] ?? 0) ?> Pending</h3>
        <p class="hive-card-desc">Organizations awaiting partner approval.</p>
        <a href="/admin/organizations" class="hive-card-arrow"><i data-lucide="arrow-up-right"></i></a>
      </div>

      <div class="hive-feature-card">
        <div class="hive-card-icon-wrap blue">
          <i data-lucide="award" class="hive-icon"></i>
        </div>
        <h3 class="hive-card-title"><?= number_format((float) ($stats['total_verified_hours'] ?? 0), 2) ?> hrs</h3>
        <p class="hive-card-desc">Platform-wide verified community service hours.</p>
      </div>
    </div>
  </div>
</section>

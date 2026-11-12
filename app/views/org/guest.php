<?php
declare(strict_types=1);
?>
<!-- GUEST REGISTER ORGANIZATION VIEW -->
<div class="org-guest-page">
  <!-- Section 1: Hero with Bhumiti Wallpaper & Card on Right -->
  <section class="org-hero-section">
    <div class="org-hero-container">
      <div class="org-hero-card">
        <div class="org-hero-img-wrap">
          <img src="<?= asset('assets/images/register-org-img.png') ?>" alt="Register Organization" class="org-hero-img">
        </div>
        <h1 class="org-hero-title">
          BRING YOUR CAUSE TO THE HIVE.
        </h1>
        <p class="org-hero-description">
          Connect with passionate volunteers and turn your organization's mission into meaningful community action.
        </p>
        <div class="org-hero-cta">
          <button type="button" class="btn hero-btn" onclick="openModal('signup-modal')">
            Register your organization
            <i data-lucide="arrow-right"></i>
          </button>
        </div>
      </div>
    </div>
  </section>

  <!-- Section 2: WHY JOIN HELPHIVE? -->
  <section class="why-join-section">
    <div class="section-container" style="padding-top: 60px; padding-bottom: 60px;">
      <div class="section-heading text-center" style="margin-bottom: 48px;">
        <h2 class="section-title">WHY JOIN HELP<span class="text-yellow">HIVE</span>?</h2>
      </div>

      <div class="hive-cards-grid">
        <!-- Feature 1: Find Volunteers -->
        <div class="hive-feature-card">
          <div class="hive-card-icon-center">
            <img src="<?= asset('assets/images/volunteer.png') ?>" alt="Find Volunteers" class="hive-card-img">
          </div>
          <h3 class="hive-card-title">Find Volunteers</h3>
          <p class="hive-card-desc">Connect with people who<br>care about your cause.</p>
        </div>

        <!-- Feature 2: Post Opportunities -->
        <div class="hive-feature-card">
          <div class="hive-card-icon-center">
            <img src="<?= asset('assets/images/join.png') ?>" alt="Post Opportunities" class="hive-card-img">
          </div>
          <h3 class="hive-card-title">Post Opportunities</h3>
          <p class="hive-card-desc">Share activities and welcome<br>new volunteers.</p>
        </div>

        <!-- Feature 3: Track Impact -->
        <div class="hive-feature-card">
          <div class="hive-card-icon-center">
            <img src="<?= asset('assets/images/org.png') ?>" alt="Track Impact" class="hive-card-img">
          </div>
          <h3 class="hive-card-title">Track Impact</h3>
          <p class="hive-card-desc">Manage participation and<br>verified volunteer hours.</p>
        </div>
      </div>
    </div>
  </section>
</div>

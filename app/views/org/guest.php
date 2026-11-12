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
    <div class="section-container">
      <div class="section-heading text-center" style="margin-bottom: 48px;">
        <h2 class="section-title">WHY JOIN HELP<span class="text-yellow">HIVE</span>?</h2>
      </div>

      <div class="hive-cards-grid">
        <!-- Feature 1: Find Volunteers -->
        <div class="hive-feature-card">
          <div class="hive-card-icon-center">
            <img src="<?= asset('assets/images/find-volunteers.png') ?>" alt="Find Volunteers" class="hive-card-img">
          </div>
          <h3 class="hive-card-title">Find Volunteers</h3>
          <p class="hive-card-desc">Connect with people who<br>care about your cause.</p>
        </div>

        <!-- Feature 2: Post Opportunities -->
        <div class="hive-feature-card">
          <div class="hive-card-icon-center">
            <img src="<?= asset('assets/images/post-opportunities.png') ?>" alt="Post Opportunities" class="hive-card-img">
          </div>
          <h3 class="hive-card-title">Post Opportunities</h3>
          <p class="hive-card-desc">Share activities and welcome<br>new volunteers.</p>
        </div>

        <!-- Feature 3: Track Impact -->
        <div class="hive-feature-card">
          <div class="hive-card-icon-center">
            <img src="<?= asset('assets/images/track-impact.png') ?>" alt="Track Impact" class="hive-card-img">
          </div>
          <h3 class="hive-card-title">Track Impact</h3>
          <p class="hive-card-desc">Manage participation and<br>verified volunteer hours.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Section 3: GET STARTED IN 3 SIMPLE STEPS -->
  <section class="get-started-section">
    <div class="section-container">
      <div class="get-started-wrapper">
        <h2 class="section-title get-started-title">GET STARTED IN <span class="text-yellow">3 SIMPLE STEPS</span></h2>

        <div class="steps-vertical-wrap">
          <!-- Step 1 -->
          <div class="step-bullet-item">
            <div class="step-bullet-num">1</div>
            <div class="step-bullet-content">
              <h3 class="step-bullet-title">Create an Account</h3>
              <p class="step-bullet-desc">Sign up or log in to get started.</p>
            </div>
          </div>

          <!-- Step 2 -->
          <div class="step-bullet-item">
            <div class="step-bullet-num">2</div>
            <div class="step-bullet-content">
              <h3 class="step-bullet-title">Register Your Organization</h3>
              <p class="step-bullet-desc">Submit your organization's information for review.</p>
            </div>
          </div>

          <!-- Step 3 -->
          <div class="step-bullet-item">
            <div class="step-bullet-num">3</div>
            <div class="step-bullet-content">
              <h3 class="step-bullet-title">Start Making an Impact</h3>
              <p class="step-bullet-desc">Once approved, publish opportunities and manage volunteer participation.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

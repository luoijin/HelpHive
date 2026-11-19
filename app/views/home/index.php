<?php
declare(strict_types=1);
?>
<!-- 1. HOME VIEW (Figma 308:39998 & 381:8589) -->
<section id="view-home" class="page-view active" style="display: block;">
  <!-- Hero Section with Bhumiti Wallpaper Background (100vh) -->
  <div class="hero-section">
    <div class="hero-container">
      <div class="hero-card">
        <span class="hero-eyebrow">Welcome to HelpHive!</span>
        <h1 class="hero-title">
          BE A LITTLE BUZZ OF HOPE<br>
          IN A WORLD THAT NEEDS<br>
          MORE KINDNESS.
        </h1>
        <p class="hero-description">
          Discover opportunities to give back, support local organizations, and make a positive impact where it matters most. HelpHive connects volunteers with meaningful ways to serve their communities because even the smallest contribution can inspire great change.
        </p>
        <div class="hero-cta-wrap">
          <a href="/opportunities" class="btn hero-btn">
            View all opportunities
            <i data-lucide="arrow-right"></i>
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Find Your Place in the Hive Section -->
  <section class="section-container hive-cards-section">
    <div class="section-heading text-center">
      <h2 class="section-title">Find Your Place in the <span class="text-yellow">Hive</span> and Make a Difference</h2>
    </div>

    <div class="hive-cards-grid">
      <!-- Card 1 -->
      <div class="hive-feature-card" onclick="openModal('login-modal')" style="cursor: pointer;">
        <a href="#" class="hive-card-arrow" onclick="event.stopPropagation(); openModal('login-modal'); return false;" aria-label="Join the Hive">
          <i data-lucide="arrow-up-right"></i>
        </a>
        <div class="hive-card-icon-center">
          <img src="<?= asset('assets/images/join.png') ?>" alt="Join the Hive" class="hive-card-img">
        </div>
        <h3 class="hive-card-title">Join the Hive</h3>
        <p class="hive-card-desc">Become part of a caring community where people share their time and talents to make a difference.</p>
      </div>

      <!-- Card 2 -->
      <div class="hive-feature-card" onclick="window.location.href='/opportunities'" style="cursor: pointer;">
        <a href="/opportunities" class="hive-card-arrow" onclick="event.stopPropagation();" aria-label="Volunteer Opportunities">
          <i data-lucide="arrow-up-right"></i>
        </a>
        <div class="hive-card-icon-center">
          <img src="<?= asset('assets/images/volunteer.png') ?>" alt="Volunteer Opportunities" class="hive-card-img">
        </div>
        <h3 class="hive-card-title">Volunteer Opportunities</h3>
        <p class="hive-card-desc">Find meaningful volunteer activities that match your interests and help make your community a better place.</p>
      </div>

      <!-- Card 3 -->
      <div class="hive-feature-card" onclick="window.location.href='/org/register'" style="cursor: pointer;">
        <a href="/org/register" class="hive-card-arrow" onclick="event.stopPropagation();" aria-label="Register Your Organization">
          <i data-lucide="arrow-up-right"></i>
        </a>
        <div class="hive-card-icon-center">
          <img src="<?= asset('assets/images/org.png') ?>" alt="Register Your Organization" class="hive-card-img">
        </div>
        <h3 class="hive-card-title">Register Your Organization</h3>
        <p class="hive-card-desc">Share volunteer opportunities and connect with people who want to help bring your community projects to life.</p>
      </div>
    </div>
  </section>

  <!-- Causes You Can Support Section -->
  <section class="causes-section-bg">
    <div class="section-container">
      <div class="section-heading text-center">
        <h2 class="section-title">Causes You Can Support</h2>
       
      </div>

      <div class="causes-grid">
        <!-- Cause 1 -->
        <article class="cause-card">
          <div class="cause-img-wrap">
            <img src="<?= asset('assets/images/bcaa29c6960f82756e4e156fec5481a5ed10c521.png') ?>" alt="Environment & Nature" onerror="this.src='https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?w=600&auto=format&fit=crop&q=80'">
          </div>
          <div class="cause-card-body">
            <h3 class="cause-card-title">Environment & Nature</h3>
            <p class="cause-card-desc">Help protect our planet through tree planting, cleanups, and environmental activities.</p>
            <a href="/opportunities?cause=Environment" class="btn btn-cause" style="text-decoration: none; display: inline-block;">Explore Cause</a>
          </div>
        </article>

        <!-- Cause 2 -->
        <article class="cause-card">
          <div class="cause-img-wrap">
            <img src="<?= asset('assets/images/fa9a09b328e3ff6d275f4933939d59d043244a18.png') ?>" alt="Education & Youth" onerror="this.src='https://images.unsplash.com/photo-1509062522246-3755977927d7?w=600&auto=format&fit=crop&q=80'">
          </div>
          <div class="cause-card-body">
            <h3 class="cause-card-title">Education & Youth</h3>
            <p class="cause-card-desc">Inspire young minds by tutoring, reading together, and supporting learning programs.</p>
            <a href="/opportunities?cause=Education" class="btn btn-cause" style="text-decoration: none; display: inline-block;">Explore Cause</a>
          </div>
        </article>

        <!-- Cause 3 -->
        <article class="cause-card">
          <div class="cause-img-wrap">
            <img src="<?= asset('assets/images/055cd8289ec97665bd23daad150f0f4ec6745575.png') ?>" alt="Community Support" onerror="this.src='https://images.unsplash.com/photo-1593113598332-cd288d649433?w=600&auto=format&fit=crop&q=80'">
          </div>
          <div class="cause-card-body">
            <h3 class="cause-card-title">Community Support</h3>
            <p class="cause-card-desc">Support families and neighbors through food drives, donation programs, and outreach.</p>
            <a href="/opportunities?cause=Community+Support" class="btn btn-cause" style="text-decoration: none; display: inline-block;">Explore Cause</a>
          </div>
        </article>

        <!-- Cause 4 -->
        <article class="cause-card">
          <div class="cause-img-wrap">
            <img src="<?= asset('assets/images/4cda783da487dd5b7bfdf8834b0d1ba361720005.png') ?>" alt="Animal Welfare" onerror="this.src='https://images.unsplash.com/photo-1548767797-d8c844163c4c?w=600&auto=format&fit=crop&q=80'">
          </div>
          <div class="cause-card-body">
            <h3 class="cause-card-title">Animal Welfare</h3>
            <p class="cause-card-desc">Give animals a little extra love by helping at shelters and supporting animal care.</p>
            <a href="/opportunities?cause=Animal+Welfare" class="btn btn-cause" style="text-decoration: none; display: inline-block;">Explore Cause</a>
          </div>
        </article>
      </div>
    </div>
  </section>

  <!-- About HelpHive Section -->
  <section id="about" class="about-section">
    <div class="section-container">
      <div class="about-grid">
        <div class="about-text-content">
          <span class="about-eyebrow">ABOUT HELPHIVE</span>
          <h2 class="section-title">Empowering Communities Through <span class="text-yellow">Purposeful Action</span></h2>
          <p class="about-lead">
            HelpHive is a community-driven volunteering platform dedicated to bridging the gap between compassionate individuals and passionate grassroots organizations.
          </p>
          <p class="about-description">
            We believe that every small act of kindness creates a ripple effect of hope. Whether it's restoring local coastlines, tutoring youth, packing food boxes for families, or caring for sheltered animals, HelpHive makes giving back transparent, accessible, and deeply rewarding.
          </p>
          <div class="about-stats-row">
            <div class="about-stat-item">
              <span class="about-stat-number">100%</span>
              <span class="about-stat-label">Verified Service Hours</span>
            </div>
            <div class="about-stat-item">
              <span class="about-stat-number">4+</span>
              <span class="about-stat-label">Core Impact Causes</span>
            </div>
            <div class="about-stat-item">
              <span class="about-stat-number">Direct</span>
              <span class="about-stat-label">Community Impact</span>
            </div>
          </div>
        </div>

        <div class="about-cards-col">
          <div class="about-feature-box">
            <div class="about-feature-icon">
              <i data-lucide="heart-handshake"></i>
            </div>
            <div class="about-feature-info">
              <h3>Connect With Purpose</h3>
              <p>Discover local initiatives aligned with your personal passions, schedule, and skillset.</p>
            </div>
          </div>

          <div class="about-feature-box">
            <div class="about-feature-icon">
              <i data-lucide="shield-check"></i>
            </div>
            <div class="about-feature-info">
              <h3>Verified Organizations</h3>
              <p>Every nonprofit and community group is reviewed by administrators to ensure meaningful and safe volunteer experiences.</p>
            </div>
          </div>

          <div class="about-feature-box">
            <div class="about-feature-icon">
              <i data-lucide="award"></i>
            </div>
            <div class="about-feature-info">
              <h3>Track Your Real Impact</h3>
              <p>Log attendance, earn verified community service hours, and download official participation reports anytime.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- FAQs Section -->
  <section id="faqs" class="section-container max-w-700" style="padding-top: 60px; padding-bottom: 40px;">
    <div class="section-heading text-center" style="margin-bottom: 32px;">
      <h2 class="section-title">Frequently Asked Questions</h2>
    </div>

    <div class="faq-accordion">
      <div class="faq-item">
        <button type="button" class="faq-question" onclick="toggleFaq(this)">
          <span>What is HelpHive?</span>
          <i data-lucide="chevron-down"></i>
        </button>
        <div class="faq-answer">
          <p>HelpHive is a centralized community platform connecting compassionate volunteers with local community organizations, causes, and outreach programs.</p>
        </div>
      </div>

      <div class="faq-item">
        <button type="button" class="faq-question" onclick="toggleFaq(this)">
          <span>How do I find volunteer opportunities?</span>
          <i data-lucide="chevron-down"></i>
        </button>
        <div class="faq-answer">
          <p>Go to the Search Opportunities page and use the keyword search bar or filter by Cause Area, Location Type (On-site/Remote), Duration, and Good For categories.</p>
        </div>
      </div>

      <div class="faq-item">
        <button type="button" class="faq-question" onclick="toggleFaq(this)">
          <span>How are volunteer hours verified?</span>
          <i data-lucide="chevron-down"></i>
        </button>
        <div class="faq-answer">
          <p>After you attend a scheduled activity, the organizing organization reviews attendance and marks your hours as verified in the HelpHive dashboard.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Yellow Banner Section -->
  <section class="banner-section" style="padding-top: 20px; padding-bottom: 60px;">
    <div class="banner-inner">
      <h2 class="banner-title">A Kinder World Begins With You.</h2>
      <p class="banner-sub">Find a cause you care about and turn your kindness into action.</p>
      <a href="/opportunities" class="btn btn-banner" style="text-decoration: none; display: inline-block;">Learn About HelpHive</a>
    </div>
  </section>
</section>

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
      <h2 class="section-title">Find Your Place in the Hive and Make a Difference</h2>
      <p class="section-subtitle">Everyone has something to give. Find a cause close to your heart and make a difference, one good deed at a time.</p>
    </div>

    <div class="hive-cards-grid">
      <!-- Card 1 -->
      <div class="hive-feature-card">
        <div class="hive-card-icon-wrap yellow">
          <i data-lucide="users" class="hive-icon"></i>
        </div>
        <h3 class="hive-card-title">Join the Hive</h3>
        <p class="hive-card-desc">Become part of a caring community where people share their time and talents to make a difference.</p>
        <a href="#" class="hive-card-arrow" onclick="openModal('signup-modal'); return false;">
          <i data-lucide="arrow-up-right"></i>
        </a>
      </div>

      <!-- Card 2 -->
      <div class="hive-feature-card highlighted">
        <div class="hive-card-icon-wrap green">
          <i data-lucide="heart-handshake" class="hive-icon"></i>
        </div>
        <h3 class="hive-card-title">Volunteer Opportunities</h3>
        <p class="hive-card-desc">Find meaningful volunteer activities that match your interests and help make your community a better place.</p>
        <a href="/opportunities" class="hive-card-arrow">
          <i data-lucide="arrow-up-right"></i>
        </a>
      </div>

      <!-- Card 3 -->
      <div class="hive-feature-card">
        <div class="hive-card-icon-wrap blue">
          <i data-lucide="building-2" class="hive-icon"></i>
        </div>
        <h3 class="hive-card-title">Register Your Organization</h3>
        <p class="hive-card-desc">Share volunteer opportunities and connect with people who want to help bring your community projects to life.</p>
        <a href="/register?role=organization" class="hive-card-arrow">
          <i data-lucide="arrow-up-right"></i>
        </a>
      </div>
    </div>
  </section>

  <!-- Causes You Can Support Section -->
  <section class="causes-section-bg">
    <div class="section-container">
      <div class="section-heading text-center">
        <h2 class="section-title">Causes You Can Support</h2>
        <p class="section-subtitle">Explore key initiatives that bring positive transformation across neighborhoods and beyond.</p>
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

  <!-- Yellow Banner Section -->
  <section class="banner-section">
    <div class="banner-inner">
      <h2 class="banner-title">A Kinder World Begins With You.</h2>
      <p class="banner-sub">Find a cause you care about and turn your kindness into action.</p>
      <a href="#about" class="btn btn-banner" style="text-decoration: none; display: inline-block;">Learn About HelpHive</a>
    </div>
  </section>

  <!-- About Section -->
  <section id="about" class="section-container max-w-700" style="padding-top: 60px; padding-bottom: 40px;">
    <div class="section-heading text-center">
      <h2 class="section-title">About HelpHive</h2>
      <p class="section-subtitle">One Hive. One Heart. One Purpose. Together, we make good happen.</p>
    </div>
    <div class="content-card">
      <p style="margin-bottom: 16px;">HelpHive is a community volunteering platform designed to connect volunteers with local nonprofit organizations and community groups.</p>
      <p>Our goal is to build a functional platform where organizations can publish volunteering opportunities, volunteers can discover and register for activities, and completed volunteer hours can be recorded, verified, and celebrated.</p>
    </div>
  </section>

  <!-- FAQs Section -->
  <section id="faqs" class="section-container max-w-700" style="padding-top: 20px; padding-bottom: 60px;">
    <div class="section-heading text-center">
      <h2 class="section-title">Frequently Asked Questions</h2>
      <p class="section-subtitle">Find quick answers about HelpHive volunteering, registrations, and hour verification.</p>
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
</section>

<?php
declare(strict_types=1);

$opp = $opportunity ?? [
    'id' => 1,
    'title' => 'Coastal Cleanup Drive',
    'org_name' => 'Blue Shores Initiative',
    'cause_area' => 'Environment',
    'location' => 'Lapu-Lapu City',
    'start_at' => '2026-10-31 07:00:00',
    'end_at' => '2026-10-31 10:00:00',
    'capacity' => 50,
    'description' => 'Collect litter along the coast to protect marine life and keep local beaches clean.',
    'requirements' => 'Wear comfortable clothing, sunscreen, and bring water. Gloves and collection bags will be provided.'
];
?>
<!-- 3. OPPORTUNITY DETAILS VIEW (Figma 308:42930 / 381:11170) -->
<section id="view-details" class="page-view active" style="display: block;">
  <div class="section-container">
    <!-- Back navigation -->
    <div class="details-top-nav">
      <a href="/opportunities" class="btn-back" style="text-decoration: none;">
        <i data-lucide="arrow-left"></i> Back to opportunities
      </a>
      <span class="view-tag">OPPORTUNITY DETAILS</span>
    </div>

    <!-- Main Details Card -->
    <div class="opportunity-detail-card" id="opportunity-detail-content">
      <div class="detail-hero-banner">
        <img src="<?= e($opp['image_url'] ?? asset('assets/images/3f7a0de487a85a9f0d684274dbdcef0b89a3b92d.png')) ?>" alt="<?= e($opp['title']) ?>" onerror="this.src='https://images.unsplash.com/photo-1618477461853-cf6ed80faba5?w=600&auto=format&fit=crop&q=80'">
      </div>
      <div class="detail-body">
        <div class="detail-header-section">
          <span class="tag-badge cause" style="margin-bottom: 12px; display: inline-block;"><?= e($opp['cause_area']) ?></span>
          <h1 class="detail-title"><?= e($opp['title']) ?></h1>
          <p class="detail-org-name"><?= e($opp['org_name'] ?? 'HelpHive Partner') ?></p>
          <p class="opp-desc" style="font-size: 16px;"><?= e($opp['description']) ?></p>
        </div>

        <!-- Quick Info Bar (Figma Node 308:42930) -->
        <div class="quick-info-grid">
          <div class="info-box">
            <div class="label">Date & Time</div>
            <div class="val"><?= e(date('M d, Y • g:i A', strtotime($opp['start_at'] ?? 'now'))) ?></div>
          </div>
          <div class="info-box">
            <div class="label">Duration</div>
            <div class="val"><?= e($opp['duration'] ?? 'One day') ?></div>
          </div>
          <div class="info-box">
            <div class="label">Location</div>
            <div class="val"><?= e($opp['location']) ?></div>
          </div>
          <div class="info-box">
            <div class="label">Available Slots</div>
            <div class="val"><?= (int) ($opp['capacity'] ?? 0) ?> volunteers</div>
          </div>
          <div class="info-box">
            <div class="label">Suitable for</div>
            <div class="val"><?= e($opp['good_for'] ?? 'Individuals & Groups') ?></div>
          </div>
        </div>

        <h3 class="detail-section-title">About this opportunity</h3>
        <p class="detail-text"><?= nl2br(e($opp['description'])) ?></p>

        <?php if (!empty($opp['requirements'])): ?>
          <h3 class="detail-section-title">Requirements & What to Bring</h3>
          <p class="detail-text"><?= nl2br(e($opp['requirements'])) ?></p>
        <?php endif; ?>

        <h3 class="detail-section-title">What you'll do</h3>
        <ul class="bullet-checklist">
          <li>
            <i data-lucide="check" class="checklist-icon"></i>
            <span>Participate actively in the scheduled community service activities.</span>
          </li>
          <li>
            <i data-lucide="check" class="checklist-icon"></i>
            <span>Work collaboratively with fellow registered volunteers and staff.</span>
          </li>
          <li>
            <i data-lucide="check" class="checklist-icon"></i>
            <span>Follow the organizer's safety guidelines and check in on-site.</span>
          </li>
        </ul>

        <!-- After the activity Notice (Figma TC_HH_011) -->
        <div class="after-activity-notice">
          <h4>After the activity</h4>
          <p>Your attendance and volunteer hours will be reviewed by the organization before they appear as verified hours in your HelpHive account.</p>
        </div>

        <!-- Action Button -->
        <div class="detail-cta-wrap">
          <?php if (Auth::user()): ?>
            <form action="/opportunities/<?= (int) $opp['id'] ?>/register" method="POST">
              <?= Csrf::field() ?>
              <button type="submit" class="btn btn-cause btn-full" style="padding: 16px; font-size: 18px;">
                Confirm Registration Now
              </button>
            </form>
          <?php else: ?>
            <button type="button" class="btn btn-cause btn-full" style="padding: 16px; font-size: 18px;" onclick="openModal('login-modal')">
              Log In to Register
            </button>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>

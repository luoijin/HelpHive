<?php
declare(strict_types=1);

$oppTitle = $title ?? 'Volunteer Opportunity';
?>
<!-- 5. REGISTRATION SUBMITTED CONFIRMATION (Figma 381:8312) -->
<section id="view-registration-confirmed" class="page-view active" style="display: block;">
  <div class="section-container max-w-700">
    <a href="/opportunities" class="btn-back" style="text-decoration: none;">
      <i data-lucide="arrow-left"></i> Back to opportunities
    </a>

    <div class="confirmation-card">
      <div class="confirm-icon-wrap">
        <i data-lucide="check-circle" class="confirm-icon"></i>
      </div>
      <span class="sub-header-pill">REGISTRATION SUBMITTED</span>
      <h2 class="confirm-title">Registration Submitted!</h2>
      <p class="confirm-desc" id="confirm-opp-name">
        Your registration for <strong><?= e($oppTitle) ?></strong> has been submitted successfully.
      </p>

      <div class="confirm-status-card">
        <div class="status-row">
          <span class="status-label">Application Status</span>
          <span class="status-badge pending">Pending Review</span>
        </div>
        <div class="whats-next-box">
          <h4>What's Next?</h4>
          <p>Please wait while the organization reviews your registration. You'll be able to check your application status in your account.</p>
        </div>
      </div>

      <div class="confirm-actions">
        <a href="/volunteer/registrations" class="btn btn-primary" style="text-decoration: none;">View My Applications</a>
        <p class="confirm-notice">Submission is not approval. Your registration will be reviewed by the organizer.</p>
      </div>
    </div>
  </div>
</section>

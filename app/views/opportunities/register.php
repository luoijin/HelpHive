<?php
declare(strict_types=1);

$opp = $opportunity ?? [
    'id' => 1,
    'title' => 'Coastal Cleanup Drive',
    'org_name' => 'Blue Shores Initiative',
    'start_at' => '2026-10-31 07:00:00',
];
$currentUser = Auth::user();
$userEmail = $currentUser['email'] ?? '';
if ($userEmail === '' && !empty($currentUser['id'])) {
    $u = (new UserModel(Database::connection()))->findById((int) $currentUser['id']);
    $userEmail = $u['email'] ?? '';
}
?>
<!-- 4. VOLUNTEER REGISTRATION FORM (Figma 381:11859) -->
<section id="view-volunteer-reg" class="page-view active" style="display: block;">
  <div class="section-container max-w-700">
    <a href="/opportunities/<?= (int) $opp['id'] ?>" class="btn-back" style="text-decoration: none;">
      <i data-lucide="arrow-left"></i> Back to Opportunity
    </a>

    <div class="form-card-container">
      <div class="form-header">
        <span class="sub-header-pill">VOLUNTEER REGISTRATION</span>
        <h2 class="form-title">Fill out this form</h2>
        <p class="form-required-notice">*All fields are required</p>
      </div>

      <form action="/opportunities/<?= (int) $opp['id'] ?>/register" method="POST" id="volunteer-registration-form">
        <?= Csrf::field() ?>

        <!-- Selected Opportunity Header Preview -->
        <div class="selected-opportunity-box">
          <span class="box-label">Registering For:</span>
          <h4 id="reg-form-opp-title"><?= e($opp['title']) ?></h4>
          <p id="reg-form-opp-org"><?= e($opp['org_name'] ?? 'HelpHive Partner') ?> &bull; <?= e(date('M d, Y', strtotime($opp['start_at'] ?? 'now'))) ?></p>
        </div>

        <fieldset class="form-section">
          <legend class="form-section-legend">Your Information</legend>
          <p class="form-section-desc">Use your account details to complete your registration.</p>

          <div class="form-group">
            <label for="reg-full-name">Full name*</label>
            <input type="text" id="reg-full-name" name="full_name" required placeholder="Enter your full name" value="<?= e($currentUser['name'] ?? '') ?>">
          </div>

          <div class="form-group">
            <label for="reg-email">Email Address*</label>
            <input type="email" id="reg-email" name="email" required placeholder="johnSmith@example.com" value="<?= e($userEmail) ?>">
          </div>

          <div class="form-group">
            <label for="reg-phone">Contact Number*</label>
            <input type="tel" id="reg-phone" name="phone" required placeholder="+63 9XX XXX XXXX">
          </div>
        </fieldset>

        <fieldset class="form-section">
          <legend class="form-section-legend">A Few More Details</legend>

          <div class="form-group">
            <label for="reg-reason">Why would you like to volunteer?</label>
            <textarea id="reg-reason" name="reason" rows="3" placeholder="Tell the organizer a little about your interest"></textarea>
          </div>

          <div class="form-group">
            <label for="reg-notes">Is there anything you want to ask or tell us?</label>
            <textarea id="reg-notes" name="notes" rows="2" placeholder="Let us know what's on your mind"></textarea>
          </div>

          <div class="checkbox-group">
            <label class="custom-checkbox">
              <input type="checkbox" name="agree_attendance" required checked>
              <span class="checkmark"></span>
              <span class="checkbox-label">I confirm that I can attend the scheduled activity.</span>
            </label>

            <label class="custom-checkbox">
              <input type="checkbox" name="agree_guidelines" required checked>
              <span class="checkmark"></span>
              <span class="checkbox-label">I agree to follow the organizer's instructions or safety guidelines.</span>
            </label>
          </div>
        </fieldset>

        <div class="form-submit-wrap">
          <button type="submit" class="btn btn-primary btn-full">Submit Registration</button>
          <p class="submit-disclaimer">Review your details before submitting.</p>
        </div>
      </form>
    </div>
  </div>
</section>

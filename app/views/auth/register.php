<?php
declare(strict_types=1);

$requestedRole = $_GET['role'] ?? 'volunteer';
?>
<!-- DEDICATED SIGN UP VIEW -->
<section id="view-register" class="page-view active" style="display: block;">
  <div class="section-container max-w-700">
    <div class="form-card-container" style="max-width: 520px; margin: 40px auto;">
      <div class="modal-header" style="text-align: center; margin-bottom: 24px;">
        <div class="modal-logo-wrap">
          <span class="brand-name modal-logo-text"><span class="logo-help">Help</span><span class="logo-hive">Hive</span></span>
        </div>
        <div class="modal-divider"></div>
        <h3 class="modal-heading" style="font-size: 22px; margin-top: 12px;">Create your account</h3>
        <p class="modal-switch-text">
          Already have an account? <a href="/login">Log In</a>
        </p>
      </div>

      <form action="/register" method="POST">
        <?= Csrf::field() ?>
        <div class="form-row-2">
          <div class="form-group">
            <label for="page-reg-first-name">First Name*</label>
            <input type="text" id="page-reg-first-name" name="first_name" required placeholder="John">
          </div>
          <div class="form-group">
            <label for="page-reg-last-name">Last Name*</label>
            <input type="text" id="page-reg-last-name" name="last_name" required placeholder="Smith">
          </div>
        </div>

        <div class="form-group">
          <label for="page-reg-email">Email Address*</label>
          <input type="email" id="page-reg-email" name="email" required placeholder="Enter your email">
        </div>

        <div class="form-group">
          <label for="page-reg-password">Password*</label>
          <input type="password" id="page-reg-password" name="password" minlength="8" required placeholder="Minimum 8 characters (letters and numbers)">
        </div>

        <div class="form-group">
          <label for="page-reg-password-confirm">Confirm Password*</label>
          <input type="password" id="page-reg-password-confirm" name="password_confirmation" minlength="8" required placeholder="Re-enter your password">
        </div>

        <div class="form-group">
          <label for="page-reg-role-input">I want to join as*</label>
          <div class="custom-role-dropdown" id="page-reg-role-dropdown">
            <button type="button" class="role-dropdown-trigger" onclick="toggleRoleDropdown('page-reg-role-dropdown'); event.stopPropagation();" aria-haspopup="listbox" aria-expanded="false">
              <div class="role-trigger-left">
                <div class="role-icon-box" id="page-reg-role-icon">
                  <i data-lucide="<?= $requestedRole === 'organization' ? 'building-2' : 'user' ?>"></i>
                </div>
                <div class="role-trigger-text">
                  <span class="role-title" id="page-reg-role-title"><?= $requestedRole === 'organization' ? 'Organization' : 'Volunteer' ?></span>
                  <span class="role-desc" id="page-reg-role-desc"><?= $requestedRole === 'organization' ? 'Publish opportunities and verify hours' : 'Discover and join opportunities' ?></span>
                </div>
              </div>
              <i data-lucide="chevron-down" class="role-chevron"></i>
            </button>
            <input type="hidden" name="role" id="page-reg-role-input" value="<?= e($requestedRole) ?>">

            <div class="role-dropdown-menu" role="listbox">
              <div class="role-dropdown-option <?= $requestedRole !== 'organization' ? 'selected' : '' ?>" data-role="volunteer" onclick="selectRole('page-reg-role-dropdown', 'volunteer', 'Volunteer', 'Discover and join opportunities', 'user')" role="option" aria-selected="<?= $requestedRole !== 'organization' ? 'true' : 'false' ?>">
                <div class="role-option-icon">
                  <i data-lucide="user"></i>
                </div>
                <div class="role-option-info">
                  <span class="role-option-title">Volunteer</span>
                  <span class="role-option-desc">Discover and join opportunities</span>
                </div>
                <i data-lucide="check" class="role-check-icon"></i>
              </div>

              <div class="role-dropdown-option <?= $requestedRole === 'organization' ? 'selected' : '' ?>" data-role="organization" onclick="selectRole('page-reg-role-dropdown', 'organization', 'Organization', 'Publish opportunities and verify hours', 'building-2')" role="option" aria-selected="<?= $requestedRole === 'organization' ? 'true' : 'false' ?>">
                <div class="role-option-icon">
                  <i data-lucide="building-2"></i>
                </div>
                <div class="role-option-info">
                  <span class="role-option-title">Organization</span>
                  <span class="role-option-desc">Publish opportunities and verify hours</span>
                </div>
                <i data-lucide="check" class="role-check-icon"></i>
              </div>
            </div>
          </div>
        </div>

        <button type="submit" class="btn btn-primary btn-full modal-submit-btn">Sign Up</button>

        <p class="modal-legal-notice" style="margin-top: 16px; text-align: center; font-size: 13px; color: var(--slate-muted);">
          By creating an account, you agree to our <a href="#">Terms of Use</a> and <a href="#">Privacy Policy</a>.
        </p>
      </form>
    </div>
  </div>
</section>

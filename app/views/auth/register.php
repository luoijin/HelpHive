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
          <input type="password" id="page-reg-password" name="password" minlength="8" required placeholder="Minimum 8 characters">
        </div>

        <div class="form-group">
          <label for="page-reg-role">I want to join as*</label>
          <select id="page-reg-role" name="role" required>
            <option value="volunteer" <?= $requestedRole === 'volunteer' ? 'selected' : '' ?>>Volunteer (Discover and join opportunities)</option>
            <option value="organization" <?= $requestedRole === 'organization' ? 'selected' : '' ?>>Organization (Publish opportunities and verify hours)</option>
          </select>
        </div>

        <button type="submit" class="btn btn-primary btn-full modal-submit-btn">Sign Up</button>

        <p class="modal-legal-notice" style="margin-top: 16px; text-align: center; font-size: 13px; color: var(--slate-muted);">
          By creating an account, you agree to our <a href="#">Terms of Use</a> and <a href="#">Privacy Policy</a>.
        </p>
      </form>
    </div>
  </div>
</section>

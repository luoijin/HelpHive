<?php
declare(strict_types=1);
?>
<!-- DEDICATED LOGIN VIEW -->
<section id="view-login" class="page-view active" style="display: block;">
  <div class="section-container max-w-700">
    <div class="form-card-container" style="max-width: 480px; margin: 40px auto;">
      <div class="modal-header" style="text-align: center; margin-bottom: 24px;">
        <div class="modal-logo-wrap">
          <span class="brand-name modal-logo-text"><span class="logo-help">Help</span><span class="logo-hive">Hive</span></span>
        </div>
        <div class="modal-divider"></div>
        <h3 class="modal-heading" style="font-size: 22px; margin-top: 12px;">Log in to HelpHive</h3>
        <p class="modal-switch-text">
          Don't have an account? <a href="/register">Sign Up</a>
        </p>
      </div>

      <form action="/login" method="POST">
        <?= Csrf::field() ?>
        <div class="form-group">
          <label for="page-login-email">Email Address</label>
          <input type="email" id="page-login-email" name="email" required placeholder="Enter your email">
        </div>

        <div class="form-group">
          <label for="page-login-password">Password</label>
          <input type="password" id="page-login-password" name="password" required placeholder="Enter your password">
        </div>

        <div class="form-row-between" style="display: flex; justify-content: flex-end; margin-bottom: 16px;">
          <a href="#" class="link-muted" onclick="alert('Please contact support to reset your password.'); return false;">Forgot Password?</a>
        </div>

        <button type="submit" class="btn btn-primary btn-full modal-submit-btn">Log In</button>

        <p class="modal-legal-notice" style="margin-top: 16px; text-align: center; font-size: 13px; color: var(--slate-muted);">
          By continuing, you agree to our <a href="#">Terms of Use</a> and <a href="#">Privacy Policy</a>.
        </p>
      </form>
    </div>
  </div>
</section>

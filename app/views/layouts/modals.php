<?php
declare(strict_types=1);
?>
<!-- ================= MODAL: LOGIN ================= -->
<div class="modal-backdrop" id="login-modal" onclick="handleBackdropClick(event, 'login-modal')">
  <div class="modal-card">
    <button type="button" class="modal-close-btn" onclick="closeModal('login-modal')"><i data-lucide="x"></i></button>

    <div class="modal-header">
      <div class="modal-logo-wrap">
        <span class="brand-name modal-logo-text"><span class="logo-help">Help</span><span class="logo-hive">Hive</span></span>
      </div>
      <div class="modal-divider"></div>
      <h3 class="modal-heading">Log in or create your account</h3>
      <p class="modal-switch-text">
        Don't have an account? <a href="#" onclick="switchModal('login-modal', 'signup-modal'); return false;">Sign Up</a>
      </p>
    </div>

    <!-- Social Logins -->
    <div class="social-login-group">
      <button type="button" class="btn-social google" onclick="alert('Google Sign-In integration ready')">
        <svg class="social-svg" viewBox="0 0 24 24" width="20" height="20">
          <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
          <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
          <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
          <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
        </svg>
        <span>Continue with Google</span>
      </button>

      <button type="button" class="btn-social facebook" onclick="alert('Facebook Sign-In integration ready')">
        <svg class="social-svg" viewBox="0 0 24 24" width="20" height="20" fill="#1877F2">
          <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
        </svg>
        <span>Continue with Facebook</span>
      </button>
    </div>

    <div class="modal-separator">
      <span>OR</span>
    </div>

    <!-- Login Form -->
    <form action="/login" method="POST">
      <?= Csrf::field() ?>
      <div class="form-group">
        <label for="login-email">Email Address</label>
        <input type="email" id="login-email" name="email" required placeholder="Enter your email">
      </div>

      <div class="form-group">
        <label for="login-password">Password</label>
        <input type="password" id="login-password" name="password" required placeholder="Enter your password">
      </div>

      <div class="form-row-between">
        <a href="#" class="link-muted" onclick="alert('Please contact support to reset your password.'); return false;">Forgot Password?</a>
      </div>

      <button type="submit" class="btn btn-primary btn-full modal-submit-btn">Log In</button>

      <p class="modal-legal-notice">
        By continuing, you agree to our <a href="#">Terms of Use</a> and <a href="#">Privacy Policy</a>.
      </p>
    </form>
  </div>
</div>

<!-- ================= MODAL: SIGN UP ================= -->
<div class="modal-backdrop" id="signup-modal" onclick="handleBackdropClick(event, 'signup-modal')">
  <div class="modal-card">
    <button type="button" class="modal-close-btn" onclick="closeModal('signup-modal')"><i data-lucide="x"></i></button>

    <div class="modal-header">
      <div class="modal-logo-wrap">
        <span class="brand-name modal-logo-text"><span class="logo-help">Help</span><span class="logo-hive">Hive</span></span>
      </div>
      <div class="modal-divider"></div>
      <h3 class="modal-heading">Create your account</h3>
      <p class="modal-switch-text">
        Already have an account? <a href="#" onclick="switchModal('signup-modal', 'login-modal'); return false;">Log In</a>
      </p>
    </div>

    <!-- Social Logins -->
    <div class="social-login-group">
      <button type="button" class="btn-social google" onclick="alert('Google Sign-In integration ready')">
        <svg class="social-svg" viewBox="0 0 24 24" width="20" height="20">
          <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
          <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
          <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l2.85-2.22.81-.63z"/>
          <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84c.87-2.6 3.3-4.52 6.16-4.52z"/>
        </svg>
        <span>Continue with Google</span>
      </button>

      <button type="button" class="btn-social facebook" onclick="alert('Facebook Sign-In integration ready')">
        <svg class="social-svg" viewBox="0 0 24 24" width="20" height="20" fill="#1877F2">
          <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
        </svg>
        <span>Continue with Facebook</span>
      </button>
    </div>

    <div class="modal-separator">
      <span>OR</span>
    </div>

    <!-- Sign Up Form -->
    <form action="/register" method="POST">
      <?= Csrf::field() ?>
      <div class="form-row-2">
        <div class="form-group">
          <label for="signup-first-name">First Name</label>
          <input type="text" id="signup-first-name" name="first_name" required placeholder="John">
        </div>
        <div class="form-group">
          <label for="signup-last-name">Last Name</label>
          <input type="text" id="signup-last-name" name="last_name" required placeholder="Smith">
        </div>
      </div>

      <div class="form-group">
        <label for="signup-email">Email Address</label>
        <input type="email" id="signup-email" name="email" required placeholder="Enter your email">
      </div>

      <div class="form-group">
        <label for="signup-password">Password*</label>
        <input type="password" id="signup-password" name="password" minlength="8" required placeholder="Minimum 8 characters (letters and numbers)">
      </div>

      <div class="form-group">
        <label for="signup-password-confirm">Confirm Password*</label>
        <input type="password" id="signup-password-confirm" name="password_confirmation" minlength="8" required placeholder="Re-enter your password">
      </div>

      <div class="form-group">
        <label for="signup-role-input">I want to join as</label>
        <div class="custom-role-dropdown" id="signup-role-dropdown">
          <button type="button" class="role-dropdown-trigger" onclick="toggleRoleDropdown('signup-role-dropdown'); event.stopPropagation();" aria-haspopup="listbox" aria-expanded="false">
            <div class="role-trigger-left">
              <div class="role-icon-box" id="signup-role-icon">
                <i data-lucide="user"></i>
              </div>
              <div class="role-trigger-text">
                <span class="role-title" id="signup-role-title">Volunteer</span>
                <span class="role-desc" id="signup-role-desc">Find and join opportunities</span>
              </div>
            </div>
            <i data-lucide="chevron-down" class="role-chevron"></i>
          </button>
          <input type="hidden" name="role" id="signup-role-input" value="volunteer">

          <div class="role-dropdown-menu" role="listbox">
            <div class="role-dropdown-option selected" data-role="volunteer" onclick="selectRole('signup-role-dropdown', 'volunteer', 'Volunteer', 'Find and join opportunities', 'user')" role="option" aria-selected="true">
              <div class="role-option-icon">
                <i data-lucide="user"></i>
              </div>
              <div class="role-option-info">
                <span class="role-option-title">Volunteer</span>
                <span class="role-option-desc">Find and join opportunities</span>
              </div>
              <i data-lucide="check" class="role-check-icon"></i>
            </div>

            <div class="role-dropdown-option" data-role="organization" onclick="selectRole('signup-role-dropdown', 'organization', 'Organization', 'Publish opportunities and verify hours', 'building-2')" role="option" aria-selected="false">
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

      <p class="modal-legal-notice">
        By creating an account, you agree to our <a href="#">Terms of Use</a> and <a href="#">Privacy Policy</a>.
      </p>
    </form>
  </div>
</div>

<!-- Flash Messages -->
<?php
$flashes = Flash::all();
if (!empty($flashes)):
  foreach ($flashes as $type => $messages):
    foreach ($messages as $msg):
?>
  <div class="toast-notification show" style="background: <?= $type === 'error' ? '#ef4444' : '#10b981' ?>;">
    <i data-lucide="<?= $type === 'error' ? 'alert-circle' : 'check-circle' ?>" class="toast-icon"></i>
    <span class="toast-msg"><?= e($msg) ?></span>
  </div>
<?php
    endforeach;
  endforeach;
endif;
?>

<!-- Toast Notification element -->
<div class="toast-notification" id="toast">
  <i data-lucide="check-circle" class="toast-icon"></i>
  <span class="toast-msg" id="toast-text"></span>
</div>

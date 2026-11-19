<?php
declare(strict_types=1);

$user = Auth::user() ?? ($user ?? []);
?>
<!-- REGISTER ORGANIZATION VIEW (User-Side Form with Abstract Background) -->
<section id="view-register-org" class="page-view active org-register-page-section">
  <div class="org-register-container">
    <div class="org-register-card">
      <div class="org-form-header">
        <h1 class="org-form-main-title">REGISTER YOUR ORGANIZATION</h1>
        <p class="org-form-main-subtitle">Bring your organization to HelpHive and connect with volunteers who care about your cause.</p>
      </div>

      <form action="/org/register" method="POST" id="org-registration-form" enctype="multipart/form-data">
        <?= Csrf::field() ?>
        <input type="hidden" name="role" value="organization">

        <!-- Section 1: Organization Information -->
        <div class="form-section-block">
          <div class="form-section-header">
            <h2 class="form-section-title">Organization Information</h2>
            <p class="form-section-desc">Tell us about your organization and the work you do.</p>
          </div>

          <!-- Organization Logo -->
          <div class="form-group">
            <label class="form-field-label">Organization Logo</label>
            <div class="upload-dropzone" id="logo-dropzone" onclick="document.getElementById('org-logo-input').click()">
              <input type="file" id="org-logo-input" name="logo" accept="image/png,image/jpeg" style="display: none;" onchange="handleLogoUpload(this)">
              <div class="upload-dropzone-inner" id="logo-dropzone-content">
                <div class="upload-icon-circle">
                  <i data-lucide="image"></i>
                </div>
                <p class="upload-title">Upload an Opportunity Image</p>
                <p class="upload-subtitle">Choose an image from your device</p>
                <span class="upload-hint">JPG, PNG &middot; Recommended: landscape image</span>
              </div>
              <div id="logo-preview-wrapper" style="display: none; flex-direction: column; align-items: center; gap: 12px;">
                <img id="logo-preview-img" src="" alt="Organization Logo Preview" style="max-height: 100px; max-width: 220px; object-fit: contain; border-radius: var(--radius-md); border: 1px solid var(--slate-border); padding: 4px; background: #ffffff;">
                <button type="button" class="btn btn-ghost" style="padding: 6px 14px; font-size: 13px; border-radius: var(--radius-full);" onclick="event.stopPropagation(); resetLogoUpload();">Remove image</button>
              </div>
            </div>
          </div>

          <!-- Organization Name * -->
          <div class="form-group">
            <label for="org-name" class="form-field-label">Organization Name <span class="text-danger">*</span></label>
            <input type="text" id="org-name" name="organization_name" required placeholder="e.g. Green Future Initiative" class="form-control-unified">
          </div>

          <!-- Organization Type * -->
          <div class="form-group">
            <label for="org-type" class="form-field-label">Organization Type <span class="text-danger">*</span></label>
            <select id="org-type" name="organization_type" required class="form-control-unified">
              <option value="" disabled selected>Select organization type</option>
              <option value="Nonprofit Organization">Nonprofit Organization</option>
              <option value="Community Group">Community Group</option>
              <option value="School / Educational Institution">School / Educational Institution</option>
              <option value="Government Organization">Government Organization</option>
              <option value="Other">Other</option>
            </select>
          </div>

          <!-- About Your Organization * -->
          <div class="form-group">
            <label for="org-about" class="form-field-label">About Your Organization <span class="text-danger">*</span></label>
            <textarea id="org-about" name="description" rows="4" required placeholder="Describe your mission and how your organization helps the community." class="form-control-unified"></textarea>
          </div>

          <!-- Cause Area * -->
          <div class="form-group">
            <label for="org-cause" class="form-field-label">Cause Area <span class="text-danger">*</span></label>
            <select id="org-cause" name="cause_area" required class="form-control-unified">
              <option value="" disabled selected>Select primary cause area</option>
              <option value="Environment">Environment</option>
              <option value="Education">Education</option>
              <option value="Community Support">Community Support</option>
              <option value="Animal Welfare">Animal Welfare</option>
            </select>
          </div>
        </div>

        <!-- Section 2: Location and Contact -->
        <div class="form-section-block">
          <div class="form-section-header">
            <h2 class="form-section-title">Location and Contact</h2>
            <p class="form-section-desc">Help volunteers learn where you're based and how to reach you.</p>
          </div>

          <!-- City / Province * -->
          <div class="form-group">
            <label for="org-location" class="form-field-label">City / Province <span class="text-danger">*</span></label>
            <input type="text" id="org-location" name="location" required placeholder="e.g. Cebu City, Cebu" class="form-control-unified">
          </div>

          <!-- Street Address -->
          <div class="form-group">
            <label for="org-street" class="form-field-label">Street Address</label>
            <input type="text" id="org-street" name="street_address" placeholder="Street, building, or office address (optional)" class="form-control-unified">
          </div>

          <!-- Organization Email * -->
          <div class="form-group">
            <label for="org-email" class="form-field-label">Organization Email <span class="text-danger">*</span></label>
            <input type="email" id="org-email" name="email" required value="<?= e($user['email'] ?? '') ?>" placeholder="contact@organization.org" class="form-control-unified">
          </div>

          <!-- Contact Number -->
          <div class="form-group">
            <label for="org-phone" class="form-field-label">Contact Number</label>
            <input type="tel" id="org-phone" name="contact_number" placeholder="e.g. 09Xx xxx xxxx" class="form-control-unified">
          </div>

          <!-- Website or Social Media Page -->
          <div class="form-group">
            <label for="org-website" class="form-field-label">Website or Social Media Page</label>
            <input type="url" id="org-website" name="website" placeholder="https:// ... (optional)" class="form-control-unified">
          </div>
        </div>

        <!-- Section 3: Organization Representative -->
        <div class="form-section-block">
          <div class="form-section-header">
            <h2 class="form-section-title">Organization Representative</h2>
            <p class="form-section-desc">Who will manage your organization's HelpHive account?</p>
          </div>

          <!-- Contact Person's Full Name * -->
          <div class="form-group">
            <label for="org-rep-name" class="form-field-label">Contact Person's Full Name <span class="text-danger">*</span></label>
            <input type="text" id="org-rep-name" name="contact_person" required value="<?= e($user['full_name'] ?? $user['name'] ?? '') ?>" placeholder="Enter full name" class="form-control-unified">
          </div>

          <!-- Position / Role * -->
          <div class="form-group">
            <label for="org-rep-role" class="form-field-label">Position / Role <span class="text-danger">*</span></label>
            <input type="text" id="org-rep-role" name="position" required placeholder="e.g. Volunteer Coordinator" class="form-control-unified">
          </div>
        </div>

        <!-- Submit & Terms Agreement -->
        <div class="form-submit-block">
          <button type="submit" class="btn btn-primary btn-full org-submit-btn">Submit Organization Registration</button>
          <p class="form-terms-text">
            By submitting, you agree to HelpHive's Terms of Use and acknowledge its Privacy Policy.
          </p>
        </div>
      </form>
    </div>
  </div>
</section>

<script>
function handleLogoUpload(input) {
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = function(e) {
      const previewImg = document.getElementById('logo-preview-img');
      const previewWrapper = document.getElementById('logo-preview-wrapper');
      const dropzoneContent = document.getElementById('logo-dropzone-content');
      if (previewImg && previewWrapper && dropzoneContent) {
        previewImg.src = e.target.result;
        previewWrapper.style.display = 'flex';
        dropzoneContent.style.display = 'none';
      }
    };
    reader.readAsDataURL(input.files[0]);
  }
}

function resetLogoUpload() {
  const input = document.getElementById('org-logo-input');
  const previewWrapper = document.getElementById('logo-preview-wrapper');
  const dropzoneContent = document.getElementById('logo-dropzone-content');
  if (input) input.value = '';
  if (previewWrapper) previewWrapper.style.display = 'none';
  if (dropzoneContent) dropzoneContent.style.display = 'block';
}
</script>

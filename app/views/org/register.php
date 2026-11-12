<?php
declare(strict_types=1);
?>
<!-- 8. REGISTER ORGANIZATION VIEW (Figma 381:8965 & 308:40647) -->
<section id="view-register-org" class="page-view active" style="display: block;">
  <div class="section-container max-w-700">
    <div class="form-card-container">
      <div class="form-header">
        <span class="sub-header-pill">ORGANIZATION PARTNERSHIP</span>
        <h2 class="form-title">Register Your Organization</h2>
        <p class="form-subtitle">Connect with thousands of compassionate volunteers ready to empower your mission.</p>
      </div>

      <form action="/register" method="POST" id="org-registration-form">
        <?= Csrf::field() ?>
        <input type="hidden" name="role" value="organization">

        <div class="form-group">
          <label for="org-name">Organization Name*</label>
          <input type="text" id="org-name" name="organization_name" required placeholder="e.g. Blue Shores Initiative">
        </div>

        <div class="form-group">
          <label for="org-type">Organization Type*</label>
          <select id="org-type" name="organization_type" required>
            <option value="Nonprofit">Nonprofit Organization</option>
            <option value="Community Group">Community Group</option>
            <option value="School / University">School / University</option>
            <option value="Government Body">Government Body</option>
            <option value="Social Enterprise">Social Enterprise</option>
          </select>
        </div>

        <div class="form-group">
          <label for="org-cause">Primary Cause Area*</label>
          <select id="org-cause" name="cause_area" required>
            <option value="Environment">Environment & Nature</option>
            <option value="Education">Education & Youth</option>
            <option value="Community Support">Community Support</option>
            <option value="Animal Welfare">Animal Welfare</option>
          </select>
        </div>

        <div class="form-group">
          <label for="org-location">City / Province*</label>
          <input type="text" id="org-location" name="location" required placeholder="e.g. Cebu City, Philippines">
        </div>

        <div class="form-group">
          <label for="org-contact-person">Contact Person's Full Name*</label>
          <input type="text" id="org-contact-person" name="full_name" required placeholder="e.g. Maria Santos">
        </div>

        <div class="form-group">
          <label for="org-email">Account & Organization Email*</label>
          <input type="email" id="org-email" name="email" required placeholder="contact@organization.org">
        </div>

        <div class="form-group">
          <label for="org-password">Account Password*</label>
          <input type="password" id="org-password" name="password" minlength="8" required placeholder="Minimum 8 characters">
        </div>

        <div class="form-group">
          <label for="org-about">About Your Organization*</label>
          <textarea id="org-about" name="description" rows="3" required placeholder="Tell volunteers about your purpose, mission, and ongoing projects..."></textarea>
        </div>

        <button type="submit" class="btn btn-primary btn-full">Submit Organization Registration</button>
      </form>
    </div>
  </div>
</section>

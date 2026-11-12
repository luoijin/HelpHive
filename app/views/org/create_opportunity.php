<?php
declare(strict_types=1);
?>
<!-- CREATE OPPORTUNITY VIEW -->
<section id="view-create-opportunity" class="page-view active" style="display: block;">
  <div class="section-container max-w-700">
    <a href="/org/opportunities" class="btn-back" style="text-decoration: none;">
      <i data-lucide="arrow-left"></i> Back to Opportunities
    </a>

    <div class="form-card-container">
      <div class="form-header">
        <span class="sub-header-pill">ORGANIZATION MANAGEMENT</span>
        <h2 class="form-title">Post a Volunteering Opportunity</h2>
        <p class="form-subtitle">Fill in the event details to attract and register dedicated volunteers.</p>
      </div>

      <form action="/org/opportunities/create" method="POST">
        <?= Csrf::field() ?>

        <div class="form-group">
          <label for="opp-title">Opportunity Title*</label>
          <input type="text" id="opp-title" name="title" required placeholder="e.g. Coastal Cleanup Drive">
        </div>

        <div class="form-group">
          <label for="opp-cause">Cause Area*</label>
          <select id="opp-cause" name="cause_area" required>
            <option value="Environment">Environment & Nature</option>
            <option value="Education">Education & Youth</option>
            <option value="Community Support">Community Support</option>
            <option value="Animal Welfare">Animal Welfare</option>
          </select>
        </div>

        <div class="form-group">
          <label for="opp-location">Location / Venue*</label>
          <input type="text" id="opp-location" name="location" required placeholder="e.g. Marigondon Beach, Lapu-Lapu City">
        </div>

        <div class="form-row-2">
          <div class="form-group">
            <label for="opp-start">Start Date & Time*</label>
            <input type="datetime-local" id="opp-start" name="start_at" required>
          </div>
          <div class="form-group">
            <label for="opp-end">End Date & Time*</label>
            <input type="datetime-local" id="opp-end" name="end_at" required>
          </div>
        </div>

        <div class="form-row-2">
          <div class="form-group">
            <label for="opp-deadline">Registration Deadline*</label>
            <input type="datetime-local" id="opp-deadline" name="registration_deadline" required>
          </div>
          <div class="form-group">
            <label for="opp-capacity">Volunteer Capacity*</label>
            <input type="number" id="opp-capacity" name="capacity" min="1" max="1000" value="25" required>
          </div>
        </div>

        <div class="form-group">
          <label for="opp-description">Description & Goals*</label>
          <textarea id="opp-description" name="description" rows="4" required placeholder="Describe the mission, tasks, and what volunteers will be doing..."></textarea>
        </div>

        <div class="form-group">
          <label for="opp-requirements">Requirements & Guidelines</label>
          <textarea id="opp-requirements" name="requirements" rows="3" placeholder="Attire, age requirements, items to bring, etc."></textarea>
        </div>

        <div class="form-group">
          <label for="opp-status">Publishing Status</label>
          <select id="opp-status" name="status">
            <option value="draft">Save as Draft</option>
            <option value="published">Publish Immediately</option>
          </select>
        </div>

        <button type="submit" class="btn btn-primary btn-full">Save Opportunity</button>
      </form>
    </div>
  </div>
</section>

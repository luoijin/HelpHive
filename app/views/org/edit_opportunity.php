<?php
declare(strict_types=1);

$opp = $opportunity ?? [];
?>
<!-- EDIT OPPORTUNITY VIEW -->
<section id="view-edit-opportunity" class="page-view active" style="display: block;">
  <div class="section-container max-w-700">
    <a href="/org/opportunities" class="btn-back" style="text-decoration: none;">
      <i data-lucide="arrow-left"></i> Back to Opportunities
    </a>

    <div class="form-card-container">
      <div class="form-header">
        <span class="sub-header-pill">ORGANIZATION MANAGEMENT</span>
        <h2 class="form-title">Edit Opportunity</h2>
        <p class="form-subtitle">Update event details and requirements.</p>
      </div>

      <form action="/org/opportunities/<?= (int) ($opp['id'] ?? 0) ?>/edit" method="POST">
        <?= Csrf::field() ?>

        <div class="form-group">
          <label for="opp-title">Opportunity Title*</label>
          <input type="text" id="opp-title" name="title" value="<?= e($opp['title'] ?? '') ?>" required>
        </div>

        <div class="form-group">
          <label for="opp-cause">Cause Area*</label>
          <select id="opp-cause" name="cause_area" required>
            <option value="Environment" <?= ($opp['cause_area'] ?? '') === 'Environment' ? 'selected' : '' ?>>Environment & Nature</option>
            <option value="Education" <?= ($opp['cause_area'] ?? '') === 'Education' ? 'selected' : '' ?>>Education & Youth</option>
            <option value="Community Support" <?= ($opp['cause_area'] ?? '') === 'Community Support' ? 'selected' : '' ?>>Community Support</option>
            <option value="Animal Welfare" <?= ($opp['cause_area'] ?? '') === 'Animal Welfare' ? 'selected' : '' ?>>Animal Welfare</option>
          </select>
        </div>

        <div class="form-group">
          <label for="opp-location">Location / Venue*</label>
          <input type="text" id="opp-location" name="location" value="<?= e($opp['location'] ?? '') ?>" required>
        </div>

        <div class="form-row-2">
          <div class="form-group">
            <label for="opp-start">Start Date & Time*</label>
            <input type="datetime-local" id="opp-start" name="start_at" value="<?= !empty($opp['start_at']) ? date('Y-m-d\TH:i', strtotime($opp['start_at'])) : '' ?>" required>
          </div>
          <div class="form-group">
            <label for="opp-end">End Date & Time*</label>
            <input type="datetime-local" id="opp-end" name="end_at" value="<?= !empty($opp['end_at']) ? date('Y-m-d\TH:i', strtotime($opp['end_at'])) : '' ?>" required>
          </div>
        </div>

        <div class="form-row-2">
          <div class="form-group">
            <label for="opp-deadline">Registration Deadline*</label>
            <input type="datetime-local" id="opp-deadline" name="registration_deadline" value="<?= !empty($opp['registration_deadline']) ? date('Y-m-d\TH:i', strtotime($opp['registration_deadline'])) : '' ?>" required>
          </div>
          <div class="form-group">
            <label for="opp-capacity">Volunteer Capacity*</label>
            <input type="number" id="opp-capacity" name="capacity" min="1" max="1000" value="<?= (int) ($opp['capacity'] ?? 25) ?>" required>
          </div>
        </div>

        <div class="form-group">
          <label for="opp-description">Description & Goals*</label>
          <textarea id="opp-description" name="description" rows="4" required><?= e($opp['description'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
          <label for="opp-requirements">Requirements & Guidelines</label>
          <textarea id="opp-requirements" name="requirements" rows="3"><?= e($opp['requirements'] ?? '') ?></textarea>
        </div>

        <div class="form-group">
          <label for="opp-status">Status</label>
          <select id="opp-status" name="status">
            <option value="draft" <?= ($opp['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option>
            <option value="published" <?= ($opp['status'] ?? '') === 'published' ? 'selected' : '' ?>>Published</option>
            <option value="cancelled" <?= ($opp['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
            <option value="completed" <?= ($opp['status'] ?? '') === 'completed' ? 'selected' : '' ?>>Completed</option>
          </select>
        </div>

        <button type="submit" class="btn btn-primary btn-full">Update Opportunity</button>
      </form>
    </div>
  </div>
</section>

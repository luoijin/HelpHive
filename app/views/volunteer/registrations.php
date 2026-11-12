<?php
declare(strict_types=1);

$registrationsList = $registrations ?? [];
?>
<!-- 6. MY APPLICATIONS VIEW (Figma 381:9692) -->
<section id="view-applications" class="page-view active" style="display: block;">
  <div class="section-container">
    <div class="section-heading">
      <h2 class="section-title">My Applications</h2>
      <p class="section-subtitle">Track and review the status of your submitted community activity registrations.</p>
    </div>

    <div class="applications-grid" id="applications-container">
      <?php if (!empty($registrationsList)): ?>
        <?php foreach ($registrationsList as $reg): ?>
          <div class="application-card">
            <div class="app-meta">
              <span class="tag-badge cause" style="margin-bottom: 8px; display: inline-block;"><?= e($reg['cause_area'] ?? 'Community') ?></span>
              <h3><?= e($reg['title']) ?></h3>
              <p><?= e($reg['org_name'] ?? 'HelpHive Partner') ?> &bull; <?= e($reg['location'] ?? 'Cebu') ?> &bull; <?= e(date('M d, Y', strtotime($reg['start_at'] ?? 'now'))) ?></p>
              <?php if (($reg['status'] ?? '') === 'registered'): ?>
                <p style="font-size: 13px; color: #15803d; margin-top: 4px;">You are confirmed for this opportunity!</p>
              <?php elseif (($reg['status'] ?? '') === 'waitlisted'): ?>
                <p style="font-size: 13px; color: #a16207; margin-top: 4px;">You are on the waitlist for this activity.</p>
              <?php elseif (($reg['status'] ?? '') === 'cancelled'): ?>
                <p style="font-size: 13px; color: #b91c1c; margin-top: 4px;">Registration was cancelled.</p>
              <?php else: ?>
                <p style="font-size: 13px; color: #a16207; margin-top: 4px;">Pending review by organization.</p>
              <?php endif; ?>
            </div>
            <div class="app-side" style="display: flex; flex-direction: column; align-items: flex-end; gap: 8px;">
              <span class="status-badge <?= in_array($reg['status'] ?? '', ['registered', 'approved'], true) ? 'approved' : 'pending' ?>">
                <?= e(ucfirst($reg['status'] ?? 'Pending')) ?>
              </span>
              <a href="/opportunities/<?= (int) $reg['opportunity_id'] ?>" class="btn btn-ghost" style="text-decoration: none;">
                View Opportunity
              </a>
              <?php if (($reg['status'] ?? '') === 'registered'): ?>
                <form action="/registrations/<?= (int) $reg['id'] ?>/cancel" method="POST" onsubmit="return confirm('Are you sure you want to cancel this registration?');" style="margin: 0;">
                  <?= Csrf::field() ?>
                  <button type="submit" class="btn" style="color: #ef4444; background: none; border: none; font-size: 13px; cursor: pointer; text-decoration: underline;">
                    Cancel Registration
                  </button>
                </form>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div style="text-align: center; padding: 48px; background: #fff; border: 1px solid var(--slate-border); border-radius: var(--radius-lg); grid-column: 1 / -1;">
          <i data-lucide="clipboard-list" style="width: 48px; height: 48px; stroke: var(--slate-muted); margin-bottom: 16px;"></i>
          <h3 style="font-size: 20px; font-weight: 700; margin-bottom: 8px;">No applications yet</h3>
          <p style="color: var(--slate-muted); margin-bottom: 20px;">You haven't submitted any applications for volunteer activities yet.</p>
          <a href="/opportunities" class="btn btn-primary" style="text-decoration: none;">Browse Opportunities</a>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

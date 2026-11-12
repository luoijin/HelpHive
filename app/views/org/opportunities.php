<?php
declare(strict_types=1);

$opps = $opportunities ?? [];
?>
<!-- ORGANIZATION OPPORTUNITIES LIST -->
<section id="view-org-opportunities" class="page-view active" style="display: block;">
  <div class="section-container">
    <div class="details-top-nav" style="justify-content: space-between; align-items: center;">
      <div>
        <h2 class="section-title">Manage Opportunities</h2>
        <p class="section-subtitle">Track, edit, publish, and mark attendance for your organization's events.</p>
      </div>
      <a href="/org/opportunities/create" class="btn btn-primary" style="text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
        <i data-lucide="plus-circle"></i> Post Opportunity
      </a>
    </div>

    <div class="form-card-container" style="max-width: 100%; margin-top: 24px;">
      <?php if (!empty($opps)): ?>
        <div style="overflow-x: auto;">
          <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
              <tr style="border-bottom: 2px solid var(--slate-border); color: var(--slate-muted); font-size: 14px;">
                <th style="padding: 12px 16px;">Title</th>
                <th style="padding: 12px 16px;">Cause</th>
                <th style="padding: 12px 16px;">Date & Time</th>
                <th style="padding: 12px 16px;">Capacity</th>
                <th style="padding: 12px 16px;">Status</th>
                <th style="padding: 12px 16px; text-align: right;">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($opps as $opp): ?>
                <tr style="border-bottom: 1px solid var(--slate-border); font-size: 14px;">
                  <td style="padding: 14px 16px; font-weight: 600;">
                    <a href="/opportunities/<?= (int) $opp['id'] ?>" style="color: inherit; text-decoration: none;">
                      <?= e($opp['title']) ?>
                    </a>
                  </td>
                  <td style="padding: 14px 16px;">
                    <span class="tag-badge cause"><?= e($opp['cause_area']) ?></span>
                  </td>
                  <td style="padding: 14px 16px;">
                    <?= e(date('M d, Y g:i A', strtotime($opp['start_at'] ?? 'now'))) ?>
                  </td>
                  <td style="padding: 14px 16px;"><?= (int) $opp['capacity'] ?> slots</td>
                  <td style="padding: 14px 16px;">
                    <span class="status-badge <?= $opp['status'] === 'published' ? 'approved' : 'pending' ?>">
                      <?= e(ucfirst($opp['status'])) ?>
                    </span>
                  </td>
                  <td style="padding: 14px 16px; text-align: right;">
                    <div style="display: inline-flex; gap: 8px; align-items: center; justify-content: flex-end;">
                      <a href="/org/opportunities/<?= (int) $opp['id'] ?>/attendance" class="btn btn-cause" style="padding: 6px 12px; font-size: 13px; text-decoration: none;">
                        Attendance
                      </a>
                      <a href="/org/opportunities/<?= (int) $opp['id'] ?>/edit" class="btn btn-ghost" style="padding: 6px 12px; font-size: 13px; text-decoration: none;">
                        Edit
                      </a>
                      <?php if ($opp['status'] === 'draft'): ?>
                        <form action="/org/opportunities/<?= (int) $opp['id'] ?>/publish" method="POST" style="margin: 0;">
                          <?= Csrf::field() ?>
                          <button type="submit" class="btn btn-primary" style="padding: 6px 12px; font-size: 13px;">Publish</button>
                        </form>
                      <?php endif; ?>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div style="text-align: center; padding: 48px;">
          <i data-lucide="calendar" style="width: 48px; height: 48px; stroke: var(--slate-muted); margin-bottom: 16px;"></i>
          <h3 style="font-size: 20px; font-weight: 700; margin-bottom: 8px;">No opportunities published yet</h3>
          <p style="color: var(--slate-muted); margin-bottom: 20px;">Create your first volunteer opportunity to start engaging your local community.</p>
          <a href="/org/opportunities/create" class="btn btn-primary" style="text-decoration: none;">Create Opportunity</a>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

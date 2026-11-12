<?php
declare(strict_types=1);

$orgList = $organizations ?? [];
?>
<!-- ADMIN ORGANIZATIONS VIEW -->
<section id="view-admin-orgs" class="page-view active" style="display: block;">
  <div class="section-container">
    <div class="details-top-nav" style="justify-content: space-between; align-items: center;">
      <div>
        <h2 class="section-title">Approve Organizations</h2>
        <p class="section-subtitle">Review partnership applications and grant publishing privileges.</p>
      </div>
      <a href="/admin/users" class="btn btn-ghost" style="text-decoration: none;">
        Manage Users
      </a>
    </div>

    <div class="form-card-container" style="max-width: 100%; margin-top: 24px;">
      <?php if (!empty($orgList)): ?>
        <div style="overflow-x: auto;">
          <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
              <tr style="border-bottom: 2px solid var(--slate-border); color: var(--slate-muted); font-size: 14px;">
                <th style="padding: 12px 16px;">Organization Name</th>
                <th style="padding: 12px 16px;">Contact Email</th>
                <th style="padding: 12px 16px;">Location</th>
                <th style="padding: 12px 16px;">Status</th>
                <th style="padding: 12px 16px;">Submitted</th>
                <th style="padding: 12px 16px; text-align: right;">Approval Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($orgList as $org): ?>
                <tr style="border-bottom: 1px solid var(--slate-border); font-size: 14px;">
                  <td style="padding: 14px 16px; font-weight: 600;">
                    <?= e($org['name']) ?>
                    <?php if (!empty($org['description'])): ?>
                      <p style="font-size: 12px; font-weight: normal; color: var(--slate-muted); margin-top: 4px;"><?= e($org['description']) ?></p>
                    <?php endif; ?>
                  </td>
                  <td style="padding: 14px 16px;"><?= e($org['contact_email'] ?? '') ?></td>
                  <td style="padding: 14px 16px;"><?= e($org['location'] ?? '—') ?></td>
                  <td style="padding: 14px 16px;">
                    <span class="status-badge <?= !empty($org['is_approved']) ? 'approved' : 'pending' ?>">
                      <?= !empty($org['is_approved']) ? 'Approved' : 'Pending' ?>
                    </span>
                  </td>
                  <td style="padding: 14px 16px;"><?= e(date('M d, Y', strtotime($org['created_at'] ?? 'now'))) ?></td>
                  <td style="padding: 14px 16px; text-align: right;">
                    <div style="display: inline-flex; gap: 6px; justify-content: flex-end;">
                      <?php if (empty($org['is_approved'])): ?>
                        <form action="/admin/organizations/<?= (int) $org['id'] ?>/approve" method="POST" style="margin: 0;">
                          <?= Csrf::field() ?>
                          <button type="submit" class="btn btn-primary" style="padding: 6px 12px; font-size: 12px;">Approve</button>
                        </form>
                      <?php endif; ?>
                      <form action="/admin/organizations/<?= (int) $org['id'] ?>/reject" method="POST" style="margin: 0;">
                        <?= Csrf::field() ?>
                        <button type="submit" class="btn" style="padding: 6px 12px; font-size: 12px; color: #ef4444; border: 1px solid #ef4444; border-radius: var(--radius-sm); background: none; cursor: pointer;">
                          Reject
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <p style="text-align: center; color: var(--slate-muted); padding: 32px 0;">No organization registration requests found.</p>
      <?php endif; ?>
    </div>
  </div>
</section>

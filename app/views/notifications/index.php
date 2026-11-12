<?php
declare(strict_types=1);

$notificationsList = $notifications ?? [];
?>
<!-- NOTIFICATIONS VIEW -->
<section id="view-notifications" class="page-view active" style="display: block;">
  <div class="section-container max-w-700">
    <div class="details-top-nav" style="justify-content: space-between; align-items: center;">
      <div>
        <h2 class="section-title">Notifications</h2>
        <p class="section-subtitle">Stay informed on registrations, hour verifications, and approvals.</p>
      </div>
      <?php if (!empty($notificationsList)): ?>
        <form action="/notifications/read-all" method="POST" style="margin: 0;">
          <?= Csrf::field() ?>
          <button type="submit" class="btn btn-ghost" style="font-size: 13px;">Mark all as read</button>
        </form>
      <?php endif; ?>
    </div>

    <div class="form-card-container" style="margin-top: 24px; padding: 16px;">
      <?php if (!empty($notificationsList)): ?>
        <div style="display: flex; flex-direction: column; gap: 12px;">
          <?php foreach ($notificationsList as $notif): ?>
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 14px; border-radius: var(--radius-md); background: <?= !empty($notif['is_read']) ? '#ffffff' : '#fef9e7' ?>; border: 1px solid <?= !empty($notif['is_read']) ? 'var(--slate-border)' : 'var(--primary-yellow)' ?>;">
              <div style="display: flex; align-items: flex-start; gap: 12px;">
                <div style="padding: 8px; border-radius: var(--radius-full); background: <?= !empty($notif['is_read']) ? 'var(--slate-border)' : 'var(--primary-yellow)' ?>; color: #1b2025;">
                  <i data-lucide="bell" style="width: 18px; height: 18px;"></i>
                </div>
                <div>
                  <p style="font-size: 14px; font-weight: <?= !empty($notif['is_read']) ? '500' : '700' ?>; margin-bottom: 4px;"><?= e($notif['message']) ?></p>
                  <span style="font-size: 12px; color: var(--slate-muted);"><?= e(date('M d, Y • g:i A', strtotime($notif['created_at'] ?? 'now'))) ?></span>
                  <?php if (!empty($notif['link'])): ?>
                    <a href="<?= e($notif['link']) ?>" style="font-size: 12px; margin-left: 8px; color: var(--primary-green); text-decoration: underline;">View details</a>
                  <?php endif; ?>
                </div>
              </div>
              <?php if (empty($notif['is_read'])): ?>
                <form action="/notifications/<?= (int) $notif['id'] ?>/read" method="POST" style="margin: 0;">
                  <?= Csrf::field() ?>
                  <button type="submit" class="btn btn-ghost" style="padding: 4px 8px; font-size: 12px;" title="Mark as read">
                    <i data-lucide="check"></i>
                  </button>
                </form>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else: ?>
        <div style="text-align: center; padding: 48px;">
          <i data-lucide="bell-off" style="width: 48px; height: 48px; stroke: var(--slate-muted); margin-bottom: 16px;"></i>
          <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 8px;">No notifications yet</h3>
          <p style="color: var(--slate-muted);">You're all caught up! Activity updates will appear here.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

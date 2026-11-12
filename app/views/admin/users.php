<?php
declare(strict_types=1);

$userList = $users ?? [];
?>
<!-- ADMIN USERS VIEW -->
<section id="view-admin-users" class="page-view active" style="display: block;">
  <div class="section-container">
    <div class="details-top-nav" style="justify-content: space-between; align-items: center;">
      <div>
        <h2 class="section-title">Manage Users</h2>
        <p class="section-subtitle">Oversee user accounts, roles, and platform permissions.</p>
      </div>
      <a href="/admin/organizations" class="btn btn-ghost" style="text-decoration: none;">
        View Organizations
      </a>
    </div>

    <div class="form-card-container" style="max-width: 100%; margin-top: 24px;">
      <?php if (!empty($userList)): ?>
        <div style="overflow-x: auto;">
          <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
              <tr style="border-bottom: 2px solid var(--slate-border); color: var(--slate-muted); font-size: 14px;">
                <th style="padding: 12px 16px;">Name</th>
                <th style="padding: 12px 16px;">Email</th>
                <th style="padding: 12px 16px;">Role</th>
                <th style="padding: 12px 16px;">Status</th>
                <th style="padding: 12px 16px;">Joined</th>
                <th style="padding: 12px 16px; text-align: right;">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($userList as $u): ?>
                <tr style="border-bottom: 1px solid var(--slate-border); font-size: 14px;">
                  <td style="padding: 14px 16px; font-weight: 600;"><?= e($u['full_name']) ?></td>
                  <td style="padding: 14px 16px;"><?= e($u['email']) ?></td>
                  <td style="padding: 14px 16px;">
                    <span class="tag-badge"><?= e(ucfirst($u['role'])) ?></span>
                  </td>
                  <td style="padding: 14px 16px;">
                    <span class="status-badge <?= !empty($u['is_active']) ? 'approved' : 'pending' ?>">
                      <?= !empty($u['is_active']) ? 'Active' : 'Disabled' ?>
                    </span>
                  </td>
                  <td style="padding: 14px 16px;"><?= e(date('M d, Y', strtotime($u['created_at'] ?? 'now'))) ?></td>
                  <td style="padding: 14px 16px; text-align: right;">
                    <form action="/admin/users/<?= (int) $u['id'] ?>/toggle" method="POST" style="margin: 0;">
                      <?= Csrf::field() ?>
                      <button type="submit" class="btn btn-ghost" style="padding: 6px 12px; font-size: 12px;">
                        <?= !empty($u['is_active']) ? 'Deactivate' : 'Activate' ?>
                      </button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <p style="text-align: center; color: var(--slate-muted); padding: 32px 0;">No users found.</p>
      <?php endif; ?>
    </div>
  </div>
</section>

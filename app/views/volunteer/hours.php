<?php
declare(strict_types=1);

$verifiedTotal = (float) ($verifiedHours ?? 0.0);
$pendingTotal = (float) ($pendingHours ?? 0.0);
$recordsList = $attendanceRecords ?? [];
?>
<!-- VOLUNTEER HOURS VIEW -->
<section id="view-hours" class="page-view active" style="display: block;">
  <div class="section-container">
    <div class="section-heading">
      <h2 class="section-title">My Community Service Hours</h2>
      <p class="section-subtitle">Track your verified volunteer contributions and claim service hours for attended activities.</p>
    </div>

    <!-- Separate Hour Cards (Business Invariant: verified and pending hours never mix) -->
    <div class="hive-cards-grid" style="margin-bottom: 40px;">
      <div class="hive-feature-card highlighted" style="border-top: 4px solid var(--primary-green);">
        <div class="hive-card-icon-wrap green">
          <i data-lucide="check-circle" class="hive-icon"></i>
        </div>
        <h3 class="hive-card-title"><?= number_format($verifiedTotal, 2) ?> Hours</h3>
        <p class="hive-card-desc"><strong>Verified Service Hours</strong><br>Officially certified by organizing bodies for community recognition.</p>
      </div>

      <div class="hive-feature-card" style="border-top: 4px solid var(--primary-yellow);">
        <div class="hive-card-icon-wrap yellow">
          <i data-lucide="hourglass" class="hive-icon"></i>
        </div>
        <h3 class="hive-card-title"><?= number_format($pendingTotal, 2) ?> Hours</h3>
        <p class="hive-card-desc"><strong>Pending Verification</strong><br>Awaiting organization review and attendance confirmation.</p>
      </div>
    </div>

    <!-- Attendance & Hours Table -->
    <div class="form-card-container" style="max-width: 100%;">
      <div class="form-header" style="text-align: left; margin-bottom: 24px;">
        <h3 style="font-size: 20px; font-weight: 700;">Service Hours History</h3>
      </div>

      <?php if (!empty($recordsList)): ?>
        <div style="overflow-x: auto;">
          <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
              <tr style="border-bottom: 2px solid var(--slate-border); color: var(--slate-muted); font-size: 14px;">
                <th style="padding: 12px 16px;">Opportunity</th>
                <th style="padding: 12px 16px;">Date</th>
                <th style="padding: 12px 16px;">Attendance</th>
                <th style="padding: 12px 16px;">Claimed</th>
                <th style="padding: 12px 16px;">Verified</th>
                <th style="padding: 12px 16px;">Status</th>
                <th style="padding: 12px 16px;">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($recordsList as $row): ?>
                <tr style="border-bottom: 1px solid var(--slate-border); font-size: 14px;">
                  <td style="padding: 14px 16px; font-weight: 600;"><?= e($row['title']) ?></td>
                  <td style="padding: 14px 16px;"><?= e(date('M d, Y', strtotime($row['start_at'] ?? 'now'))) ?></td>
                  <td style="padding: 14px 16px;">
                    <span class="status-badge <?= ($row['attendance_status'] ?? '') === 'present' ? 'approved' : 'pending' ?>">
                      <?= e(ucfirst($row['attendance_status'] ?? 'pending')) ?>
                    </span>
                  </td>
                  <td style="padding: 14px 16px;"><?= number_format((float) ($row['hours_claimed'] ?? 0), 2) ?> hrs</td>
                  <td style="padding: 14px 16px; font-weight: 700; color: var(--primary-green);">
                    <?= $row['hours_verified'] !== null ? number_format((float) $row['hours_verified'], 2) . ' hrs' : '—' ?>
                  </td>
                  <td style="padding: 14px 16px;">
                    <span class="status-badge <?= ($row['verification_status'] ?? '') === 'verified' ? 'approved' : 'pending' ?>">
                      <?= e(ucfirst($row['verification_status'] ?? 'unverified')) ?>
                    </span>
                  </td>
                  <td style="padding: 14px 16px;">
                    <?php if (($row['verification_status'] ?? '') !== 'verified'): ?>
                      <form action="/volunteer/hours/<?= (int) $row['registration_id'] ?>/claim" method="POST" style="display: flex; gap: 6px; align-items: center; margin: 0;">
                        <?= Csrf::field() ?>
                        <input type="number" step="0.5" min="0.5" max="24" name="hours" value="<?= e((string) ($row['hours_claimed'] ?: 3.0)) ?>" style="width: 70px; padding: 6px; border: 1px solid var(--slate-border); border-radius: var(--radius-sm);" required>
                        <button type="submit" class="btn btn-cause" style="padding: 6px 12px; font-size: 12px;">Claim</button>
                      </form>
                    <?php else: ?>
                      <span style="color: var(--primary-green); font-size: 13px;"><i data-lucide="check"></i> Verified</span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <p style="color: var(--slate-muted); text-align: center; padding: 24px 0;">No hour claims recorded yet. Participate in an event to begin logging service hours!</p>
      <?php endif; ?>
    </div>
  </div>
</section>

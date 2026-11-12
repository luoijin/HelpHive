<?php
declare(strict_types=1);

$opp = $opportunity ?? [];
$attendees = $registrations ?? [];
?>
<!-- MANAGE ATTENDANCE & VERIFY HOURS VIEW -->
<section id="view-org-attendance" class="page-view active" style="display: block;">
  <div class="section-container">
    <div class="details-top-nav">
      <a href="/org/opportunities" class="btn-back" style="text-decoration: none;">
        <i data-lucide="arrow-left"></i> Back to Opportunities
      </a>
      <span class="view-tag">ATTENDANCE & HOURS VERIFICATION</span>
    </div>

    <div class="section-heading" style="margin-top: 16px;">
      <h2 class="section-title"><?= e($opp['title'] ?? 'Opportunity') ?></h2>
      <p class="section-subtitle">
        <?= e(date('M d, Y • g:i A', strtotime($opp['start_at'] ?? 'now'))) ?> &bull; <?= e($opp['location'] ?? '') ?>
      </p>
    </div>

    <div class="form-card-container" style="max-width: 100%;">
      <?php if (!empty($attendees)): ?>
        <div style="overflow-x: auto;">
          <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
              <tr style="border-bottom: 2px solid var(--slate-border); color: var(--slate-muted); font-size: 14px;">
                <th style="padding: 12px 16px;">Volunteer</th>
                <th style="padding: 12px 16px;">Email</th>
                <th style="padding: 12px 16px;">Attendance</th>
                <th style="padding: 12px 16px;">Claimed</th>
                <th style="padding: 12px 16px;">Verified</th>
                <th style="padding: 12px 16px;">Status</th>
                <th style="padding: 12px 16px; text-align: right;">Verification Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($attendees as $reg): ?>
                <tr style="border-bottom: 1px solid var(--slate-border); font-size: 14px;">
                  <td style="padding: 14px 16px; font-weight: 600;"><?= e($reg['volunteer_name']) ?></td>
                  <td style="padding: 14px 16px; color: var(--slate-muted);"><?= e($reg['email']) ?></td>
                  <td style="padding: 14px 16px;">
                    <form action="/org/attendance/<?= (int) $reg['registration_id'] ?>" method="POST" style="margin: 0; display: inline-flex; gap: 4px;">
                      <?= Csrf::field() ?>
                      <select name="attendance_status" onchange="this.form.submit()" style="padding: 4px 8px; border-radius: var(--radius-sm); border: 1px solid var(--slate-border); font-size: 13px;">
                        <option value="pending" <?= ($reg['attendance_status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="present" <?= ($reg['attendance_status'] ?? '') === 'present' ? 'selected' : '' ?>>Present</option>
                        <option value="absent" <?= ($reg['attendance_status'] ?? '') === 'absent' ? 'selected' : '' ?>>Absent</option>
                        <option value="excused" <?= ($reg['attendance_status'] ?? '') === 'excused' ? 'selected' : '' ?>>Excused</option>
                      </select>
                    </form>
                  </td>
                  <td style="padding: 14px 16px;"><?= number_format((float) ($reg['hours_claimed'] ?? 0), 2) ?> hrs</td>
                  <td style="padding: 14px 16px; font-weight: 700; color: var(--primary-green);">
                    <?= $reg['hours_verified'] !== null ? number_format((float) $reg['hours_verified'], 2) . ' hrs' : '—' ?>
                  </td>
                  <td style="padding: 14px 16px;">
                    <span class="status-badge <?= ($reg['verification_status'] ?? '') === 'verified' ? 'approved' : 'pending' ?>">
                      <?= e(ucfirst($reg['verification_status'] ?? 'unverified')) ?>
                    </span>
                  </td>
                  <td style="padding: 14px 16px; text-align: right;">
                    <?php if (($reg['verification_status'] ?? '') !== 'verified'): ?>
                      <div style="display: inline-flex; gap: 6px; align-items: center; justify-content: flex-end;">
                        <form action="/org/hours/<?= (int) $reg['registration_id'] ?>/verify" method="POST" style="display: inline-flex; gap: 4px; margin: 0;">
                          <?= Csrf::field() ?>
                          <input type="number" step="0.5" min="0.5" max="24" name="hours_verified" value="<?= e((string) ($reg['hours_claimed'] ?: 3.0)) ?>" style="width: 65px; padding: 4px 6px; border: 1px solid var(--slate-border); border-radius: var(--radius-sm); font-size: 13px;" required <?= ($reg['attendance_status'] ?? '') !== 'present' ? 'disabled' : '' ?>>
                          <button type="submit" class="btn btn-cause" style="padding: 4px 10px; font-size: 12px;" <?= ($reg['attendance_status'] ?? '') !== 'present' ? 'disabled title="Mark present first"' : '' ?>>
                            Verify
                          </button>
                        </form>
                        <form action="/org/hours/<?= (int) $reg['registration_id'] ?>/reject" method="POST" style="margin: 0;">
                          <?= Csrf::field() ?>
                          <button type="submit" class="btn" style="padding: 4px 8px; font-size: 12px; color: #ef4444; border: 1px solid #ef4444; border-radius: var(--radius-sm); background: none; cursor: pointer;">
                            Reject
                          </button>
                        </form>
                      </div>
                    <?php else: ?>
                      <span style="color: var(--primary-green); font-size: 13px;"><i data-lucide="check-check"></i> Verified at <?= e(date('M d, Y', strtotime($reg['verified_at'] ?? 'now'))) ?></span>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <p style="text-align: center; color: var(--slate-muted); padding: 32px 0;">No registered volunteers for this opportunity yet.</p>
      <?php endif; ?>
    </div>
  </div>
</section>

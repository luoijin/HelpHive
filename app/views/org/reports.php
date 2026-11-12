<?php
declare(strict_types=1);

$rows = $reportRows ?? [];
$fromDate = $_GET['from'] ?? '';
$toDate = $_GET['to'] ?? '';
?>
<!-- PARTICIPATION REPORTS (VERIFIED ONLY) -->
<section id="view-org-reports" class="page-view active" style="display: block;">
  <div class="section-container">
    <div class="details-top-nav" style="justify-content: space-between; align-items: center;">
      <div>
        <h2 class="section-title">Participation Reports</h2>
        <p class="section-subtitle">Official records built exclusively from certified and verified volunteer hours.</p>
      </div>
      <div style="display: inline-flex; gap: 8px;">
        <a href="/org/reports/export/csv<?= $fromDate || $toDate ? '?from=' . urlencode($fromDate) . '&to=' . urlencode($toDate) : '' ?>" class="btn btn-ghost" style="text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
          <i data-lucide="download"></i> Export CSV
        </a>
        <a href="/org/reports/export/pdf<?= $fromDate || $toDate ? '?from=' . urlencode($fromDate) . '&to=' . urlencode($toDate) : '' ?>" class="btn btn-primary" style="text-decoration: none; display: inline-flex; align-items: center; gap: 6px;">
          <i data-lucide="file-text"></i> Export PDF
        </a>
      </div>
    </div>

    <!-- Filter Bar -->
    <form action="/org/reports" method="GET" class="search-top-bar" style="margin-top: 24px; display: flex; gap: 12px; align-items: center;">
      <div style="display: flex; align-items: center; gap: 8px;">
        <label for="rep-from" style="font-size: 14px; font-weight: 600;">From:</label>
        <input type="date" id="rep-from" name="from" value="<?= e($fromDate) ?>" style="padding: 8px 12px; border: 1px solid var(--slate-border); border-radius: var(--radius-sm);">
      </div>
      <div style="display: flex; align-items: center; gap: 8px;">
        <label for="rep-to" style="font-size: 14px; font-weight: 600;">To:</label>
        <input type="date" id="rep-to" name="to" value="<?= e($toDate) ?>" style="padding: 8px 12px; border: 1px solid var(--slate-border); border-radius: var(--radius-sm);">
      </div>
      <button type="submit" class="btn btn-cause" style="padding: 8px 16px;">Filter</button>
      <?php if ($fromDate || $toDate): ?>
        <a href="/org/reports" class="btn btn-ghost" style="text-decoration: none; padding: 8px 16px;">Clear</a>
      <?php endif; ?>
    </form>

    <div class="form-card-container" style="max-width: 100%; margin-top: 24px;">
      <?php if (!empty($rows)): ?>
        <div style="overflow-x: auto;">
          <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
              <tr style="border-bottom: 2px solid var(--slate-border); color: var(--slate-muted); font-size: 14px;">
                <th style="padding: 12px 16px;">Opportunity Title</th>
                <th style="padding: 12px 16px;">Date</th>
                <th style="padding: 12px 16px;">Volunteer Name</th>
                <th style="padding: 12px 16px;">Email</th>
                <th style="padding: 12px 16px;">Attendance</th>
                <th style="padding: 12px 16px;">Verified Hours</th>
                <th style="padding: 12px 16px;">Certified Date</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($rows as $row): ?>
                <tr style="border-bottom: 1px solid var(--slate-border); font-size: 14px;">
                  <td style="padding: 14px 16px; font-weight: 600;"><?= e($row['title']) ?></td>
                  <td style="padding: 14px 16px;"><?= e(date('M d, Y', strtotime($row['start_at'] ?? 'now'))) ?></td>
                  <td style="padding: 14px 16px;"><?= e($row['volunteer'] ?? $row['full_name'] ?? '') ?></td>
                  <td style="padding: 14px 16px; color: var(--slate-muted);"><?= e($row['email'] ?? '') ?></td>
                  <td style="padding: 14px 16px;">
                    <span class="status-badge approved"><?= e(ucfirst($row['attendance_status'] ?? 'present')) ?></span>
                  </td>
                  <td style="padding: 14px 16px; font-weight: 700; color: var(--primary-green);">
                    <?= number_format((float) ($row['hours_verified'] ?? 0), 2) ?> hrs
                  </td>
                  <td style="padding: 14px 16px;"><?= e(date('M d, Y', strtotime($row['verified_at'] ?? 'now'))) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div style="text-align: center; padding: 48px;">
          <i data-lucide="file-check" style="width: 48px; height: 48px; stroke: var(--slate-muted); margin-bottom: 16px;"></i>
          <h3 style="font-size: 20px; font-weight: 700; margin-bottom: 8px;">No verified participation records</h3>
          <p style="color: var(--slate-muted); margin-bottom: 20px;">Once volunteers are marked present and their hours verified, certified records will appear here.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

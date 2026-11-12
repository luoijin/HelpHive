<?php
declare(strict_types=1);

use Dompdf\Dompdf;

/** Renders a verified-hours report as a PDF download. */
final class PdfExporter
{
    /**
     * @param array<int,array<string,mixed>> $rows Verified rows only (from HourModel::reportRows).
     */
    public static function download(array $rows, string $orgName, string $filename): void
    {
        $html = '<h1>' . e($orgName) . ' — Participation Report</h1>'
              . '<p>Verified hours only.</p><table border="1" cellpadding="4" cellspacing="0" width="100%">'
              . '<tr><th>Opportunity</th><th>Date</th><th>Volunteer</th><th>Attendance</th><th>Hours</th></tr>';
        foreach ($rows as $r) {
            $html .= '<tr><td>' . e($r['title']) . '</td><td>' . e($r['start_at']) . '</td><td>'
                   . e($r['volunteer']) . '</td><td>' . e($r['attendance_status']) . '</td><td>'
                   . e((string) $r['hours_verified']) . '</td></tr>';
        }
        $html .= '</table>';

        $pdf = new Dompdf();
        $pdf->loadHtml($html);
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();
        $pdf->stream(basename($filename), ['Attachment' => true]);
        exit;
    }
}
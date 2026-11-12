<?php
declare(strict_types=1);

/** Streams rows as a CSV download. */
final class CsvExporter
{
    /**
     * @param array<int,array<string,mixed>> $rows
     */
    public static function download(array $rows, string $filename): void
    {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
        $out = fopen('php://output', 'w');
        fwrite($out, "\xEF\xBB\xBF"); // UTF-8 BOM for Excel
        if ($rows) {
            fputcsv($out, array_keys($rows[0]));
            foreach ($rows as $row) {
                // Neutralize spreadsheet formula injection
                $row = array_map(fn($v) => is_string($v) && preg_match('/^[=+\-@]/', $v) ? "'" . $v : $v, $row);
                fputcsv($out, $row);
            }
        }
        fclose($out);
        exit;
    }
}
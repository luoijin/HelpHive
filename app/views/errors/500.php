<?php
declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>500 — Internal Server Error | HelpHive</title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    .error-page-container {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 32px 16px;
      text-align: center;
      background-color: var(--slate-bg, #f8fafc);
    }
    .error-card {
      background: #ffffff;
      border: 1px solid var(--slate-border, #e2e8f0);
      border-radius: 16px;
      padding: 48px 32px;
      max-width: 600px;
      width: 100%;
      box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
    }
    .error-brand {
      font-size: 28px;
      font-weight: 800;
      margin-bottom: 24px;
      display: inline-block;
      text-decoration: none;
    }
    .error-code {
      font-size: 64px;
      font-weight: 800;
      color: #ef4444;
      line-height: 1;
      margin-bottom: 12px;
    }
    .error-title {
      font-size: 24px;
      font-weight: 700;
      color: #0f172a;
      margin-bottom: 12px;
    }
    .error-description {
      font-size: 15px;
      color: #64748b;
      line-height: 1.6;
      margin-bottom: 32px;
    }
    .error-debug {
      margin-top: 24px;
      text-align: left;
      background: #1e293b;
      color: #f1f5f9;
      padding: 16px;
      border-radius: 8px;
      font-size: 13px;
      overflow-x: auto;
      max-height: 350px;
    }
    .error-actions {
      display: flex;
      gap: 12px;
      justify-content: center;
    }
  </style>
</head>
<body>
  <div class="error-page-container">
    <div class="error-card">
      <a href="/" class="error-brand brand-name">
        <span class="logo-help" style="color: #0284c7;">Help</span><span class="logo-hive" style="color: #f59e0b;">Hive</span>
      </a>

      <div class="error-code">500</div>
      <h1 class="error-title">Something went wrong</h1>
      <p class="error-description">
        We encountered an unexpected error on our server. Our team has been notified. Please try refreshing the page or return to HelpHive home.
      </p>

      <div class="error-actions">
        <a href="/" class="btn btn-primary" style="text-decoration: none; padding: 10px 24px;">Return to Home</a>
      </div>

      <?php if (!empty($isLocal) && isset($e) && $e instanceof Throwable): ?>
        <div class="error-debug">
          <div style="font-weight: bold; margin-bottom: 8px; color: #f87171;">
            [<?= get_class($e) ?>]: <?= htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') ?>
          </div>
          <div style="color: #94a3b8; font-size: 12px; margin-bottom: 12px;">
            in <?= htmlspecialchars($e->getFile(), ENT_QUOTES, 'UTF-8') ?>:<?= $e->getLine() ?>
          </div>
          <pre style="margin: 0; font-family: monospace; white-space: pre-wrap;"><?= htmlspecialchars($e->getTraceAsString(), ENT_QUOTES, 'UTF-8') ?></pre>
        </div>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>

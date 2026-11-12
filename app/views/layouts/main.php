<?php
declare(strict_types=1);

$pageTitle = $title ?? 'HelpHive — Community Volunteering Platform';
$userMode = Auth::user() ? 'user' : 'guest';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($pageTitle) ?></title>
  <!-- Google Fonts: Montserrat & Outfit -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,600&family=Outfit:wght@500;600;700;800;900&display=swap" rel="stylesheet">
  <!-- Lucide Icons -->
  <script src="https://unpkg.com/lucide@latest"></script>
  <link rel="stylesheet" href="<?= asset('assets/css/style.css') ?>?v=<?= filemtime(BASE_PATH . '/public/assets/css/style.css') ?>">
</head>
<body data-mode="<?= e($userMode) ?>">

  <?php require __DIR__ . '/header.php'; ?>

  <!-- ================= MAIN CONTENT ================= -->
  <main class="main-wrapper">
    <?= $content ?? '' ?>
  </main>

  <?php require __DIR__ . '/footer.php'; ?>
  <?php require __DIR__ . '/modals.php'; ?>

  <script src="<?= asset('assets/js/app.js') ?>?v=<?= filemtime(BASE_PATH . '/public/assets/js/app.js') ?>"></script>
  <script>
    if (window.lucide) {
      lucide.createIcons();
    }
  </script>
</body>
</html>

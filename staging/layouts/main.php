<?php
// Layout utama, semua page akan ditaruh di $content
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $metaDesc; ?>">
    <meta name="keywords" content="Samsat Jambi, Pajak Kendaraan, PKB, Smart Samsat">
    <meta name="author" content="Samsat Jambi">

    <title><?php echo $title; ?></title>

    <script src="<?php echo BASE_URL; ?>/assets/js/tailwind.js"></script>
    <script src="<?php echo BASE_URL; ?>/assets/js/alpine.min.js" defer></script>
    <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>/assets/img/favicon.png" />
</head>
<body class="bg-gray-50">
    <?php include __DIR__ . '/navbar.php'; ?>

    <main class="pt-20 min-h-screen">
        <?php echo $content; ?>
    </main>

    <?php include __DIR__ . '/footer.php'; ?>
</body>
</html>
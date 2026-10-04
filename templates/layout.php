<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem auto; max-width: 720px; line-height: 1.4; padding: 0 1rem; }
        code { background: #f4f4f4; padding: 0.1rem 0.3rem; }
        .status-ok { color: #0a7f37; font-weight: 600; }
    </style>
</head>
<body>
<main>
    <?= $content ?>
</main>
</body>
</html>

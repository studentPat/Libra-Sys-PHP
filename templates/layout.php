<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></title>
    <style>
        body { font-family: system-ui, sans-serif; margin: 2rem auto; max-width: 48rem; padding: 0 1rem; }
        .status-ok { color: #176b2c; }
        .status-error { color: #9b1c1c; }
    </style>
</head>
<body>
    <header><a href="/">LibraSys</a></header>
    <main><?= $content ?></main>
</body>
</html>

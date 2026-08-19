<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title ?? 'Gestion des produits') ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; background: #f5f5f5; color: #222; }
        table { width: 100%; border-collapse: collapse; background: #fff; }
        th, td { padding: 0.6rem; border: 1px solid #ddd; text-align: left; }
        img.miniature { width: 60px; height: 60px; object-fit: cover; border-radius: 4px; }
        .flash { padding: 0.8rem; margin-bottom: 1rem; border-radius: 4px; }
        .flash.success { background: #d4edda; color: #155724; }
        .flash.error { background: #f8d7da; color: #721c24; }
        .btn { display: inline-block; padding: 0.5rem 1rem; background: #2c3e50; color: #fff; text-decoration: none; border-radius: 4px; border: none; cursor: pointer; }
        form.champ { display: flex; flex-direction: column; gap: 0.8rem; max-width: 420px; }
        label { font-weight: bold; }
        input, textarea { padding: 0.5rem; border: 1px solid #ccc; border-radius: 4px; font: inherit; }
    </style>
</head>
<body>
    <h1><?= htmlspecialchars($title ?? '') ?></h1>

    <?php if (!empty($_SESSION['flash'])): ?>
        <div class="flash <?= htmlspecialchars($_SESSION['flash']['type']) ?>">
            <?= htmlspecialchars($_SESSION['flash']['message']) ?>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <?= $content ?>
</body>
</html>

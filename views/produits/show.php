<?php if ($produit->aUneImage()): ?>
    <img src="<?= $base ?>/produits/<?= $produit->id ?>/image" alt="<?= htmlspecialchars($produit->nom) ?>" style="max-width:300px;border-radius:8px;">
<?php endif; ?>

<p><strong>Description :</strong> <?= nl2br(htmlspecialchars($produit->description)) ?></p>
<p><strong>Prix :</strong> <?= number_format($produit->prix, 2, ',', ' ') ?> FCFA</p>
<p><strong>Quantité :</strong> <?= $produit->quantite ?></p>

<p><a class="btn" href="<?= $base ?>/produits">← Retour au catalogue</a></p>

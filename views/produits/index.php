<p><a class="btn" href="<?= $base ?>/produits/create">+ Ajouter un produit</a></p>

<table>
    <thead>
        <tr>
            <th>Image</th>
            <th>Nom</th>
            <th>Prix</th>
            <th>Quantité</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($produits as $produit): ?>
            <tr>
                <td>
                    <?php if ($produit->aUneImage()): ?>
                        <img class="miniature" src="<?= $base ?>/produits/<?= $produit->id ?>/image" alt="<?= htmlspecialchars($produit->nom) ?>">
                    <?php else: ?>
                        —
                    <?php endif; ?>
                </td>
                <td><a href="<?= $base ?>/produits/<?= $produit->id ?>"><?= htmlspecialchars($produit->nom) ?></a></td>
                <td><?= number_format($produit->prix, 2, ',', ' ') ?> FCFA</td>
                <td><?= $produit->quantite ?></td>
                <td>
                    <form method="post" action="<?= $base ?>/produits/<?= $produit->id ?>/delete" onsubmit="return confirm('Supprimer ce produit ?');">
                        <button class="btn" type="submit">Supprimer</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        <?php if (empty($produits)): ?>
            <tr><td colspan="5">Aucun produit pour le moment.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

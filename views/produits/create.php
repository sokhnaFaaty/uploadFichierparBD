<form class="champ" method="post" action="<?= $base ?>/produits" enctype="multipart/form-data">
    <label for="nom">Nom</label>
    <input type="text" id="nom" name="nom" required>

    <label for="description">Description</label>
    <textarea id="description" name="description" rows="3"></textarea>

    <label for="prix">Prix</label>
    <input type="number" id="prix" name="prix" step="0.01" min="0" required>

    <label for="quantite">Quantité</label>
    <input type="number" id="quantite" name="quantite" min="0" required>

    <label for="image">Image</label>
    <input type="file" id="image" name="image" accept="image/png, image/jpeg, image/webp" required>

    <button class="btn" type="submit">Enregistrer</button>
</form>

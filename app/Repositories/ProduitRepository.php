<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Interfaces\ProduitRepositoryInterface;
use App\Models\Produit;
use PDO;

class ProduitRepository implements ProduitRepositoryInterface
{
    // On ne sélectionne jamais la colonne "image" ici : elle peut peser plusieurs Ko/Mo,
    // inutile de la charger pour une simple liste. Voir getImage() plus bas.
    private const COLONNES_SANS_IMAGE = 'id, nom, description, prix, quantite, image_type, date_creation';

    public function __construct(private PDO $pdo) {}

    public function all(): array
    {
        $stmt = $this->pdo->query(
            'SELECT ' . self::COLONNES_SANS_IMAGE . ' FROM produits ORDER BY nom'
        );
        return $this->hydrate($stmt->fetchAll());
    }

    public function findById(int $id): ?Produit
    {
        $stmt = $this->pdo->prepare(
            'SELECT ' . self::COLONNES_SANS_IMAGE . ' FROM produits WHERE id = ?'
        );
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row === false ? null : Produit::fromRow($row);
    }

    public function create(array $data): Produit
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO produits (nom, description, prix, quantite, image, image_type)
             VALUES (?, ?, ?, ?, ?, ?)
             RETURNING id'
        );

        $stmt->bindValue(1, $data['nom']);
        $stmt->bindValue(2, $data['description']);
        $stmt->bindValue(3, $data['prix']);
        $stmt->bindValue(4, $data['quantite'], PDO::PARAM_INT);

        if ($data['image'] !== null) {
            $stmt->bindParam(5, $data['image'], PDO::PARAM_LOB);
            $stmt->bindValue(6, $data['image_type']);
        } else {
            $stmt->bindValue(5, null, PDO::PARAM_NULL);
            $stmt->bindValue(6, null, PDO::PARAM_NULL);
        }

        $stmt->execute();
        $id = (int) $stmt->fetchColumn();

        return $this->findById($id) ?? throw new \RuntimeException('Produit introuvable après création');
    }

    public function update(int $id, array $data): void
    {
        if ($data['image'] !== null) {
            $stmt = $this->pdo->prepare(
                'UPDATE produits SET nom = ?, description = ?, prix = ?, quantite = ?, image = ?, image_type = ? WHERE id = ?'
            );
            $stmt->bindValue(1, $data['nom']);
            $stmt->bindValue(2, $data['description']);
            $stmt->bindValue(3, $data['prix']);
            $stmt->bindValue(4, $data['quantite'], PDO::PARAM_INT);
            $stmt->bindParam(5, $data['image'], PDO::PARAM_LOB);
            $stmt->bindValue(6, $data['image_type']);
            $stmt->bindValue(7, $id, PDO::PARAM_INT);
            $stmt->execute();
            return;
        }

        // Pas de nouvelle image envoyée : on garde l'ancienne, on ne touche pas la colonne image.
        $stmt = $this->pdo->prepare(
            'UPDATE produits SET nom = ?, description = ?, prix = ?, quantite = ? WHERE id = ?'
        );
        $stmt->execute([$data['nom'], $data['description'], $data['prix'], $data['quantite'], $id]);
    }

    public function delete(int $id): void
    {
        $this->pdo->prepare('DELETE FROM produits WHERE id = ?')->execute([$id]);
    }

    public function getImage(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT image, image_type FROM produits WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();

        if ($row === false || $row['image'] === null) {
            return null;
        }

        // PDO_PGSQL renvoie les colonnes BYTEA sous forme de flux (resource).
        $data = is_resource($row['image']) ? stream_get_contents($row['image']) : $row['image'];

        return ['data' => $data, 'type' => $row['image_type'] ?? 'application/octet-stream'];
    }

    /** @param array[] $rows */
    private function hydrate(array $rows): array
    {
        return array_map(Produit::fromRow(...), $rows);
    }
}

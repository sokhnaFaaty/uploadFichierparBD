<?php

declare(strict_types=1);

namespace App\Models;

class Produit
{
    public function __construct(
        public readonly int $id,
        public readonly string $nom,
        public readonly string $description,
        public readonly float $prix,
        public readonly int $quantite,
        public readonly ?string $imageType,
        public readonly string $dateCreation,
    ) {}

    public static function fromRow(array $row): self
    {
        return new self(
            id: (int) $row['id'],
            nom: $row['nom'],
            description: $row['description'],
            prix: (float) $row['prix'],
            quantite: (int) $row['quantite'],
            imageType: $row['image_type'] ?? null,
            dateCreation: $row['date_creation'],
        );
    }

    public function aUneImage(): bool
    {
        return $this->imageType !== null;
    }
}

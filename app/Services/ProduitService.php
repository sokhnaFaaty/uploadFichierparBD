<?php

declare(strict_types=1);

namespace App\Services;

use App\Interfaces\ProduitRepositoryInterface;
use App\Models\Produit;
use Exceptions\ValidationException;

class ProduitService
{
    private const TYPES_AUTORISES = ['image/jpeg', 'image/png', 'image/webp'];
    private const TAILLE_MAX = 2 * 1024 * 1024; // 2 Mo

    public function __construct(private ProduitRepositoryInterface $produits) {}

    public function create(array $input, array $fichier): Produit
    {
        $data = $this->valider($input);
        $image = $this->lireImage($fichier, obligatoire: true);
        $data['image'] = $image['data'] ?? null;
        $data['image_type'] = $image['type'] ?? null;

        return $this->produits->create($data);
    }

    public function update(int $id, array $input, array $fichier): void
    {
        $data = $this->valider($input);
        $image = $this->lireImage($fichier, obligatoire: false);
        $data['image'] = $image['data'] ?? null;
        $data['image_type'] = $image['type'] ?? null;

        $this->produits->update($id, $data);
    }

    /** @return array{nom: string, description: string, prix: float, quantite: int} */
    private function valider(array $input): array
    {
        $nom = trim((string) ($input['nom'] ?? ''));
        $prix = $input['prix'] ?? null;
        $quantite = $input['quantite'] ?? null;

        if ($nom === '') {
            throw new ValidationException('Le nom du produit est obligatoire.');
        }
        if (!is_numeric($prix) || (float) $prix < 0) {
            throw new ValidationException('Le prix doit être un nombre positif.');
        }
        if (!is_numeric($quantite) || (int) $quantite < 0) {
            throw new ValidationException('La quantité doit être un entier positif.');
        }

        return [
            'nom'         => $nom,
            'description' => trim((string) ($input['description'] ?? '')),
            'prix'        => (float) $prix,
            'quantite'    => (int) $quantite,
        ];
    }

    /** @return array{data: string, type: string}|null */
    private function lireImage(array $fichier, bool $obligatoire): ?array
    {
        $erreur = $fichier['error'] ?? UPLOAD_ERR_NO_FILE;

        if ($erreur === UPLOAD_ERR_NO_FILE) {
            if ($obligatoire) {
                throw new ValidationException("L'image du produit est obligatoire.");
            }
            return null;
        }

        if ($erreur !== UPLOAD_ERR_OK) {
            throw new ValidationException("Erreur lors de l'envoi de l'image.");
        }

        if ($fichier['size'] > self::TAILLE_MAX) {
            throw new ValidationException("L'image ne doit pas dépasser 2 Mo.");
        }

        $type = mime_content_type($fichier['tmp_name']);
        if (!in_array($type, self::TYPES_AUTORISES, true)) {
            throw new ValidationException("Format d'image non supporté (jpeg, png, webp uniquement).");
        }

        return [
            'data' => file_get_contents($fichier['tmp_name']),
            'type' => $type,
        ];
    }
}

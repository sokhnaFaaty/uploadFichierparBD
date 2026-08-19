<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\Models\Produit;

interface ProduitRepositoryInterface
{
    /** @return Produit[] */
    public function all(): array;

    public function findById(int $id): ?Produit;

    public function create(array $data): Produit;

    public function update(int $id, array $data): void;

    public function delete(int $id): void;

    /** @return array{data: string, type: string}|null */
    public function getImage(int $id): ?array;
}

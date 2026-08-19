<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Interfaces\ProduitRepositoryInterface;
use App\Services\ProduitService;
use Core\View;
use Exceptions\NotFoundException;
use Exceptions\ValidationException;

class ProduitController extends Controller
{
    public function __construct(
        private ProduitRepositoryInterface $produits,
        private ProduitService $produitService,
    ) {}

    public function index(): string
    {
        return View::render('produits/index', [
            'title'    => 'Catalogue des produits',
            'produits' => $this->produits->all(),
        ]);
    }

    public function create(): string
    {
        return View::render('produits/create', [
            'title' => 'Nouveau produit',
        ]);
    }

    public function store(): never
    {
        try {
            $this->produitService->create($_POST, $_FILES['image'] ?? []);
            flash('success', 'Produit ajouté au catalogue.');
            View::redirect('/produits');
        } catch (ValidationException $e) {
            flash('error', $e->getMessage());
            View::redirectBack('/produits/create');
        }
    }

    public function show(int $id): string
    {
        $produit = $this->produits->findById($id);

        if ($produit === null) {
            throw new NotFoundException('Produit introuvable.');
        }

        return View::render('produits/show', [
            'title'   => $produit->nom,
            'produit' => $produit,
        ]);
    }

    public function image(int $id): never
    {
        $image = $this->produits->getImage($id);

        if ($image === null) {
            throw new NotFoundException('Image introuvable.');
        }

        header('Content-Type: ' . $image['type']);
        header('Content-Length: ' . strlen($image['data']));
        echo $image['data'];
        exit;
    }

    public function delete(int $id): never
    {
        $this->produits->delete($id);
        flash('success', 'Produit supprimé.');
        View::redirect('/produits');
    }
}

<?php

declare(strict_types=1);

use App\Controllers\ProduitController;

$router->get('/produits', [ProduitController::class, 'index']);
$router->get('/produits/create', [ProduitController::class, 'create']);
$router->post('/produits', [ProduitController::class, 'store']);
$router->get('/produits/{id}', [ProduitController::class, 'show']);
$router->get('/produits/{id}/image', [ProduitController::class, 'image']);
$router->post('/produits/{id}/delete', [ProduitController::class, 'delete']);

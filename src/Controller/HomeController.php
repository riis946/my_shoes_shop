<?php


namespace App\Controller;

use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ProductRepository $productRepo, CategoryRepository $categoryRepo): Response
    {
        return $this->render('home/index.html.twig', [
            'products' => $productRepo->findAll(),
            'categories' => $categoryRepo->findAll(),
        ]);
    }

    #[Route('/produit/{id}', name: 'product_show')]
    public function show(Product $product): Response
    {
        return $this->render('home/show.html.twig', [
            'product' => $product,
        ]);
    }
}

<?php
namespace App\Controller;
use App\Service\BoutiqueService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
class BoutiqueController extends AbstractController
{
    public function index(BoutiqueService $boutique)
    {
        $categories = $boutique->findAllCategories();
        return $this->render('boutique/boutique.html.twig', [
            "categories" =>$categories,
        ]);
    }
}
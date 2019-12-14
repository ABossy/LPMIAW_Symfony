<?php
namespace App\Controller;
use App\Service\BoutiqueService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class BoutiqueController extends AbstractController
{
    public function index(BoutiqueService $boutique)
    {
        $categories = $boutique->findAllCategories();
        return $this->render('boutique/boutique.html.twig', [
            "categories" =>$categories,
        ]);
    }

    public function rayon(BoutiqueService $boutique, $idCategorie)
    {
        $produits = $boutique->findProduitsByCategorie($idCategorie);
        return $this->render('boutique/rayon.html.twig', [
            "produits" =>$produits,
        ]);
    }



    public function contact()
    {
        return $this->render('default/contact.html.twig', [

        ]);
    }

    public function search(BoutiqueService $boutiqueService, Request $request)
    {
        $searchText = $request->get('search');
        $produits = $boutiqueService->findProduitsByLibelleOrTexte($searchText);
        return $this->render('boutique/rayon.html.twig', [
            "produits" => $produits ]);

    }
}
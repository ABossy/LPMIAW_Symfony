<?php
namespace App\Controller;
use App\Entity\Categorie;
use App\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class BoutiqueController extends AbstractController
{
    public function index()
    {
        $categories = $this->getDoctrine()->getRepository(Categorie::class)->findAll();
        return $this->render('boutique/boutique.html.twig', [
            "categories" =>$categories,
        ]);
    }

    public function rayon($idCategorie)
    {
        $produits = $this->getDoctrine()->getRepository(Produit::class)->findByCategorie($idCategorie);
        return $this->render('boutique/rayon.html.twig', [
            "produits" =>$produits,
        ]);
    }

    public function contact()
    {
        return $this->render('default/contact.html.twig', [

        ]);
    }

    public function search(Request $request)
    {
        $searchText = $request->get('search');
        $produits = $this->getDoctrine()->getRepository(Produit::class)->findProduitsByName($searchText);
        return $this->render('boutique/rayon.html.twig', [
            "produits" => $produits ]);

    }
}
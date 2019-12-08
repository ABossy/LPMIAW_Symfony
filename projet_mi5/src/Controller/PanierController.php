<?php
namespace App\Controller;
use App\Service\PanierService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
class PanierController extends AbstractController
{

    public function index(PanierService $boutique)
    {
        return $this->render('panier/index.html.twig', [
            "produits" =>[],
        ]);

    }

    public function ajoutPanier($idProduit){

    }

}


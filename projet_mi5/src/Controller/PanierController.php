<?php
namespace App\Controller;
use App\Service\BoutiqueService;
use App\Service\PanierService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
class PanierController extends AbstractController
{

    public function index(PanierService $panierService, BoutiqueService $boutiqueService)
    {
        $panierWithItems = [];
        $panier          = $panierService->getContenu();
        $prixTotal       = $panierService->getTotal();
        foreach ($panier as $id => $quantity) {
            $panierWithItems[] = ['item' =>$boutiqueService->findProduitById($id), 'quantity' => $quantity];
        }
        return $this->render(
            'panier/index.html.twig',
            [
                "panier" => $panierWithItems,
                "prix"   => $prixTotal,
            ]
        );

    }

    public function ajoutPanier(PanierService $panierService, $idProduit)
    {
        $panierService->ajouterProduit($idProduit);
        return $this->redirectToRoute('panier_page');
    }
    public function panierEnlever(PanierService $panierService, $idProduit)
    {
        $panierService->enleverProduit($idProduit);
        return $this->redirectToRoute('panier_page');
    }
    public function panierSupprimer(PanierService $panierService, $idProduit)
    {
        $panierService->supprimerProduit($idProduit);
        return $this->redirectToRoute('panier_page');
    }



}


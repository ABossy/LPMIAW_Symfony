<?php
namespace App\Controller;
use App\Entity\Produit;
use App\Service\BoutiqueService;
use App\Service\PanierService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
class PanierController extends AbstractController
{

    public function index(PanierService $panierService)
    {
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Produit::class);
        $panierWithItems = [];
        $panier          = $panierService->getContenu();
        $prixTotal       = $panierService->getTotal();
        foreach ($panier as $id => $quantity) {
            $panierWithItems[] = ['item' =>$repo->find($id), 'quantity' => $quantity];
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


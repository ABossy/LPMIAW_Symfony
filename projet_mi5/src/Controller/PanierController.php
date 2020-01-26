<?php
namespace App\Controller;
use App\Entity\Commande;
use App\Entity\Produit;
use App\Entity\User;
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

    public function panierVider(PanierService $panierService)
    {
        $panierService->removeall();
        return $this->redirectToRoute('panier_page');
    }

    public function validation(PanierService $panierService){
        if($panierService->getNbProduits() == 0){
            return $this->render( 'panier/validation-empty.html.twig');
        }
        if($this->getUser()){
            $commandes = $this->getDoctrine()->getRepository(Commande::class)->findBy(['user' => $this->getUser()->getId()]);
            $commande = $panierService->panierToCommande($this->getUser());
            return $this->render( 'panier/validation.html.twig',[
                'user' => $this->getUser(),
                'commandes' => $commandes,
                'commande' => $commande
            ]);
        } else{
            return $this->redirectToRoute( 'app_login');
        }

    }

    public function iconePanier(PanierService $panierService){
            return $this->render('iconePanier.html.twig',[
                'articles'=>$panierService->getNbProduits()
            ]);
    }



}


<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
class ProduitController extends AbstractController
{
    public function index()
    {
        return $this->render('produit/produit.html.twig', [

        ]);
    }
}
<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
class ContactController extends AbstractController
{
    public function index()
    {
        return $this->render('contact/contact.html.twig', [

        ]);
    }
}
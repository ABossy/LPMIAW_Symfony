<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserController extends AbstractController
{

    private $session;

    public function __construct(SessionInterface $session){
        $this->session =$session;
    }
    

    public function index(UserRepository $userRepository): Response
    { dump($this->session);
        return $this->render('user/index.html.twig', [
            'user' => $this->getDoctrine()->getRepository(User::class)->findOneById($this->session->get('user')),

        ]);
    }


    public function new(Request $request, SessionInterface $session, UserPasswordEncoderInterface $passwordEncoder) : Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($passwordEncoder->encodePassword($user,$user->getPassword()));
            $user->setRoles(["ROLE_CLIENT"]);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
           // $this->session->set('user', $user->getId());
            return $this->redirectToRoute('user_accueil');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }




}

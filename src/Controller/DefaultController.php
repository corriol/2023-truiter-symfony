<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route("/", name: "index")]
    public function index(): Response
    {
/*        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);*/
        return $this->redirectToRoute("home");

    }

    #[Route("/{id}", name: "tweets_user_id", requirements: ["id" => "\d+"], methods: ["GET"], priority: 2)]
    public function tweetsByUserId(int $id): Response
    {
        $text = "Tweets de l'usuari amb id: {$id}";

        return $this->render('default/sample.html.twig', [
            'message'=> $text
        ]);
    }

    #[Route("/{username}", name: "tweets_username")]
    public function tweetsByUsername(string $username): Response
    {
        $text = "Tweets de l'usuari: {$username}";

        return $this->render('default/sample.html.twig', [
            'message'=> $text
        ]);
    }

    #[Route("/home", name: "home", priority: 1)]
    public function home(): Response
    {
        $message = "Pàgina principal";

        return $this->render('default/sample.html.twig', [
            'message'=> $message
        ]);

    }
}

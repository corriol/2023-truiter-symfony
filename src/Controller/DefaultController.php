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
    #[Route("/{id}", name: "tweets_user_id", requirements: ["id" => "\d+"])]
    public function tweetsByUserId(int $id): Response
    {
        return new Response("Tweets de l'usuari amb id: {$id}");
    }

    #[Route("/{username}", name: "tweets_username")]
    public function tweetsByUsername(string $username): Response
    {
        return new Response("Tweets de l'usuari: {$username}");
    }

    #[Route("/home", name: "home", priority: 1)]
    public function home(): Response
    {
        return new Response("Pàgina principal");
    }
}

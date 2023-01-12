<?php

namespace App\Controller;

use App\Entity\Tweet;
use App\Entity\User;
use App\Repository\TweetRepository;
use App\Repository\UserRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
    public function tweetsByUsername(string $username, UserRepository $userRepository, TweetRepository $tweetRepository): Response
    {
        $text = "Tweets de l'usuari: {$username}";

        $user = $userRepository->findOneBy(["username"=>$username]);

        $tweets = $tweetRepository->findBy(["author"=>$user]);

        return $this->render('default/sample.html.twig', [
            'tweets'=> $tweets
        ]);
    }

    #[Route("/home", name: "home", priority: 1)]
    public function home(UserRepository $userRepository, TweetRepository $tweetRepository, ValidatorInterface $validator): Response
    {
        $tweets = $tweetRepository->findBy([], ["createdAt"=>"DESC"]);

        return $this->render('default/sample.html.twig', [
            'tweets'=> $tweets
        ]);

    }
}

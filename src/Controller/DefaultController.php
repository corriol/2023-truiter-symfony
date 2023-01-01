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
    public function tweetsByUsername(string $username): Response
    {
        $text = "Tweets de l'usuari: {$username}";

        return $this->render('default/sample.html.twig', [
            'message'=> $text
        ]);
    }

    #[Route("/home", name: "home", priority: 1)]
    public function home(UserRepository $userRepository, TweetRepository $tweetRepository, ValidatorInterface $validator): Response
    {
        $user = $userRepository->findOneBy(["username"=>"user"]);
        if (!$user) {
            $user = new User();
            $user->setUsername("user");
            $user->setName("Usuari de prova");
            $user->setPassword("prova");
            $user->setCreatedAt(new DateTime());
            $user->setVerified(false);

            $userRepository->save($user);
        }

        $tweets = $tweetRepository->findAll();

        $tweet = new Tweet();
        $tweet->setAuthor($user);
        $tweet->setText("My tweet #" . count($tweets) + 1);
        $tweet->setCreatedAt(new DateTime());
        $tweet->setLikeCount(0);

        $errors = $validator->validate($tweet);

       // dump($errors);

        if (count($errors) > 0)
            return new Response($errors);


        $tweetRepository->save($tweet, true);

        $tweets = $tweetRepository->findAll();

        return $this->render('default/sample.html.twig', [
            'tweets'=> $tweets
        ]);

    }
}

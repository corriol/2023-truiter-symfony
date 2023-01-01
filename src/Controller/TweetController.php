<?php

namespace App\Controller;

use App\Entity\Tweet;
use App\Entity\User;
use App\Repository\TweetRepository;
use DateTime;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TweetController extends AbstractController
{
    #[Route('/compose/tweet', name: 'tweet_compose')]
    public function compose(Request $request, TweetRepository $tweetRepository): Response
    {
        $tweet = new Tweet();
        $tweet->setCreatedAt(new DateTime());
        $tweet->setLikeCount(0);

        $formBuilder = $this->createFormBuilder($tweet)
            ->add('text', TextareaType::class)
            ->add('author', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'username'])
            ->add('create', SubmitType::class);

        $form = $formBuilder->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tweetRepository->save($tweet, true);

            $this->addFlash("info", "El nou tweet s'ha creat");
            return $this->redirectToRoute("home");
        }
        return $this->renderForm('tweet/index.html.twig', [
            'form' => $form
        ]);
    }
}

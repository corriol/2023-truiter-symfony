<?php

namespace App\Controller;

use App\Entity\Tweet;
use App\Entity\User;
use App\Form\TweetType;
use App\Repository\PhotoRepository;
use App\Repository\TweetRepository;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class TweetController extends AbstractController
{
    #[Route('/compose/tweet', name: 'tweet_compose')]
    #[IsGranted('ROLE_USER')]
    public function compose(Request $request, TweetRepository $tweetRepository,
                            PhotoRepository $photoRepository): Response
    {
        $tweet = new Tweet();
        $tweet->setCreatedAt(new DateTime());
        $tweet->setLikeCount(0);

        // obtenim les dades de l'usuari que ha iniciat sessió
        $user = $this->getUser();
        $tweet->setAuthor($user);

        $form = $this->createForm(TweetType::class, $tweet);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $tweet = $form->getData();

            foreach ($tweet->getAttachments() as $photo) {
                $photo->setTweet($tweet);
                $photoRepository->save($photo);
            }

            $tweetRepository->save($tweet, true);

            $this->addFlash("info", "El nou tweet s'ha creat");
            return $this->redirectToRoute("home");
        }
        return $this->renderForm('tweet/index.html.twig', [
            'form' => $form
        ]);
    }
    #[Route('/tweets/{id}/like', name: 'tweet_like')]
    #[IsGranted('ROLE_USER')]
    public function like(Tweet $tweet, TweetRepository $tweetRepository): Response {
        $user = $this->getUser();
        if (!$tweet->getLinkingUsers()->contains($user)) {
            $tweet->addLinkingUser($user);
            $tweet->setLikeCount($tweet->getLikeCount() + 1);
            $tweetRepository->save($tweet, true);
        }

        return $this->redirectToRoute('home');
    }

    #[Route('/api/tweets/{id}/like', name: 'api_tweet_like')]
    public function apiLike(Tweet $tweet, TweetRepository $tweetRepository): Response {

        $user = $this->getUser();

        if (!$user)
            return new JsonResponse(["result"=>"ko"], Response::HTTP_UNAUTHORIZED);

        if ($tweet->getLinkingUsers()->contains($user))
            return new JsonResponse(["result"=>"ko"], Response::HTTP_OK);

        $tweet->addLinkingUser($user);
        $tweet->setLikeCount($tweet->getLikeCount() + 1);
        $tweetRepository->save($tweet, true);

        return new JsonResponse(["result"=>"ok"], Response::HTTP_OK);
    }
}

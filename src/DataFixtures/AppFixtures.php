<?php

namespace App\DataFixtures;

use App\Entity\Tweet;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $user = new User();
        $user->setUsername("user");
        $user->setName("Usuari de prova");
        $user->setPassword("prova");
        $user->setCreatedAt(new DateTime());
        $user->setVerified(false);
        $manager->persist($user);

        for ($i=0; $i<20; $i++) {
            $tweet = new Tweet();
            $tweet->setAuthor($user);
            $tweet->setText("My tweet #" . $i + 1);
            $tweet->setCreatedAt(new DateTime());
            $tweet->setLikeCount(0);
            $manager->persist($tweet);
        }

        $manager->flush();
    }
}

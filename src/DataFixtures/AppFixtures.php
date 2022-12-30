<?php

namespace App\DataFixtures;

use App\Entity\Tweet;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = Faker\Factory::create('ES_es');
        $user = new User();
        $user->setUsername("user");
        $user->setName("Usuari de prova");
        $user->setPassword("prova");
        $user->setCreatedAt($faker->dateTimeInInterval('-1 year'));
        $user->setVerified(false);
        $manager->persist($user);

        for ($i=0; $i<20; $i++) {
            $tweet = new Tweet();
            $tweet->setAuthor($user);
            $tweet->setText($faker->text(280));
            $tweet->setCreatedAt($faker->dateTimeInInterval('-1 year', '1 year'));
            $tweet->setLikeCount(0);
            $manager->persist($tweet);
        }

        $manager->flush();
    }
}

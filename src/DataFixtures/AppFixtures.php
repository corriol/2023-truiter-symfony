<?php

namespace App\DataFixtures;

use App\Entity\Tweet;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use WW\Faker\Provider\Picture;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher) {

    }
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = Faker\Factory::create('ES_es');

        $faker->addProvider(new \Mmo\Faker\PicsumProvider($faker));
        $faker->addProvider(new \Mmo\Faker\LoremSpaceProvider($faker));

        for ($i=0; $i<20; $i++) {
            $faker->loremSpace(\Mmo\Faker\LoremSpaceProvider::CATEGORY_FACE, 'resources', 100, 100); // /tmp/fd3646c544a9a46bd16d1d097e737ee4.jpg
        }

        $array = [
            ["user", "Usuari de prova", "user"],
            ["admin", "Admnistrador", "admin"],
            ["corriol", "Corriol", "corriol"]
        ];

        $totalUsers = count($array);
        $users = [];


        for ($i=0; $i < $totalUsers; $i++) {
            $user = new User();
            $user->setUsername($array[$i][0]);
            $user->setName($array[$i][1]);

            $hashedPassword = $this->passwordHasher->hashPassword($user, $array[$i][2]);
            $user->setPassword($hashedPassword);

            $user->setCreatedAt($faker->dateTimeInInterval('-1 year'));
            $user->setVerified(false);
            $manager->persist($user);
            $users[] = $user;
        }
        for ($i=0; $i<30; $i++) {
            $tweet = new Tweet();
            $tweet->setAuthor($users[rand(0, $totalUsers-1)]);
            $tweet->setText($faker->text(280));
            $tweet->setCreatedAt($faker->dateTimeInInterval('-1 year', '1 year'));
            $tweet->setLikeCount(0);
            $manager->persist($tweet);
        }

        $manager->flush();
    }
}

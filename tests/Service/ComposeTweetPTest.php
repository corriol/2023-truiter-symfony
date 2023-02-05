<?php

namespace App\Tests\Service;

use App\Repository\UserRepository;
use Symfony\Component\Panther\PantherTestCase;

class ComposeTweetPTest extends PantherTestCase
{
    public function testSomething(): void
    {
        $client = static::createPantherClient(['external_base_uri' => 'http://localhost:8080']
        );
        $crawler = $client->request('GET', '/home');
        $client->takeScreenshot("./tests/screenshots/test.png");

        $this->assertSelectorTextContains('h2', 'Últims tuits');

    }

    public function testComposeTweet(): void
    {
        $client = static::createPantherClient(['external_base_uri' => 'http://localhost:8080']
        );
        $crawler = $client->request('GET', '/login');

        $buttonCrawlerNode = $crawler->selectButton('Sign in');

        $client->submitForm("Sign in",
            [
                "username"=>"user",
                "password"=>"user"
            ]
        );

        $crawler = $client->request('GET', '/compose/tweet');

        $addPhoto = $crawler->selectButton('Add photo');
        $addPhoto->click();

        $client->takeScreenshot('./tests/screenshots/form.png');

        $addPhoto->click();
        $client->takeScreenshot('./tests/screenshots/form-2.png');

        $this->assertSelectorExists('#tweet_attachments_0_altText');
        $this->assertSelectorExists('#tweet_attachments_1_altText');

    }






    /*public function testANewTweetWithImagesIsCreated(): void
    {
        $client = static::createPantherClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByUsername('user');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/compose/tweet');

        $buttonCrawlerNode = $crawler->selectButton('Crear');

        $form = $buttonCrawlerNode->form();
        $name = $form->getName();

        $form['tweet[text]'] = "Proves d'enviament amb imatges";
        $form['tweet[attachments][0][altText]'] = "Proves";
        $form['tweet[attachments][0][photoFile][file]']->upload('/home/vicent/projectes/2023-truiter-symfony/resources/0bf7a2d91dca47a26c85ced8747cd3c4.jpg');

        $crawler->selectButton('Add photo');


        $client->submit($form);

        $this->assertResponseRedirects();
        $client->followRedirect();

        $this->assertSelectorTextContains("p.card-text", "Proves d'enviament amb imatges");
    }*/

}

<?php

namespace App\Tests\Service;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ComposeTweetTest extends WebTestCase
{
    public function testANewTweetIsCreated(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByUsername('user');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/compose/tweet');

        $buttonCrawlerNode = $crawler->selectButton('Crear');

        $form = $buttonCrawlerNode->form();
        $name = $form->getName();

        $client->submitForm("Crear",
            [sprintf("%s[text]", $name)=>"Proves d'enviament"]
        );

        $this->assertResponseRedirects();
        $client->followRedirect();

        $this->assertSelectorTextContains("p.card-text", "Proves d'enviament");
    }

    public function testErrorIsShown(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByUsername('user');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/compose/tweet');

        $buttonCrawlerNode = $crawler->selectButton('Crear');

        $form = $buttonCrawlerNode->form();
        $name = $form->getName();

        $client->submitForm("Crear",
            //[sprintf("%s[text]", $name)=>"Proves d'enviament"]
        );

        //$this->assertResponseRedirects();
        //$client->followRedirect();

        $this->assertSelectorExists("#tweet_text.is-invalid");
    }
}

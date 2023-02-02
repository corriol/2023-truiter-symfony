<?php

namespace App\Tests;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AccessControlTest extends WebTestCase
{

    public function testAnonymousCannotComposeTweets(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/compose/tweet');

        //$this->assertResponseIsSuccessful();
        // En aquest cas comprovem que si un usuari anònim intenta accedir ha de redirigir-lo
        // a la pàgina d'inici de sessió
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
        //$this->assertSelectorTextContains('h1', 'Hello World');
    }

    public function testAuthenticatedUsersCanComposeTweets(): void
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByUsername('user');

        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/compose/tweet');
        //$this->assertResponseIsSuccessful();
        // En aquest cas comprovem que si un usuari anònim intenta accedir ha de redirigir-lo
        // a la pàgina d'inici de sessió
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        //$this->assertSelectorTextContains('h1', 'Hello World');
    }

    /**
     * @return void
     * @dataProvider getUrlsForAnonymousUsers
     */
    public function testAnonymousAccessControl(string $url, int $status, string $method = 'GET'): void
    {
        $client = static::createClient();
        $crawler = $client->request($method, $url);

        $this->assertResponseStatusCodeSame($status);
    }
    public function getUrlsForAnonymousUsers(): iterable
    {
        yield ["/", Response::HTTP_FOUND];
        yield ["/login", Response::HTTP_OK];
        yield ["/invalid-route", Response::HTTP_NOT_FOUND];
        yield ["/register", Response::HTTP_OK];
        yield ["/logout", Response::HTTP_FOUND];
        yield ["/compose/tweet", Response::HTTP_FOUND];
        yield ["/home", Response::HTTP_OK];
        yield ["/user", Response::HTTP_OK];
        yield ["/tweets/12/like", Response::HTTP_FOUND];
    }

}

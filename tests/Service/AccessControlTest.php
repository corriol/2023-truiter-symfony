<?php

namespace App\Tests\Service;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AccessControlTest extends WebTestCase
{
    /**
     * @return void
     */
    public function testHomepageWorks(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseRedirects();
        $client->followRedirect();

        $this->assertSelectorTextContains('h2', 'Últims tuits');
    }

    /**
     * @param string $url
     * @param int $statusCode
     * @param string $method
     * @return void
     * @dataProvider getAnonymousData
     */
    public function testAnonymousAccessControlWorks(string $url, int $statusCode,
                                                string $method = 'GET') {

        $client = static::createClient();
        $crawler = $client->request($method, $url);

        $this->assertResponseStatusCodeSame($statusCode);
    }

    public function getAnonymousData(): iterable
    {
        yield  ["/register", 200];
        yield ["/login", 200];

    }


    public function testAnonymousUsersCannotComposeTweets() {
        $client = static::createClient();
        $crawler = $client->request('GET', '/compose/tweet');

        $this->assertResponseStatusCodeSame(302);
    }

    public function testLoggedUsersCanComposeTweets() {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneBy(["username"=>'user']);

        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/compose/tweet');

        $this->assertResponseStatusCodeSame(200);
    }


}

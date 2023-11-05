<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostControllerTest extends WebTestCase
{
    public function testSomething(): void
    {
        // This calls KernelTestCase::bootKernel(), and creates a
        // "client" that is acting as the browser
        $client = static::createClient([], [
            'HTTPS'             => false,
            'HTTP_HOST'         => 'php',
            'HTTP_USER_AGENT'   => 'MySuperBrowser/1.0',
        ]);

        // Request a specific page
        $crawler = $client->request('GET', '/test.php');

        // Validate a successful response and some content
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Testing');
    }
}
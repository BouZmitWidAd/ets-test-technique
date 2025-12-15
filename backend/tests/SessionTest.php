<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SessionTest extends WebTestCase
{
    private function login($client): string
    {
        $client->request(
            'POST',
            '/api/login',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'email' => 'test@test.com',
                'password' => 'password'
            ])
        );

        $data = json_decode($client->getResponse()->getContent(), true);
        return $data['token'];
    }

    public function testGetSessions(): void
    {
        $client = static::createClient();
        $token = $this->login($client);

        $client->request(
            'GET',
            '/api/sessions',
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer '.$token]
        );

        $this->assertResponseIsSuccessful();
    }
}
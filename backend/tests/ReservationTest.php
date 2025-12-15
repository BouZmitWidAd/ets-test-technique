<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ReservationTest extends WebTestCase
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

        return json_decode($client->getResponse()->getContent(), true)['token'];
    }

    public function testCannotReserveSameSessionTwice(): void
    {
        $client = static::createClient();
        $token = $this->login($client);

        $sessionId = '693f7126da113771780fcfb5';

        $client->request(
            'POST',
            '/api/reservations',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$token
            ],
            json_encode(['sessionId' => $sessionId])
        );

        // deuxième tentative
        $client->request(
            'POST',
            '/api/reservations',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => 'Bearer '.$token
            ],
            json_encode(['sessionId' => $sessionId])
        );

        $this->assertResponseStatusCodeSame(400);
    }
}
<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ReservationCancelTest extends WebTestCase
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

        $this->assertResponseIsSuccessful();

        return json_decode($client->getResponse()->getContent(), true)['token'];
    }

    public function testUserCanCancelReservation(): void
    {
        $client = static::createClient();
        $token = $this->login($client);

        // 1️⃣ Récupérer les réservations existantes
        $client->request(
            'GET',
            '/api/reservations/me',
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer ' . $token]
        );

        $this->assertResponseIsSuccessful();

        $reservations = json_decode($client->getResponse()->getContent(), true);

        // S'il n'y a aucune réservation, le test est ignoré
        if (count($reservations) === 0) {
            $this->markTestSkipped('Aucune réservation à annuler');
        }

        $reservationId = $reservations[0]['id'];

        // 2️⃣ Annuler la réservation
        $client->request(
            'DELETE',
            '/api/reservations/' . $reservationId,
            [],
            [],
            ['HTTP_AUTHORIZATION' => 'Bearer ' . $token]
        );

        $this->assertResponseIsSuccessful();
    }
}
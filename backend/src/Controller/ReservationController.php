<?php

namespace App\Controller;

use App\Document\Reservation;
use App\Document\Session;
use Doctrine\ODM\MongoDB\DocumentManager;
use MongoDB\BSON\ObjectId;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    #[Route('/api/reservations', methods: ['POST'])]
    public function create(Request $request, DocumentManager $dm): JsonResponse
    {
        $user = $this->getUser();
        $data = json_decode($request->getContent(), true);

        if (!isset($data['sessionId'])) {
            return $this->json(['error' => 'sessionId is required'], 400);
        }
        

        try {
            $sessionId = new ObjectId($data['sessionId']);
        } catch (\Throwable $e) {
            return $this->json(['error' => 'Invalid session id'], 400);
        }

        $session = $dm->getRepository(Session::class)->find($sessionId);
        if (!$session) {
            return $this->json(['error' => 'Session not found'], 404);
        }

        if ($session->getAvailablePlaces() <= 0) {
            return $this->json(['error' => 'No available places'], 400);
        }

        $existing = $dm->getRepository(Reservation::class)->findOneBy([
            'user' => $user,
            'session' => $session
        ]);

        if ($existing) {
            return $this->json(['error' => 'Already reserved'], 400);
        }

        $reservation = new Reservation();
        $reservation->setUser($user);
        $reservation->setSession($session);

        $session->setAvailablePlaces($session->getAvailablePlaces() - 1);

        $dm->persist($reservation);
        $dm->flush();

        return $this->json(['message' => 'Reservation created'], 201);
    }

    #[Route('/api/reservations/me', methods: ['GET'])]
    public function myReservations(DocumentManager $dm): JsonResponse
    {
        $user = $this->getUser();

        $reservations = $dm->getRepository(Reservation::class)
            ->findBy(['user' => $user]);

        $data = array_map(fn(Reservation $r) => [
            'id' => (string) $r->getId(),
            'sessionId' => (string) $r->getSession()->getId(),
            'language' => $r->getSession()->getLanguage(),
            'date' => $r->getSession()->getDate()->format('Y-m-d H:i'),
            'location' => $r->getSession()->getLocation(),
        ], $reservations);

        return $this->json($data);
    }

    #[Route('/api/reservations/{id}', methods: ['DELETE'])]
    public function cancel(string $id, DocumentManager $dm): JsonResponse
    {
        $user = $this->getUser();

        try {
            $reservationId = new ObjectId($id);
        } catch (\Throwable $e) {
            return $this->json(['error' => 'Invalid reservation id'], 400);
        }

        $reservation = $dm->getRepository(Reservation::class)->find($reservationId);

        if (!$reservation || $reservation->getUser() !== $user) {
            return $this->json(['error' => 'Reservation not found'], 404);
        }

        $session = $reservation->getSession();
        $session->setAvailablePlaces($session->getAvailablePlaces() + 1);

        $dm->remove($reservation);
        $dm->flush();

        return $this->json(['message' => 'Reservation cancelled']);
    }
}
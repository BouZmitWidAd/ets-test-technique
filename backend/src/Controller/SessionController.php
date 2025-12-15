<?php

namespace App\Controller;

use App\Document\Session;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use MongoDB\BSON\ObjectId;

class SessionController extends AbstractController
{

    #[Route('/api/sessions', methods: ['POST'])]
public function create(
    Request $request,
    DocumentManager $dm
): JsonResponse {
    $data = json_decode($request->getContent(), true);

    $session = new Session();
    $session->setLanguage($data['language']);
    $session->setDate(new \DateTime($data['date']));
    $session->setLocation($data['location']);
    $session->setAvailablePlaces((int) $data['availablePlaces']);

    $dm->persist($session);
    $dm->flush();

    return $this->json(['message' => 'Session created'], 201);
}

    #[Route('/api/sessions', methods: ['GET'])]
    public function list(DocumentManager $dm): JsonResponse
    {
        $sessions = $dm->getRepository(Session::class)->findAll();

        $data = array_map(fn(Session $s) => [
            'id' => (string) $s->getId(),
            'language' => $s->getLanguage(),
            'date' => $s->getDate()->format('Y-m-d H:i'),
            'location' => $s->getLocation(),
            'availablePlaces' => $s->getAvailablePlaces(),
        ], $sessions);

        return $this->json($data);
    }

    #[Route('/api/sessions/{id}', methods: ['PUT'])]
public function update(string $id, Request $request, DocumentManager $dm): JsonResponse
{
    $session = $dm->getRepository(Session::class)->find(new ObjectId($id));

    if (!$session) {
        return $this->json(['error' => 'Session not found'], 404);
    }

    $data = json_decode($request->getContent(), true);

    if (isset($data['language'])) {
        $session->setLanguage($data['language']);
    }
    if (isset($data['date'])) {
        $session->setDate(new \DateTime($data['date']));
    }
    if (isset($data['location'])) {
        $session->setLocation($data['location']);
    }
    if (isset($data['availablePlaces'])) {
        $session->setAvailablePlaces((int) $data['availablePlaces']);
    }

    $dm->flush();

    return $this->json(['message' => 'Session updated']);
}
#[Route('/api/sessions/{id}', methods: ['DELETE'])]
public function delete(string $id, DocumentManager $dm): JsonResponse
{
    try {
        $objectId = new \MongoDB\BSON\ObjectId($id);
    } catch (\Throwable $e) {
        return $this->json(['error' => 'Invalid session id'], 400);
    }

    $session = $dm->getRepository(Session::class)->find($objectId);

    if (!$session) {
        return $this->json(['error' => 'Session not found'], 404);
    }

    $dm->remove($session);
    $dm->flush();

    return $this->json(['message' => 'Session deleted']);
}
}
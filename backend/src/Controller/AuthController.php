<?php

namespace App\Controller;

use App\Document\User;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthController extends AbstractController
{
    /**
     * Endpoint pour l'enregistrement d'un nouvel utilisateur.
     * URL: /api/register
     * Méthode: POST
     *
     * @Route("/api/register", name="api_register", methods={"POST"})
     */
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        DocumentManager $dm
    ): JsonResponse {
        // 1. Décode les données JSON
        $data = json_decode($request->getContent(), true);

        // Validation simple
        if (!isset($data['email']) || !isset($data['password']) || !isset($data['nom'])) {
            return new JsonResponse([
                'message' => 'Missing email, password, or nom.'
            ], Response::HTTP_BAD_REQUEST);
        }

        // 2. Vérification si l'utilisateur existe déjà
        $existingUser = $dm->getRepository(User::class)->findOneBy(['email' => $data['email']]);
        if ($existingUser) {
            return new JsonResponse(['message' => 'User already exists.'], Response::HTTP_CONFLICT);
        }

        // 3. Création et hydratation du nouvel utilisateur
        $user = new User();
        $user->setEmail($data['email']);
        $user->setNom($data['nom']);
        // setRoles n'est pas nécessaire ici si votre getRoles() assure déjà 'ROLE_USER'

        // 4. Hachage du mot de passe
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $data['password']
        );
        $user->setPassword($hashedPassword);

        // 5. Sauvegarde dans MongoDB
        $dm->persist($user);
        $dm->flush();

        return new JsonResponse(['message' => 'User registered successfully!'], Response::HTTP_CREATED);
    }
}
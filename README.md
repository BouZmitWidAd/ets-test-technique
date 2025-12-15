# ETS Test Technique
Application de réservation de sessions de tests linguistiques

# Technologies utilisées
Symfony 6+
Doctrine MongoDB ODM
LexikJWTAuthenticationBundle
Validation Symfony
PHPUnit (tests fonctionnels)

# Authentification (JWT)
Inscription utilisateur
Authentification via /api/login
Génération d’un JWT
Protection des routes via firewall Symfony
Exemple de réponse login :
{
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
}

# Gestion des sessions de tests
Fonctionnalités :
Création de sessions
Lecture (avec pagination)
Modification
Suppression
Champs principaux :
langue
date / heure
lieu
nombre de places disponibles

# Gestion des réservations
Fonctionnalités :
Réserver une session
Empêcher une double réservation
Lister les réservations de l’utilisateur connecté
Annuler une réservation
Une règle métier empêche un utilisateur de réserver deux fois la même session.

## Base de données
MongoDB Atlas (cloud)

## Frontend – Next.js / React / Axios
Fonctionnalités frontend
 ## Page de connexion
formulaire moderne (Material UI)
authentification via JWT
stockage du token en localStorage
redirection automatique
 ## Page Sessions
affichage des sessions disponibles sous forme de DataTable
bouton Réserver si la session n’est pas encore réservée
actions conditionnelles selon l’état métier

 ## Page Réservations
affichage des réservations de l’utilisateur
possibilité d’annuler une réservation
affichage conditionnel si aucune réservation
Tous les appels API sont effectués via Axios.

## Tests
Tests fonctionnels backend avec PHPUnit

## Dockerisation
séparation backend / frontend
variables d’environnement externalisées
base de données cloud (MongoDB Atlas)
Une dockerisation complète peut être ajoutée avec :
Dockerfile backend
Dockerfile frontend
docker-compose.yml
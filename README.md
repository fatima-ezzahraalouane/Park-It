# 🚗 Park' It - API de Gestion de Parkings 🅿️

Bienvenue sur **Park' It** 🚀, une API moderne et intuitive pour gérer les parkings, permettant aux utilisateurs de consulter la disponibilité des places, de réserver en toute simplicité et de gérer leurs réservations. Développée avec **Laravel**, cette solution vise à faciliter l'accès au stationnement en temps réel. 🏙️

---

## 🌟 Contexte du Projet

L'objectif principal est de développer une **API REST** robuste permettant :
- 🔍 La recherche de parkings et la consultation de leur disponibilité.
- 📅 La réservation de places pour une durée définie.
- 🔄 La gestion et l’annulation des réservations.
- 📊 Un suivi des statistiques et un historique des réservations.
- 🔐 Une gestion administrative avancée des parkings.

---

## 📖 User Stories

### 🎯 Utilisateurs

✅ **Authentification & Sécurité**
- 🔒 En tant qu'utilisateur, je souhaite m'authentifier via **Laravel Sanctum**.

✅ **Recherche & Réservation**
- 🚗 Je veux rechercher une place disponible dans une zone spécifique avec une indication **en temps réel**.
- 📅 Je veux réserver une place de parking pour une **période spécifique** (heure d’arrivée & départ).
- 🔄 Je veux **modifier** ma réservation en changeant l'heure d'arrivée ou de départ.
- ❌ Je veux **annuler** ma réservation si nécessaire.
- 📊 Je veux consulter mon **historique de réservations**.

### 🎯 Administrateurs

✅ **Gestion des Parkings**
- 🔧 En tant qu’administrateur, je veux **ajouter, modifier ou supprimer** des parkings.
- 🏢 Je veux **définir et mettre à jour** le nombre total de places disponibles.
- 📊 Je veux visualiser **les statistiques** liées aux parkings et aux réservations.

### 🎯 Développeurs

✅ **Qualité & Performance**
- 🧪 Des **tests unitaires** doivent être réalisés pour chaque fonctionnalité.
- 📝 Des tests sur **Postman** doivent valider le bon fonctionnement de l’API.
- 📄 Une **documentation détaillée** sera disponible avec **Swagger**.
- 🚀 Le système doit **mettre à jour automatiquement** la disponibilité des places après expiration des réservations, en utilisant **les queues et jobs Laravel** ou une autre solution efficace.

---

## 🚀 Technologies Utilisées

- **Laravel** 🏗️ (Backend robuste & API RESTful)
- **PostgreSQL** 🗄️ (Gestion des données)
- **Laravel Sanctum** 🔒 (Authentification sécurisée)
- **Swagger/Postman** 📄 (Documentation API)
- **Queues & Jobs Laravel** ⏳ (Mise à jour automatique des réservations)

---

## 📌 Endpoints Principaux

### 🔐 Authentification
- `POST /api/register` - Inscription d'un utilisateur
- `POST /api/login` - Connexion

### 🚗 Parkings
- `GET /api/parkings` - Liste des parkings
- `GET /api/parkings/{id}` - Détails d’un parking
- `POST /api/parkings` - Ajouter un parking (Admin)
- `PUT /api/parkings/{id}` - Modifier un parking (Admin)
- `DELETE /api/parkings/{id}` - Supprimer un parking (Admin)

### 📅 Réservations
- `POST /api/reservations` - Réserver une place
- `GET /api/reservations` - Voir mes réservations
- `PUT /api/reservations/{id}` - Modifier une réservation
- `DELETE /api/reservations/{id}` - Annuler une réservation

---

🚀 **Prêt à garer votre voiture avec Park' It ?** 🅿️💙
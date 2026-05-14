# LMS API - Plateforme de gestion d'une école de formation
API REST développée avec **Laravel** pour la gestion des étudiants, cours, formateurs, inscriptions. 
Inclut une documentation Swagger, une collection Postman, une intégration OpenAI et un système de Webhook pour le paiement.

## Instructions d'installation et de lancement

### Cloner le projet
```bash
git clone git@github.com:Karmy3/LMS.git
cd lms_app
composer install
```
### Configurer l’environnement
```bash
cp .env.example .env
```
Configurer les variables essentielles dans le fichier .env :

Code snippet
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lms_app
DB_USERNAME=root
DB_PASSWORD=

JWT_SECRET=your_secret
WEBHOOK_SECRET=secret_examen_2026
APP_URL=http://localhost:8000

Puis générer la clé de l'application :
```bash
php artisan key:generate
```
### Préparer la base de données
```bash
php artisan migrate --seed
```
Cela crée automatiquement les tables suivantes avec des données de test (seeders) :
students
instructors
courses
enrollments

### Lancer le serveur Laravel
```bash
php artisan serve
```
API disponible sur : http://localhost:8000/api/v1

Swagger UI : http://localhost:8000/api/documentation

### Tests & fonctionnalités avancées
#### Postman
Importer la collection fournie.

Configurer les variables d'environnement :

{{base_url}} = http://localhost:8000/api/v1

{{token}} récupéré via le login.

#### Swagger
Tester les endpoints directement via l’interface interactive.

#### OpenAI (optionnel)
Ajouter votre clé API dans le fichier .env :
Code snippet
GOOGLE_API_KEY=xxxx

#### Webhook paiement
Le système accepte les notifications de paiement automatisées.
URL : POST /api/v1/webhooks/payment
Sécurité : Une signature HMAC est obligatoire, basée sur la clé WEBHOOK_SECRET.
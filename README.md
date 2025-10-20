# Services App — Refonte (PHP from scratch)

# Projet Par Paul et Jules

## But
Refactorer le mini-projet fourni pour appliquer des bonnes pratiques : séparation des couches, modèles clairs, validations, README, .gitignore et outillage (php-cs-fixer).

## Choix techniques
- **PHP (sans framework)** — MVC léger explicitement.
- **JSON** pour persistance (fichiers dans `data/`).
- **PHP-CS-Fixer** pour formatage, PHPUnit (optionnel) pour quelques tests.

## Installation & lancement
1. git clone <repo>
2. composer install (optionnel si on ajoute des dépendances)
3. php -S localhost:8000 -t public
4. Ouvrir http://localhost:8000

## Structure
- public/ — point d'entrée web (`index.php`, `assets/`)
- src/ — code applicatif (Http, Controllers, Services, Models, Repository)
- data/ — fichiers JSON persistants
- templates/ — vues HTML simples
- tools/ — config qualité (.php-cs-fixer.dist.php)



## Notes
- Données JSON auto-initialisées à la première exécution.
- Pour formatter le code: `php ./vendor/bin/php-cs-fixer fix --config=tools/.php-cs-fixer.dist.php` (si installé globalement, `php-cs-fixer fix`).

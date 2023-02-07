# TODOLIST 

## Fonctionnalités
- Créer un compte
- Se connecter
- Réinitialiser le mot de passe
- Ajouter une tâche
- Modifier une tâche
- Supprimer une tâche
- Ajouter le statu tâche à faire
- Ajouter le statu tâche important
- Ajouter le statu tâche terminer

## Utilisation
- Télécharger le depot git `git clone https://github.com/ousseine/todolist`
- Installer les dépendances `composer install`
- Télécharger les assets `yarn install`
- Créer la base de données `symfony console doctrine:database:create`
- Migrer les entités vers la base de données `symfony console make:migration && symfony console doctrine:migrations:migrate`
- Lancé le server `symfony serve -d && yarn dev-server`
- Utiliser le projet a votre guise
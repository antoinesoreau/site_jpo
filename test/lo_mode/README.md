# MODERATION

## lo_mode.php

Est l'api qui permet de lire ou d'editer la table commentaire
url api actuel (en localhost) :   http://localhost/JPO/site_jpo/test/lo_mode/lo_mode.php

### Pour modifier la table commentaire 
il suffit d'entre l'url de l'api avec le mode lecture '?action=lecture'

- pour afficher un nombre d'element definit il faut ajouter : "limit=limite_souhaiter"

### Pour modifier le Status de la table commentaire
il faut entre l'url de l'api en mode edition '?action=edition'

- pour choisir quelle commentaire modifier entre '&id=id_comentaire_a_modifier'
- pour choisir le nouvelle etat de la table entre '&status=nouveau_status'
    - 0 : activé/publier
    - 1 : inactivé/masqué

## lo_mode.js 

Permet de faire afficher le tableau de tous les commentaires 

Nom | Commentaire | Note | Status (Validé/Invalidé) | Action

la table commentaire se met ajour toutes les 30s avec les nouveaux commentaire

## lo_mode.css

Est le fichier style associé a la page

## lo_mode.html

Est le fichier dom de la page

## jpo.sql

La base de donnée est temporaire, elle sert actuellement a faire des testes
# CLAUDE.md — règles de travail

Ce fichier s'adresse aux agents de code (Claude Code et autres) qui interviennent sur ce dépôt. Il n'est pas déployé sur le site (exclu dans `.github/workflows/deploy.yml`).

## Règles générales (tous les projets de Jean Luc)

Les projets commencent souvent par une discussion avant tout code.

1. **Partir de l'existant** : vérifier les outils connectés (GitHub, Vercel, Railway) et la façon dont les projets existants (ex. NSILI) font les choses avant de proposer du neuf.
2. **Garder le périmètre simple** : challenger chaque nouvelle notion ou fonctionnalité ; décider explicitement avec Jean Luc du niveau de complexité (ex. hors connexion) avant de construire.
3. **Code** : toujours via une branche et une PR, jamais directement sur la branche principale ; demander une revue Copilot et traiter ses retours ; c'est Jean Luc qui merge. Commits signés `Claude <noreply@anthropic.com>`.
4. **Avant de livrer du code**, revue critique avec cette liste de contrôle :
   - droits et révocation ;
   - actions simultanées ;
   - coupure réseau ;
   - réponses périmées ;
   - plusieurs onglets / appareils ;
   - stockage plein ;
   - données personnelles dans les exports.
5. **Regrouper les corrections** avant de redemander une revue Copilot (quota limité). Pour une revue de tout le code : base vide sans historique commun.
6. **Déploiement** : reprendre la configuration d'un projet existant (ex. NSILI sur Vercel) plutôt que réinventer.
7. Quand Jean Luc dit qu'une configuration est faite, **la vérifier aussitôt**.
8. **Toujours mettre la réponse à sa question dans le message final.**
9. **Annoncer d'emblée les limites de l'environnement** (actions impossibles depuis la session).
10. Quand un projet passe sur git, écrire ces règles dans un `CLAUDE.md` à la racine du dépôt.

11. **Sources** : toute affirmation publiée (site, CV) s'appuie sur un document ou une décision de Jean Luc ; sans source, poser la question, ne jamais déduire ni compléter. Ne pas reformuler un texte validé. Vérifier un fait à sa source (GitHub, Vercel, documents) plutôt que sur une description.


## Spécificités de ce dépôt (dreamlogistics.net)

- **Site statique** (HTML, CSS, jQuery) à la racine du dépôt, en français.
- **Formulaires** (contact et commentaires du blog) : `envoi.php`, sur l'hébergement Hostinger, envoie le message à **contact@dreamlogistics.net** avec la fonction `mail()`. Aucun service tiers, rien n'est enregistré sur le serveur. Champ caché `formulaire` (`contact` ou `commentaire`), pot de miel `site_web`, validation côté serveur ; succès vers `merci.html`, échec vers une page d'erreur avec l'adresse de contact. L'envoi réel ne se teste qu'en production. Même mécanisme que nzuane.org.
- **Branche principale** : `main`. Chaque merge déclenche le déploiement FTPS vers Hostinger (`.github/workflows/deploy.yml`, secrets `FTP_SERVER`, `FTP_USERNAME`, `FTP_PASSWORD`). Ne jamais écrire de secret dans le dépôt.
- **Fichiers non publics** : tout fichier ajouté au dépôt est publié, sauf s'il figure dans la liste `exclude` du workflow.
- **Tests avant PR** : largeur ordinateur et smartphone (ex. Pixel 7, 412 px) : pas de débordement horizontal, aucune erreur JS.

---
ai_project: null
status: en cours
---

## Attentes
- Site vitrine de Dream Logistics (dreamlogistics.net), hébergé chez Hostinger.
- Formulaires de contact et de commentaires envoyés par Hostinger (`envoi.php`) vers contact@dreamlogistics.net, en remplacement d'EmailJS (en PR).

## Blocages
- Aucun

## Prochaines étapes
- Créer les secrets GitHub du déploiement (`FTP_SERVER` = nom d'hôte du serveur Hostinger, `FTP_USERNAME`, `FTP_PASSWORD`) et, si besoin, la variable `FTP_SERVER_DIR`, puis merger la PR.
- Après le premier déploiement : envoyer un message de test depuis contact.html et un commentaire depuis blog.html, et vérifier leur réception sur contact@dreamlogistics.net (indésirables compris).
- Supprimer le service et le modèle EmailJS désormais inutilisés.

<?php
// Réception des formulaires du site : chaque message est envoyé par e-mail à l'adresse du domaine
// hébergée chez Hostinger. Aucun service tiers, rien n'est enregistré sur le serveur.
// Même mécanisme que nzuane.org (sites/local/envoi.php).

declare(strict_types=1);

// ---------------------------------------------------------------- configuration du site
const DESTINATAIRE = 'contact@dreamlogistics.net';
const EXPEDITEUR = 'Dream Logistics <contact@dreamlogistics.net>';
const PAGE_MERCI = 'merci.html';

const ACADEMIES = ['Cameroun' => 'Ebolowa, Cameroun', 'RDC' => 'Kinshasa, RDC', 'CotedIvoire' => "Abidjan, Côte d'Ivoire"];

$formulaires = [
    'contact' => [
        'introduction' => 'Nouveau message reçu depuis le formulaire de contact de dreamlogistics.net.',
        'sujet' => fn (array $v): string => '[' . (ACADEMIES[$v['academie']] ?? 'Académie non précisée') . '] '
            . ($v['sujet'] !== '' ? $v['sujet'] : 'Message depuis le site'),
        'champs' => [
            'nom' => ['libelle' => 'Nom', 'requis' => true],
            'email' => ['libelle' => 'E-mail', 'type' => 'email'],
            'telephone' => ['libelle' => 'Téléphone'],
            'academie' => ['libelle' => 'Académie', 'type' => 'choix', 'choix' => array_keys(ACADEMIES)],
            'sujet' => ['libelle' => 'Sujet'],
            'message' => ['libelle' => 'Message', 'type' => 'long', 'requis' => true],
        ],
        'joignable' => ['email', 'telephone'],
    ],
    'commentaire' => [
        'introduction' => 'Nouveau commentaire reçu depuis le blog de dreamlogistics.net.',
        'sujet' => fn (array $v): string => 'Nouveau commentaire chez Dream Logistics',
        'champs' => [
            'nom' => ['libelle' => 'Nom', 'requis' => true],
            'email' => ['libelle' => 'E-mail', 'type' => 'email'],
            'telephone' => ['libelle' => 'Téléphone'],
            'message' => ['libelle' => 'Commentaire', 'type' => 'long', 'requis' => true],
        ],
    ],
];

const PAGE_ERREUR = <<<'HTML'
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex">
  <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
  <link rel="stylesheet" href="style.css">
  <title>Message non envoyé - Dream Logistics</title>
</head>
<body>
  <section class="sub-header">
    <nav><a href="index.html"><img src="images/logo-def.png" alt="Dream Logistics, retour à l'accueil"></a></nav>
    <h1>message non envoyé</h1>
  </section>
  <section class="contactus" style="padding: 50px 0; text-align: center;">
    <p>Votre message n'a pas pu être envoyé. Vérifiez que votre nom, votre message et un moyen de vous répondre (e-mail ou téléphone) sont bien renseignés, puis réessayez.</p>
    <p>Si le problème persiste, écrivez-nous directement à <a href="mailto:contact@dreamlogistics.net">contact@dreamlogistics.net</a>.</p>
    <p style="margin-top: 20px;"><a class="hero-btn primary-btn" href="contact.html">retour au formulaire</a></p>
  </section>
</body>
</html>
HTML;
// ---------------------------------------------------------------- fin de la configuration

function valeur(string $nom, int $longueurMax): string
{
    $valeur = $_POST[$nom] ?? '';
    if (!is_string($valeur)) {
        return '';
    }
    return mb_substr(trim($valeur), 0, $longueurMax);
}

// Les retours à la ligne sont retirés des champs d'une ligne, pour empêcher l'injection d'en-têtes.
function surUneLigne(string $valeur): string
{
    return preg_replace('/[\r\n]+/', ' ', $valeur) ?? '';
}

function pageErreur(int $code): never
{
    http_response_code($code);
    header('Content-Type: text/html; charset=UTF-8');
    echo PAGE_ERREUR;
    exit;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    header('Allow: POST');
    pageErreur(405);
}

// Robot : le champ caché est rempli. On fait comme si tout allait bien, sans rien envoyer.
if (valeur('site_web', 200) !== '') {
    header('Location: ' . PAGE_MERCI, true, 303);
    exit;
}

$nomFormulaire = valeur('formulaire', 50);
if (!isset($formulaires[$nomFormulaire])) {
    pageErreur(400);
}
$formulaire = $formulaires[$nomFormulaire];

$valeurs = [];
foreach ($formulaire['champs'] as $nom => $champ) {
    $type = $champ['type'] ?? 'texte';
    $longueurMax = $type === 'long' ? 5000 : 200;
    $v = valeur($nom, $longueurMax);
    if ($type !== 'long') {
        $v = surUneLigne($v);
    }
    if ($v === '' && !empty($champ['requis'])) {
        pageErreur(400);
    }
    if ($v !== '' && $type === 'email' && filter_var($v, FILTER_VALIDATE_EMAIL) === false) {
        pageErreur(400);
    }
    if ($v !== '' && $type === 'choix' && !in_array($v, $champ['choix'], true)) {
        pageErreur(400);
    }
    $valeurs[$nom] = $v;
}

// Au moins un moyen de répondre (e-mail ou téléphone), quand le formulaire l'exige.
if (!empty($formulaire['joignable'])) {
    $joignable = false;
    foreach ($formulaire['joignable'] as $nom) {
        if (($valeurs[$nom] ?? '') !== '') {
            $joignable = true;
        }
    }
    if (!$joignable) {
        pageErreur(400);
    }
}

$sujet = surUneLigne(($formulaire['sujet'])($valeurs));

$lignes = [$formulaire['introduction'], ''];
foreach ($formulaire['champs'] as $nom => $champ) {
    $v = $valeurs[$nom] !== '' ? $valeurs[$nom] : '(non renseigné)';
    if (($champ['type'] ?? 'texte') === 'long') {
        $lignes[] = '';
        $lignes[] = $champ['libelle'] . ' :';
        $lignes[] = $v;
    } else {
        $lignes[] = $champ['libelle'] . ' : ' . $v;
    }
}

$entetes = [
    'From' => EXPEDITEUR,
    'MIME-Version' => '1.0',
    'Content-Type' => 'text/plain; charset=UTF-8',
    'Content-Transfer-Encoding' => '8bit',
];
$emailVisiteur = $valeurs['email'] ?? '';
if ($emailVisiteur !== '') {
    $entetes['Reply-To'] = $emailVisiteur;
}

$envoye = mail(DESTINATAIRE, mb_encode_mimeheader($sujet, 'UTF-8'), implode("\n", $lignes), $entetes);

if (!$envoye) {
    pageErreur(500);
}

header('Location: ' . PAGE_MERCI, true, 303);
exit;

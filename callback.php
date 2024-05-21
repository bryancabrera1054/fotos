<?php
require 'vendor/autoload.php';

session_start();

$client = new Google_Client();
$client->setClientId('237603080191-oebfa4ipbd35t2o3ten942nespugkbjt.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('https://bryancabrera1054.github.io/fotos/callback.php');

if (!isset($_GET['code'])) {
    $authUrl = $client->createAuthUrl();
    header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
    exit();
} else {
    $client->authenticate($_GET['code']);
    $_SESSION['access_token'] = $client->getAccessToken();
    header('Location: index.php');
}
?>

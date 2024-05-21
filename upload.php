<?php
require 'vendor/autoload.php';

session_start();

// Configuración de las credenciales de OAuth 2.0
$client = new Google_Client();
$client->setClientId('237603080191-oebfa4ipbd35t2o3ten942nespugkbjt.apps.googleusercontent.com');
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost:85/fotos/callback.php');
$client->addScope(Google_Service_Drive::DRIVE_FILE);

// Redirigir a Google OAuth 2.0
if (!isset($_SESSION['access_token'])) {
    $authUrl = $client->createAuthUrl();
    header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
    exit();
} else {
    $client->setAccessToken($_SESSION['access_token']);
}

// ID de la carpeta de destino en Google Drive
$folderId = '1bKnbVN6q4qaWBwPdf1zX6asreLKAPfzMgit';

// Subir archivos a Google Drive
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $service = new Google_Service_Drive($client);
    
    foreach ($_FILES['file']['tmp_name'] as $key => $tmp_name) {
        $file = new Google_Service_Drive_DriveFile();
        $file->setName($_FILES['file']['name'][$key]);
        $file->setParents([$folderId]);

        $content = file_get_contents($tmp_name);
        $service->files->create($file, [
            'data' => $content,
            'mimeType' => mime_content_type($tmp_name),
            'uploadType' => 'multipart'
        ]);
    }
    echo 'Fotos subidas con éxito.';
}
?>
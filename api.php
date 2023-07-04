<?php

require 'includes/_database.php';

session_start();

$data = json_decode(file_get_contents('php://input'), true);

$isOk = false;

if (!array_key_exists('token', $_SESSION) || !array_key_exists('token', $data)
    || $_SESSION['token'] !== $data['token']) {
    header('content-type:application/json');
    echo json_encode([
        'result' => 'false',
        'error' => 'Accès refusé, jeton invalide.'
    ]);
    exit;
}

$idArticle = (int)strip_tags($data['idArticle']);
$price = 0;

if ($data['action'] === 'increase' && $_SERVER['REQUEST_METHOD'] === 'PUT') {
    $query = $dbCo->prepare("UPDATE `article` SET `purchase_price` = `purchase_price` + 10 WHERE `id_article` = :id_article;");
    $isOk = $query->execute([
        'id_article' => $idArticle
    ]);

    if ($isOk) {
        $query = $dbCo->prepare("SELECT `purchase_price` FROM `article` WHERE `id_article` = :id_article;");
        $isOk = $query->execute([
            'id_article' => $idArticle
        ]);
        $price = $query->fetchColumn();
    }
}

header('content-type:application/json');
echo json_encode([
    'result' => $isOk,
    'idArticle' => $idArticle,
    'price' => (float)$price
]);




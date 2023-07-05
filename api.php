<?php

require 'includes/_database.php';

session_start();

header('content-type:application/json');

$data = json_decode(file_get_contents('php://input'), true);

$isOk = false;

if (
    !array_key_exists('token', $_SESSION) || !array_key_exists('token', $data)
    || $_SESSION['token'] !== $data['token']
) {
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

    echo json_encode([
        'result' => $isOk,
        'idArticle' => $idArticle,
        'price' => (float)$price
    ]);
    exit;
}

if ($data['action'] === 'rename' && $_SERVER['REQUEST_METHOD'] === 'PUT') {
    $id = intval(strip_tags($data['idArticle']));
    $name = trim(strip_tags($data['articleName']));
    $query = $dbCo->prepare("UPDATE `article` SET `article_name` = :articleName WHERE `id_article` = :idArticle;");
    $isOk = $query->execute([
        'idArticle' => $id,
        'articleName' => $name
    ]);
    echo json_encode([
        'result' => $isOk && $query->rowCount() > 0,
        'idArticle' => $id,
        'articleName' => $name
    ]);
    exit;
}

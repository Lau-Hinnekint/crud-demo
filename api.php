<?php

require 'includes/_database.php';

$data = json_decode(file_get_contents('php://input'), true);

$isOk = false;
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
$datas = [
    'result' => $isOk,
    'idArticle' => $idArticle,
    'price' => (float)$price
];
echo json_encode($datas);




<?php

require 'includes/_database.php';

session_start();

$isOk = false;
if (!array_key_exists('HTTP_REFERER', $_SERVER)
    || !str_contains($_SERVER['HTTP_REFERER'], 'http://localhost/intro-php/')) {
    header('Location: index.php?msg=error_referer');
    exit;
}
else if (!array_key_exists('token', $_SESSION) || !array_key_exists('token', $_REQUEST)
    || $_SESSION['token'] !== $_REQUEST['token']) {
    header('Location: index.php?msg=error_csrf');
    exit;
}



if ($_REQUEST['action'] === 'add' && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $query = $dbCo->prepare("INSERT INTO `article`(`article_name`, `purchase_price`, `volume`, `id_brand`)
        VALUES (:name, :price, :volume, :id_brand)");
    $isOk = $query->execute([
        'name' => strip_tags($_POST['name']),
        'price' => strip_tags($_POST['price']),
        'volume' => '200',
        'id_brand' => 1
    ]);

    // echo '<p>' . ($isOk ? 'La bière a été ajoutée.' : 'Problème lors de l\'ajout') . '</p>';
}
else if ($_REQUEST['action'] === 'increase' && $_SERVER['REQUEST_METHOD'] == 'GET') {
    
}

header('Location: index.php?msg='.($isOk ? 'add_ok' : 'add_ko'));
exit;

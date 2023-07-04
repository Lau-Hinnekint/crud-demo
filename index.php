<?php

require 'includes/_database.php';

session_start();

$_SESSION['token'] = md5(uniqid(mt_rand(), true));

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test CRUD</title>
</head>

<body>
    <h1>TEST CRUD</h1>
    <?php

    if (array_key_exists('msg', $_GET)) {
        echo '<p>' . $_GET['msg'] . '</p>';
    }

    ?>
    <form action="actions.php" method="post">
        <div>
            <label for="add-name">Nom de la bière :</label>
            <input type="text" name="name" id="add-name">
        </div>
        <div>
            <label for="add-price">Prix :</label>
            <input type="text" name="price" id="add-price">
        </div>
        <input type="hidden" name="action" id="" value="add">
        <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>" id="token-csrf">
        <input type="submit" value="Ajouter">
    </form>

    <?php

    $query = $dbCo->prepare("SELECT id_article, article_name AS name, purchase_price AS price FROM article;");
    $isOk = $query->execute();
    // $query = $dbCo->query("SELECT article_name AS name, purchase_price AS price FROM article;");
    // $isOk = $query->execute();

    // var_dump($isOk); 
    // var_dump($dbCo->lastInsertId());
    // var_dump($query->rowCount());

    $articles = $query->fetchAll();

    echo '<ul>';
    foreach ($articles as $article) {
        echo '<li>' . $article['name'] . ' <span data-price-id="' . $article['id_article'] . '">' . $article['price'] . '</span> €
        <button type="button" class="js-btn-increase" data-id="' . $article['id_article'] . '">+</a>
        </li>';
    }
    // <a href="actions.php?action=increase&id=' . $article['id_article'] . '">+</a>
    echo '</ul>';
    ?>
    <script src="javascript/script.js"></script>
</body>

</html>
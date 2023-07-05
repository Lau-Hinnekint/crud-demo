<?php

require 'includes/_database.php';

session_start();

$_SESSION['token'] = md5(uniqid(mt_rand(), true));

$pageTitle = 'Test CRUD';
$cssFiles = [
    'css/style.css'
];
include 'includes/_header.php';

?>
<h1><?= $pageTitle ?></h1>
<?php

if (array_key_exists('msg', $_GET)) {
    echo '<p>' . $_GET['msg'] . '</p>';
}

?>
<form action="actions.php" method="post">
    <h2>Ajouter une bière</h2>
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

<section>
    <h2>Toutes les bières</h2>
    <ul>
        <?php

        $query = $dbCo->prepare("SELECT id_article, article_name AS name, purchase_price AS price FROM article;");
        $isOk = $query->execute();

        $articles = $query->fetchAll();

        foreach ($articles as $article) {
            echo '<li>
                <span data-name-id="' . $article['id_article'] . '">' . $article['name'] . '</span>
                <span data-price-id="' . $article['id_article'] . '">' . $article['price'] . '</span> €
                <button type="button" class="js-btn-increase" data-id="' . $article['id_article'] . '">+</button>
                <button type="button" class="js-btn-rename" data-id="' . $article['id_article'] . '">renommer</button>
        </li>';
        }

        ?>
    </ul>
</section>

<template id="renameFormTemplate">
    <form action="" method="post" data-form-id="">
        <input type="text" name="articleName" value="">
        <input type="hidden" name="idArticle" value="">
        <input type="submit" value="valider">
    </form>
</template>

<?php

include 'includes/_footer.php';

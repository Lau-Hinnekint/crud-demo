<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?></title>

    <?php

    if (isset($cssFiles)) {
        foreach ($cssFiles as $cssFile) {
            echo '<link rel="stylesheet" href="' . $cssFile . '">';
        }
    }

    ?>
</head>

<body>
<?php


require_once("templates/header.php");
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MovieStar</title>
    <link rel="stylesheet" href="<?= $BASE_URL ?>css/styles.css">
</head>


<body class=" <?= $THEME ?> nav-link bg-secondary">
    <div id="main-container" class="container-fluid">
        <h1>Conteúdo</h1>
    </div>


    <?php
    require_once("templates/footer.php");
    ?>
</body>

<html>
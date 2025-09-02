<?php

require_once "templates/header.php";

require_once "dao/MovieDAO.php";

// Dao dos Filmes
$movieDao = new MovieDAO($conn, $BASE_URL);

$lastestMovies = $movieDao->getLatestMovies();

$actionMovies = $movieDao->getMoviesByCategory("Ação");

$comedyMovies = $movieDao->getMoviesByCategory("Comédia");

$dramaMovies = $movieDao->getMoviesByCategory("Drama");

?>

<body>
    <div id="main-container" class="container-fluid">
        <h1 style="text-align: center;" class="page-title">Bem-vindo ao MovieStar!
        </h1>

    </div>
    <?php
    require_once "templates/footer.php";
    ?>
</body>
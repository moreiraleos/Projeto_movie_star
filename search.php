<?php
require_once("./templates/header.php");
require_once("dao/MovieDAO.php");
require_once("models/Movie.php");
require_once("dao/ReviewDAO.php");

$movieDao = new MovieDAO($conn, $BASE_URL);

$q = filter_input(INPUT_GET, 'q');

$movies = $movieDao->findByTitle("$q");
if (!$movies) {
    $movies = [];
}

?>


<div id="main-container" class="container-fluid">
    <h2 class="section-title" id="search-title">Você está buscando por: <span id="search-result"><?= $q ?></span></h2>
    <p class="section-description">Resultados de busca retornados com base na sua pesquisa.</p>
    <div class="movies-container">
        <?php foreach ($movies as $movie) : ?>
            <?php require("templates/movie_card.php"); ?>
        <?php endforeach; ?>
        <?php if (count($movies) === 0) : ?>
            <p class="empty-list">Não há filmes para essa busca, <a href="<?= $BASE_URL ?>index.php" class="back-link">voltar.</a></p>
        <?php endif; ?>
    </div>

</div>

<?php
include_once("./templates/footer.php");
?>
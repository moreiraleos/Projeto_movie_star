<?php
require_once("./templates/header.php");
require_once("dao/MovieDAO.php ");
require_once("models/Movie.php");
require_once("dao/ReviewDAO.php");
// Pegar o id do filme
// $userData = $userDao->verifyToken(true);

$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_SPECIAL_CHARS);

$movie;

$movieDao = new MovieDAO($conn, $BASE_URL);


$reviewDAO = new ReviewDAO($conn, $BASE_URL);


if (empty($id)) {
    $message->setMessage("O filme não foi encontrado!", "error");
} else {
    $movie = $movieDao->findById($id);

    if (!$movie) {
        $message->setMessage("O filme não foi encontrado!", "error");
    }
}

if ($movie->getImage() == "") {
    $movie->setImage("movie_cover.jpg");
}

$userOwnsMovie = false;

if (!empty($userData)) {
    if ($userData->getId() === $movie->getUsers_id()) {
        $userOwnsMovie = true;
    }

    $alreadyReviewed = $reviewDAO->hasAlreadyReviewed($id, $userData->getId());
}

// Resgatar as reviews do filme
$movieReviews = $reviewDAO->getMoviesReview($id);



?>

<div id="main-container" class="container-fluid">
    <div class="row">
        <div class="offset-md-1 col-md-6 movie-container">
            <h1 class="page-title"><?= $movie->getTitle(); ?></h1>
            <p class="movie-details">
                <span>Duração: <?= $movie->getLength(); ?></span>
                <span class="pipe"></span>
                <span><?= $movie->getCategory(); ?></span>
                <span class="pipe"></span>
                <span><i class="fas fa-star"></i> <?= $movie->getRating($id) ? number_format($movie->getRating($id), 0, '.', ',') : "Filme ainda não avaliado"; ?></span>
            </p>
            <iframe src="<?= $movie->getTrailer(); ?>" width="560" height="315" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            <p><?= $movie->getDescription(); ?></p>
        </div>
        <div class="col-md-4">
            <div class="movie-image-container" style="background-image: url('<?= $BASE_URL ?>img/movies/<?= $movie->getImage(); ?>');"></div>
        </div>
        <div class="offset-md-1 cold col-md-10" id="reviews-container">
            <h3 id="reviews-title">Avaliações:</h3>
            <!-- Exibe review para o user ou não -->

            <?php if (!empty($userData) && !$userOwnsMovie && !$alreadyReviewed) : ?>
                <div class="col-md-12" id="review-form-container">
                    <h4>Envie sua avaliação:</h4>
                    <p class="page-description">Preencha o formulário com a nota e comentário sobre o filme</p>
                    <form action="<?= $BASE_URL ?>review_process.php" method="POST" id="review-form">
                        <input type="hidden" name="type" value="create">
                        <input type="hidden" name="movies_id" value="<?= $movie->getId(); ?>">

                        <div class="form-group">
                            <label for="rating">Nota do filme:</label>
                            <select name="rating" id="rating" class="form-control">
                                <option value="">Selecione</option>
                                <option value="10">10</option>
                                <option value="9">9</option>
                                <option value="8">8</option>
                                <option value="7">7</option>
                                <option value="6">6</option>
                                <option value="5">5</option>
                                <option value="4">4</option>
                                <option value="3">3</option>
                                <option value="2">2</option>
                                <option value="1">1</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="review">Seu comentário:</label>
                            <textarea name="review" id="review" rows="3" class="form-control" placeholder="O que você achou do filme?"></textarea>
                            <input type="submit" value="Enviar comentário" class="btn card-btn">
                        </div>
                    </form>
                </div>

            <?php endif; ?>
            <?php
            if (!empty($movieReviews)) {
                foreach ($movieReviews as $review) {
                    require("templates/user_review.php");
                }
            }
            ?>
        </div>
    </div>
</div>

<?php
include_once("./templates/footer.php");
?>
<?php
require_once("./templates/header.php");

require_once("models/User.php");
require_once("dao/UserDAO.php");
require_once("dao/MovieDAO.php");

$user = new User();
$userDao = new UserDAO($conn, $BASE_URL);
$userData = $userDao->verifyToken(true);

$movieDao = new MovieDAO($conn, $BASE_URL);



$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_SPECIAL_CHARS);

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

?>
<div id="main-container" class="container-fluid">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-6 offset-md-1">
                <h1><?= $movie->getTitle(); ?></h1>
                <div class="page-description">Altere os dados do filme no formulário abaixo:</div>
                <form id="edit-movie-form" action="<?= $BASE_URL ?>movie_process.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="type" value="update">
                    <input type="hidden" name="id" value="<?= $movie->getId(); ?>">
                    <div class="form-group">
                        <label for="title">Título:</label>
                        <input type="text" class="form-control" name="title" id="title" placeholder="Digite o título do seu filme" value="<?= $movie->getTitle() ?? ""; ?>">
                    </div>
                    <div class="form-group">
                        <label for="image">imagem:</label>
                        <input type="file" class="form-control-file" name="image" id="image">
                    </div>
                    <div class="form-group">
                        <label for="length">Duração:</label>
                        <input type="text" class="form-control" name="length" id="length" placeholder="Digite a duração do seu filme" value="<?= $movie->getLength() ?? ""; ?>">
                    </div>
                    <div class="form-group">
                        <label for="category">Categoria:</label>
                        <select name="category" id="category" class="form-control">
                            <option value="">Selecione</option>
                            <option value="Ação" <?= $movie->getCategory() === "Ação" ? "selected" : "";  ?>>Ação</option>
                            <option value="Drama" <?= $movie->getCategory() === "Drama" ? "selected" : "";  ?>>Drama</option>
                            <option value="Comédia" <?= $movie->getCategory() === "Comédia" ? "selected" : "";  ?>>Comédia</option>
                            <option value="Fantasia / Ficção" <?= $movie->getCategory() === "Fantasia / Ficção" ? "selected" : "";  ?>>Fantasia / Ficção</option>
                            <option value="Romance" <?= $movie->getCategory() === "Romance" ? "selected" : "";  ?>>Romance</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="trailer">Trailer:</label>
                        <input type="url" class="form-control" name="trailer" id="trailer" placeholder="Insira o link do trailer" value="<?= $movie->getTrailer() ?? ""; ?>">
                    </div>
                    <div class="form-group">
                        <label for="description">Descrição:</label>
                        <textarea class="form-control" name="description" id="description" rows="5" placeholder="Descreva o filme"><?= $movie->getDescription() ?? ""; ?></textarea>
                    </div>
                    <input type="submit" class="btn card-btn" value="Editar filme">
                </form>
            </div>

            <div class="col-md-3">
                <div class="movie-image-container" style="background-image: url('<?= $BASE_URL ?>img/movies/<?= $movie->getImage() ?>');"></div>
            </div>
        </div>
    </div>
</div>

<?php
include_once("./templates/footer.php");
?>
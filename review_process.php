<?php
require_once("./models/Movie.php");
require_once("./models/Review.php");
require_once("./dao/MovieDAO.php");
require_once("./dao/ReviewDAO.php");
require_once("dao/UserDAO.php");
require_once("globals.php");
require_once("db.php");
require_once("./models/Message.php");


$message = new Message($BASE_URL);
$userDao = new UserDAO($conn, $BASE_URL);
$movieDao = new MovieDAO($conn, $BASE_URL);
$reviewDao = new ReviewDAO($conn, $BASE_URL);

$userData = $userDao->verifyToken(true);
$type = filter_input(INPUT_POST, 'type');


if ($type === "create") {
    $rating = filter_input(INPUT_POST, 'rating');
    $review = filter_input(INPUT_POST, 'review');
    $movies_id = filter_input(INPUT_POST, "movies_id");


    $reviewObject = new Review();

    $movie = $movieDao->findById($movies_id);

    if ($movie) {

        if (!empty($rating) && !empty($review) && !empty($movies_id)) {

            $reviewObject->setRating($rating);
            $reviewObject->setReview($review);
            $reviewObject->setMovies_id($movies_id);
            $reviewObject->setUsers_id($userData->getId());

            $reviewDao->create($reviewObject);
        } else {
            $message->setMessage("Você precisa inserir a nota e o comentário!", "error", "back");
        }
    } else {
        $message->setMessage("Informações inválidas", "error", "index.php");
    }
} else {
    $message->setMessage("Informações inválidas", "error", "index.php");
}

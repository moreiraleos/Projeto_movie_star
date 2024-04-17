<?php
require_once("./models/Movie.php");
require_once("./dao/MovieDAO.php");
require_once("dao/UserDAO.php");
require_once("globals.php");
require_once("db.php");
require_once("./models/Message.php");

$message = new Message($BASE_URL);
$userDao = new UserDAO($conn, $BASE_URL);
$movieDao = new MovieDAO($conn, $BASE_URL);

// Resgata o tipo do formulário
$type = filter_input(INPUT_POST, 'type');
$userData = $userDao->verifyToken(true);

if ($type === "create") {
    $title = filter_input(INPUT_POST, 'title');
    $description = filter_input(INPUT_POST, 'description');
    $trailer = filter_input(INPUT_POST, 'trailer');
    $category = filter_input(INPUT_POST, 'category');
    $length = filter_input(INPUT_POST, 'length');

    $movie = new Movie();

    if (!empty($title) && !empty($description) && !empty($category)) {
        $movie->setTitle($title);
        $movie->setDescription($description);
        $movie->setTrailer($trailer);
        $movie->setCategory($category);
        $movie->setLength($length);
        $movie->setUsers_id($userData->getId());
        if (isset($_FILES['image']) && !empty($_FILES['image']['tmp_name'])) {

            $image = $_FILES['image'];
            $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
            $jpgArray  = ["image/jpg", "image/jpeg"];

            if (in_array($image['type'], $imageTypes)) {
                if (in_array($image['type'], $jpgArray)) {
                    $imageFile =  imagecreatefromjpeg($image["tmp_name"]);
                } else {
                    $imageFile = imagecreatefrompng($image["tmp_name"]);
                }

                $imageName = $movie->imageGenerateName();
                imagejpeg($imageFile, "./img/movies/" . $imageName, 100);
                $movie->setImage($imageName);
            } else {
                $message->setMessage("Tipo inválido de imagem, insira png ou jpg!", "error", "back");
            }
        }


        $movieDao->create($movie);
    } else {
        $message->setMessage("Você precisa adicionar pelo menos: título, descrição e categoria!", "error", "back");
    }
} else if ($type === "delete") {
    //Recebe dados do form
    $id = filter_input(INPUT_POST, "id", FILTER_SANITIZE_SPECIAL_CHARS);
    $movie = $movieDao->findById($id);
    if ($movie) {
        if ($movie->getUsers_id() === $userData->getId()) {
            if ($movieDao->destroy($movie->getId())) {
                $message->setMessage("Filme removido com sucesso!", "success", "dashboard.php");
            } else {
                $message->setMessage("Erro ao remover o filme!", "error", "dashboard.php");
            }
        } else {
            $message->setMessage("Informações inválidas!", "error");
        }
    } else {
        $message->setMessage("Informações inválidas!", "error");
    }
} else if ($type === "update") {
    $id = filter_input(INPUT_POST, 'id');
    $title = filter_input(INPUT_POST, 'title');
    $description = filter_input(INPUT_POST, 'description');
    $trailer = filter_input(INPUT_POST, 'trailer');
    $category = filter_input(INPUT_POST, 'category');
    $length = filter_input(INPUT_POST, 'length');

    $movie = $movieDao->findById($id);

    if (!$movie) {
        $message->setMessage("Informações inválidas", "error");
    }
    if ($movie->getUsers_id() === $userData->getId()) {
        if (!empty($title) && !empty($description) && !empty($category)) {
            if ($movie->getUsers_id() === $userData->getId()) {
                $movie->setTitle($title);
                $movie->setLength($length);
                $movie->setCategory($category);
                $movie->setTrailer($trailer);
                $movie->setDescription($description);

                if (isset($_FILES['image']) && !empty($_FILES['image']['tmp_name'])) {

                    $image = $_FILES['image'];
                    $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
                    $jpgArray  = ["image/jpg", "image/jpeg"];

                    if (in_array($image['type'], $imageTypes)) {
                        if (in_array($image['type'], $jpgArray)) {
                            $imageFile =  imagecreatefromjpeg($image["tmp_name"]);
                        } else {
                            $imageFile = imagecreatefrompng($image["tmp_name"]);
                        }

                        $imageName = $movie->imageGenerateName();
                        imagejpeg($imageFile, "./img/movies/" . $imageName, 100);
                        $movie->setImage($imageName);
                    } else {
                        $message->setMessage("Tipo inválido de imagem, insira png ou jpg!", "error", "back");
                    }
                }

                $movieDao->update($movie);
            }
        } else {
            $message->setMessage("Você precisa adicionar pelo menos: título, descrição e categoria!", "error", "back");
        }
    }
} else {
    $message->setMessage("Informações inválidas!", "error");
}

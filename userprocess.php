<?php

require_once("./models/User.php");
require_once("./dao/UserDAO.php");
require_once("globals.php");
require_once("db.php");
require_once("./models/Message.php");

$message = new Message($BASE_URL);
$userDAO = new UserDAO($conn, $BASE_URL);


// Resgata o tipo do formulário
$type = filter_input(INPUT_POST, 'type');


if ($type === 'update') {

    $userData = $userDAO->verifyToken();

    $firstName = filter_input(INPUT_POST, 'name');
    $lastName = filter_input(INPUT_POST, 'lastname');
    $bio = filter_input(INPUT_POST, 'bio');
    $email = filter_input(INPUT_POST, 'email');

    $userData->setName($firstName);
    $userData->setLastName($lastName);
    $userData->setEmail($email);
    $userData->setBio($bio);

    if (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {
        $image = $_FILES["image"];
        $extensaoFile = $image["type"];
        $extensoes_permitidas = [
            "image/png",
            "image/jpeg",
            "image/jpg"
        ];
        $jpgArray = [
            "image/jpeg",
            "image/jpg"
        ];

        if (!in_array($extensaoFile, $extensoes_permitidas)) {
            $message->setMessage("Tipo inválido de imagem, insira png ou jpg!", "error", "back");
        }

        if (in_array($extensaoFile, $jpgArray)) {
            $imageFile = imagecreatefromjpeg($image["tmp_name"]);
        } else {
            $imageFile = imagecreatefrompng($image["tmp_name"]);
        }

        $imageName = $userData->imageGenerateName();
        imagejpeg($imageFile, "./img/users/" . $imageName, 100);

        $userData->setImage($imageName);
    }



    $userDAO->update($userData, true);
} else if ($type === 'changepassword') {
    $userData = $userDAO->verifyToken();
    $password = filter_input(INPUT_POST, 'password');
    $confirmpassword = filter_input(INPUT_POST, 'confirmpassword');

    $id = filter_input(INPUT_POST, 'id');

    if ($password !== $confirmpassword) {
        $message->setMessage("As senhas precisam ser iguais!", "error", "back");
    }

    // $newPassword = password_hash($password, PASSWORD_DEFAULT);
    $newPassword = $userData->generatePassword($password);
    $userData->setPassword($newPassword);
    if ($userDAO->changePassword($userData)) {
        $message->setMessage("Senha alterada com sucesso!", "success", "back");
    } else {
        $message->setMessage("Senha não foi alterada!", "error", "back");
    }
} else {
    $message->setMessage("Informações inválidas!", "error");
}

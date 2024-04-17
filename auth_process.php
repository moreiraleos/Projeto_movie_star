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


// Verifica o tipo do formulário
if ($type === 'register') {
    $firstName = filter_input(INPUT_POST, 'name');
    $lastName = filter_input(INPUT_POST, 'lastname');
    $password = filter_input(INPUT_POST, 'password');
    $confirmpassword = filter_input(INPUT_POST, 'confirmpassword');
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

    if (!$email) {
        $message->setMessage("O e-mail informado não é válido", "error", "back");
    }

    // VERIFICACAO DE DADOS
    if ($firstName && $lastName && $email && $password) {
        if ($password === $confirmpassword) {
            if (!$userDAO->findByEmail($email)) {
                $user = new User();
                //Criação de token e senha
                $userToken = $user->generatetoken();
                $finalPass = $user->generatePassword($password);
                $user->setName($firstName);
                $user->setLastName($lastName);
                $user->setEmail($email);
                $user->setPassword($finalPass);
                $user->setToken($userToken);
                $auth = true;
                $userDAO->create($user, $auth);
            } else {
                $message->setMessage("E-mail já cadastrado!", "error", "back");
            }
        } else {
            // Enviar mensagem erro - senhas não são iguais
            $message->setMessage("As senhas não são iguais!", "error", "back");
        }
    } else {
        $message->setMessage("Por favor, preencha todos os campos.", "error", "back");
    }
} else if ($type === 'login') {

    $email = filter_input(INPUT_POST, 'email');
    $password = filter_input(INPUT_POST, 'password');
    // AUTENTICA USUÁRIO
    if ($userDAO->autenticateUser($email, $password)) {
        $message->setMessage("Seja bem-vindo!", "success", "editprofile.php");
    } else {
        $message->setMessage("Email ou senha  incorretos!", "error", "back");
    }
} else {
    $message->setMessage("Informações inválidas!", "error");
}

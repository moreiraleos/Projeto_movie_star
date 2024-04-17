<?php
require_once("./templates/header.php");
require_once("dao/UserDAO.php");
require_once("models/User.php");

$userDao = new UserDAO($conn, $BASE_URL);
$userData = $userDao->verifyToken(true);
$user = new User();

if ($userData->getImage() == "") {
    $userData->setImage("user.png");
}
?>

<div id="main-container" class="container-fluid edit-profile-page">
    <div class="col-md-12">
        <form method="post" action="<?= $BASE_URL; ?>userprocess.php" enctype="multipart/form-data">
            <input type="hidden" name="type" value="update">
            <div class="row">
                <div class="col-md-4">
                    <h1><?= $userData->getFullName(); ?></h1>
                    <p class="page-description">
                        Altere seus dados no formulário abaixo:
                    </p>
                    <div class="form-group">
                        <label for="name">Nome:</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Digite o seu nome" value="<?= $userData->getName(); ?>">
                    </div>
                    <div class="form-group">
                        <label for="lastname">Sobrenome:</label>
                        <input type="text" name="lastname" id="lastname" class="form-control" placeholder="Digite o seu sobrenome" value="<?= $userData->getLastName(); ?>">
                    </div>
                    <div class="form-group">
                        <label for="email">E-mail:</label>
                        <input type="email" name="email" id="email" class="form-control disabled" placeholder="Digite o seu email" value="<?= $userData->getEmail(); ?>" readonly>
                    </div>
                    <input type="submit" value="Alterar" class="btn card-btn">
                </div>
                <div class="col-md-4">
                    <div id="profile-image-container" style="background-image: url('<?= "{$BASE_URL}img/users/{$userData->getImage()}" ?>');">
                    </div>
                    <div class="form-group">
                        <label for="image">Foto:</label>
                        <input type="file" name="image" id="image" class="form-control-file">
                    </div>
                    <div class="form-group">
                        <label for="bio">Sobre você:</label>
                        <textarea class="form-control" name="bio" id="bio" rows="5" placeholder="Conte quem você é, o que faz e onde trabalha..."><?= $userData->getBio(); ?></textarea>
                    </div>
                </div>
            </div>
        </form>
        <div class="row" id="change-password-container">
            <div class="col-md-4">
                <h2>Alterar a senha:</h2>
                <p class="page-description">Digite a nova senha e confirme para alterar sua senha:</p>
                <form method="post" action="<?= $BASE_URL; ?>userprocess.php">
                    <input type="hidden" name="type" value="changepassword">
                    <div class="form-group">
                        <label for="password">Senha:</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Digite a sua nova senha:" required>
                    </div>
                    <div class="form-group">
                        <label for="confirmpassword">Confirmação de senha:</label>
                        <input type="password" name="confirmpassword" id="confirmpassword" class="form-control" placeholder="Confirme a sua nova senha" required>
                    </div>
                    <input type="submit" value="Alterar Senha" class="btn card-btn">
                </form>
            </div>
        </div>
    </div>
</div>
<?php
include_once("./templates/footer.php");
?>
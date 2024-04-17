<?php
require_once "UserDAOInterface.php";
require_once "models/Message.php";
require_once "models/User.php";
require_once "db.php";
class UserDAO implements UserDAOInterface
{
    private $conn;
    private $url;
    private $message;

    public function __construct(PDO $conn, $url)
    {
        $this->conn = $conn;
        $this->url = $url;
        $this->message = new Message($url);
    }
    /**
     * @param array $data
     * 
     * @return void
     */
    public function buildUser($data)
    {
        $user = new User();
        $user->setId($data['id']);
        $user->setName($data['name']);
        $user->setLastName($data['lastname']);
        $user->setEmail($data['email']);
        $user->setPassword($data['password']);
        $user->setImage($data['image']);
        $user->setBio($data['bio']);
        $user->setToken($data['token']);
        return $user;
    }
    public function create(User $user, $authUser = false)
    {
        $name = $user->getName();
        $lastname = $user->getLastName();
        $email = $user->getEmail();
        $password = $user->getPassword();
        $token = $user->getToken();
        $sql = "INSERT INTO users(name, lastname, email, password, token)
                VALUES(:name, :lastname, :email, :password, :token)";
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam("name", $name);
            $stmt->bindParam("lastname", $lastname);
            $stmt->bindParam("email", $email);
            $stmt->bindParam("password", $password);
            $stmt->bindParam("token", $token);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                if ($authUser) {
                    $this->setTokenToSession($user->getToken());
                } else {
                }
            } else {
                throw new Exception("Erro ao cadastrar usuário!");
            }
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }
    }
    public function update(User $user, $redirect = true)
    {

        if (!$user) throw new PDOException("Erro ao atualizar usuário!");
        $id = $user->getId();
        $name = $user->getName();
        $lastname = $user->getLastName();
        $email = $user->getEmail();
        $image = $user->getImage();
        $bio = $user->getBio();
        $token = $user->getToken();
        $sql = "UPDATE users SET 
        name = :name, 
        lastname = :lastname, 
        email = :email,
        image = :image,
        bio = :bio,
        token = :token 
        WHERE id = :id";
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam("id", $id);
            $stmt->bindParam("name", $name);
            $stmt->bindParam("lastname", $lastname);
            $stmt->bindParam("email", $email);
            $stmt->bindParam("bio", $bio);
            $stmt->bindParam("image", $image);
            $stmt->bindParam("token", $token);
            $stmt->execute();
            if ($redirect) {
                $this->message->setMessage("Dados atualizados com sucesso!", "success", "editprofile.php");
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    public function findByToken($token)
    {
        if (empty($token)) return false;

        $sql = "SELECT * FROM users WHERE token = :token";
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam("token", $token);
            $stmt->execute();
            if ($stmt->rowCount()) {
                $data = $stmt->fetch();
                $user = $this->buildUser($data);
                return $user;
            }
            return false;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function destroyToken()
    {
        // Remove o token da sessão
        $_SESSION['token'] = "";

        // redirecionar e apresentar a mensagem de sucesso
        $this->message->setMessage("Você fez o logout com sucesso!", "success", "index.php");
    }
    public function verifyToken($protected = false)
    {
        if (!empty($_SESSION["token"])) {
            // Pega o token da session
            $token = $_SESSION['token'];
            $user = $this->findByToken($token);
            if ($user) {
                return $user;
            } else if ($protected) {
                $this->message->setMessage("Faça a autenticação para acessar essa página", "error", "index.php");
            }
        } else if ($protected) {
            $this->message->setMessage("Faça a autenticação para acessar essa página", "error", "index.php");
        }
    }
    public function setTokenToSession($token, $redirect = true)
    {
        $_SESSION['token'] = $token;
        if ($redirect) {
            $this->message->setMessage("Seja bem-vindo!", "success", "editprofile.php");
        }
    }
    public function autenticateUser($email, $password)
    {

        if (empty($email) || empty($password)) return false;
        $user = $this->findByEmail($email);

        if ($user) {

            $hash = $user->getPassword();
            if (password_verify($password, $hash)) {
                // Gerar token e inserir na sessão
                $token = $user->generatetoken();
                $this->setTokenToSession($token, false);
                // Atualizar token no usuário
                $user->setToken($token);
                $this->update($user, false);
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    public function findByEmail($email)
    {
        if (empty($email)) return false;

        $sql = "SELECT * FROM users WHERE email = :email";
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam("email", $email);
            $stmt->execute();
            if ($stmt->rowCount()) {
                $data = $stmt->fetch();
                $user = $this->buildUser($data);
                return $user;
            }
            return false;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    public function findById($id)
    {
        if (empty($id)) return false;

        $sql = "SELECT * FROM users WHERE id = :id";
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam("id", $id);
            $stmt->execute();
            if ($stmt->rowCount()) {
                $data = $stmt->fetch();
                $user = $this->buildUser($data);
                return $user;
            }
            return false;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    public function changePassword(User $user)
    {
        if (!$user) throw new PDOException("Erro ao atualizar usuário!");

        $id = $user->getId();
        $password = $user->getPassword();

        $sql = "UPDATE users SET password = :password WHERE id = :id";
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam("id", $id);
            $stmt->bindParam("password", $password);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return true;
            }
            return false;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}

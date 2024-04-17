<?php
require_once "MovieDAOinterface.php";
require_once "models/Message.php";
require_once "models/Movie.php";
require_once "db.php";
require_once "ReviewDAO.php";

class MovieDAO implements MovieDAOInterface
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
    public function buildMovie($data)
    {
        $movie = new Movie();
        $movie->setId($data['id']);
        $movie->setTitle($data['title']);
        $movie->setDescription($data['description']);
        $movie->setImage($data['image']);
        $movie->setTrailer($data['trailer']);
        $movie->setLength($data['length']);
        $movie->setUsers_id($data['users_id']);
        $movie->setCategory($data['category']);

        $reviewDao = new ReviewDAO($this->conn, $this->url);
        $rating = $reviewDao->getRatings($movie->getId());

        $movie->setRating($rating);

        return $movie;
    }
    public function findAll()
    {
    }
    public function getLastestMovies()
    {
        $movies = [];
        $sql = "SELECT * FROM movies ORDER BY id DESC";
        $this->conn->query($sql);
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $moviesArr = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($moviesArr as $movie) {
                $movies[] = $this->buildMovie($movie);
            }
        }

        return $movies;
    }
    public function getMoviesByCategory($category)
    {
        $movies = [];
        $sql = "SELECT * FROM movies WHERE category = :category ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam("category", $category);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $moviesArr = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($moviesArr as $movie) {
                $movies[] = $this->buildMovie($movie);
            }
        }
        return $movies;
    }
    public function getMoviesByUserId($id)
    {
        $movies = [];
        $sql = "SELECT * FROM movies WHERE users_id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $moviesArr = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($moviesArr as $movie) {
                $movies[] = $this->buildMovie($movie);
            }
        }
        return $movies;
    }
    public function findById($id)
    {
        if (empty($id)) return false;
        $movie = [];
        $sql = "SELECT * FROM movies WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        if ($stmt->rowCount() >  0) {
            $movie = $this->buildMovie($stmt->fetch(PDO::FETCH_ASSOC));
            return $movie;
        }
        return false;
    }
    public function findByTitle($title)
    {

        if (empty($title)) return false;
        $sql = "SELECT * FROM movies WHERE title like :title";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue("title", '%' . $title . '%');
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($movies as $movie) {
                $moviesArr[] = $this->buildMovie($movie);
            }
            return $moviesArr;
        }
        return false;
    }
    public function create(Movie $movie)
    {
        if (!$movie) return;
        $title = $movie->getTitle();
        $description = $movie->getDescription();
        $image = $movie->getImage();
        $trailer = $movie->getTrailer();
        $category = $movie->getCategory();
        $length = $movie->getLength();
        $users_id = $movie->getUsers_id();

        $sql = "INSERT INTO movies(
           title, description, image, trailer, category , length, users_id
        ) VALUES
        (:title, :description, :image, :trailer, :category, :length, :users_id)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam("title", $title);
        $stmt->bindParam("description", $description);
        $stmt->bindParam("image", $image);
        $stmt->bindParam("trailer", $trailer);
        $stmt->bindParam("category", $category);
        $stmt->bindParam("length", $length);
        $stmt->bindParam("users_id", $users_id);
        $stmt->execute();

        $this->message->setMessage("Filme adicionado com sucesso!", "success");
    }
    public function update(Movie $movie)
    {

        if (!$movie) {
            $this->message->setMessage("", "error");
        }

        $id = $movie->getId();
        $title = $movie->getTitle();
        $description = $movie->getDescription();
        $image = $movie->getImage();
        $trailer = $movie->getTrailer();
        $category = $movie->getCategory();
        $length = $movie->getLength();

        $sql = "UPDATE movies SET
            title = :title, 
            description = :description, 
            image = :image,
            trailer = :trailer,
            category = :category,
            length = :length 
            WHERE id = :id";

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam("title", $title);
            $stmt->bindParam("description", $description);
            $stmt->bindParam("image", $image);
            $stmt->bindParam("trailer", $trailer);
            $stmt->bindParam("category", $category);
            $stmt->bindParam("length", $length);
            $stmt->bindParam("id", $id);

            $stmt->execute();
            $this->message->setMessage("Filme atualizado com sucesso!", "success", "dashboard.php");
        } catch (PDOException $e) {
            $this->message->setMessage($e->getMessage(), "error", "dashboard.php");
        }
    }
    public function destroy($id)
    {
        $sql = "DELETE FROM movies WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return true;
        }
        return false;
    }
}

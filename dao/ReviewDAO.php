<?php

require_once "ReviewDAOInterface.php";
require_once "models/Message.php";
require_once "models/Review.php";
require_once "dao/UserDAO.php";
require_once "models/Movie.php";
require_once "models/User.php";
require_once "db.php";

class ReviewDAO implements ReviewDAOInterface
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
     * @param mixed $data
     * @return mixed
     */
    public function buildReview($data)
    {
        $review = new Review();

        $review->setId($data['id']);
        $review->setRating($data['rating']);
        $review->setReview($data['review']);
        $review->setUsers_id($data['users_id']);
        $review->setMovies_id($data['movies_id']);

        return $review;
    }

    /**
     *
     * @param Review $review
     * @return mixed
     */
    public function create(Review $review)
    {

        $rating = $review->getRating();
        $reviewMovie = $review->getReview();
        $movies_id = $review->getMovies_id();
        $userReview = $review->getUsers_id();


        $sql = "INSERT INTO reviews(rating, review, users_id, movies_id)
                VALUES(:rating, :review, :userReview, :movies_id)";
        try {
            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam("rating", $rating);
            $stmt->bindParam("review", $reviewMovie);
            $stmt->bindParam("movies_id", $movies_id);
            $stmt->bindParam("userReview", $userReview);

            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $this->message->setMessage("Crítica adicionada com sucesso!", "success", "index.php");
            } else {
                throw new Exception("Erro ao cadastrar review!");
            }
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }
    }

    /**
     *
     * @param mixed $id
     * @return mixed
     */
    public function getMoviesReview($id)
    {
        if (!$id) return false;
        $sql = "SELECT * FROM reviews WHERE movies_id = :movies_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam("movies_id", $id);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $userDao = new UserDAO($this->conn, $this->url);
            $reviewData = [];
            foreach ($reviews as $review) {
                $reviewObject = $this->buildReview($review);
                $user =  $userDao->findById($reviewObject->getUsers_id());

                $reviewObject->user = $user;
                $reviewData[] = $reviewObject;
            }
            return $reviewData;
        }
        return false;
    }

    /**
     *
     * @param mixed $id
     * @param mixed $userId
     * @return mixed
     */
    public function hasAlreadyReviewed($id, $userId)
    {
        $sql = "SELECT * FROM reviews WHERE movies_id = :movies_id AND users_id = :users_id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam("movies_id", $id);
        $stmt->bindParam("users_id", $userId);

        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return true;
        }

        return false;
    }

    /**
     *
     * @param mixed $id
     * @return mixed
     */
    public function getRatings($id)
    {
        $sql = "SELECT avg(rating) FROM reviews WHERE movies_id = :movies_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam("movies_id", $id);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_COLUMN);
        } else {
            return null;
        }
    }
}

<?php

class Review
{

    private $id;
    private $rating;
    private $review;
    private $users_id;
    private $movies_id;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id 
     * @return self
     */
    public function setId($id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRating()
    {
        return $this->rating;
    }


    public function setRating($rating)
    {
        $this->rating = $rating;
    }

    /**
     * @return mixed
     */
    public function getReview()
    {
        return $this->review;
    }

    /**
     * @param mixed $review 
     * @return self
     */
    public function setReview($review): self
    {
        $this->review = $review;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsers_id()
    {
        return $this->users_id;
    }

    /**
     * @param mixed $users_id 
     * @return self
     */
    public function setUsers_id($users_id): self
    {
        $this->users_id = $users_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMovies_id()
    {
        return $this->movies_id;
    }

    /**
     * @param mixed $movies_id 
     * @return self
     */
    public function setMovies_id($movies_id): self
    {
        $this->movies_id = $movies_id;
        return $this;
    }
}

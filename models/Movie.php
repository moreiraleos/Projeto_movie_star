<?php

class Movie
{
    private $id;
    private $title;
    private $description;
    private $image;
    private $trailer;
    private $category;
    private $length;
    private $users_id;

    private $rating;
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
    public function getTitle()
    {
        return $this->title;
    }
    /**
     * @param mixed $title 
     * @return self
     */
    public function setTitle($title): self
    {
        $this->title = $title;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }
    /**
     * @param mixed $description 
     * @return self
     */
    public function setDescription($description): self
    {
        $this->description = $description;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }
    /**
     * @return mixed
     */
    public function getTrailer()
    {
        return $this->trailer;
    }
    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }
    /**
     * @param mixed $category 
     * @return self
     */
    public function setCategory($category): self
    {
        $this->category = $category;
        return $this;
    }
    /**
     * @return mixed
     */
    public function getLength()
    {
        return $this->length;
    }
    /**
     * @param mixed $length 
     * @return self
     */
    public function setLength($length): self
    {
        $this->length = $length;
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

    public function imageGenerateName()
    {
        return bin2hex(random_bytes(60)) . ".jpg";
    }

    /**
     * @param mixed $image 
     * @return self
     */
    public function setImage($image): self
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @param mixed $trailer 
     * @return self
     */
    public function setTrailer($trailer): self
    {
        $this->trailer = $trailer;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param mixed $rating 
     * @return self
     */
    public function setRating($rating): self
    {
        $this->rating = $rating;
        return $this;
    }
}

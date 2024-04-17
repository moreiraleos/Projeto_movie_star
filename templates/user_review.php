<?php
if ($review->user->getImage() == "") {
    $review->user->setImage("movie_cover.jpg");
}
?>

<div class="col md-12 review">
    <div class="row">
        <div class="col-md-1">
            <div class="profile-image-container review-image" style="background-image: url('<?= $BASE_URL ?>img/users/<?= $review->user->getImage(); ?>');"></div>
        </div>
        <div class="col-md-9 author-details-container">
            <h4 class="author-name">
                <a href="<?= $BASE_URL ?>profile.php?id=<?= $review->getUsers_id(); ?>"><?= "{$review->user->getName()} {$review->user->getLastName()}" ?></a>
                <p class="rating"><i class="fas fa-star"></i> <?= $review->getRating(); ?></p>
            </h4>
        </div>
        <div class="col-md-12">
            <p class="comment-title">comentário:</p>
            <p><?= $review->getReview(); ?></p>
        </div>
    </div>
</div>
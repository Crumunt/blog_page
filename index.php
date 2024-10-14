<?php

include "db/action.php";
include "user/partials/header.php";

session_start();

$adminID = $_SESSION['adminID'] ?? NULL;

if($adminID != NULL) {
    header("location: admin/index.php");
    exit();
}

$carouselData = showRecords($conn, 'tbl_blogs', "id != 0 ORDER BY likes DESC LIMIT 3");

?>


<div id="hero-carousel" class="carousel slide shadow" data-bs-ride="carousel">
    <div class="carousel-indicators">
        <button type="button" data-bs-target="#hero-carousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#hero-carousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#hero-carousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
    </div>
    <div class="carousel-inner">
        <?php
        foreach ($carouselData as $carousel_item) {
        ?>
            <div class="carousel-item c-item" data-bs-interval="2500">
                <img src="assets/blog-assets/<?= $carousel_item[2] ?>" class="d-block w-100 carouselImage" alt="...">
                <div class="carousel-caption top-0 mt-4">
                    <h5 class="text-uppercase fs-3 mt-5"><?= $carousel_item[4] ?></h5>
                    <p class="display-4 fw-bolder text-capitalize"><?= $carousel_item[1] ?></p>
                    <a href="user/view-blog.php?blog_id=<?= $carousel_item[0] ?>" class="btn btn-primary px-4 py-2 fs-5 mt-5">Read More</a>
                </div>
            </div>
        <?php
        }
        ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#hero-carousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#hero-carousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>


<section>
    <h1 class="text-center text-uppercase mt-5 fw-bolder">Latest Articles</h1>
    <div class="container mt-5">
        <div class="row d-flex justify-content-center align-items-stretch">
            <?php
            $data = showRecords($conn, 'tbl_blogs');

            foreach (array_slice(array_reverse($data), 0, 3) as $latestArticle) {
            ?>
                <div class="col-lg-4 mb-4">
                    <div class="card shadow">
                        <img src="assets/blog-assets/<?= $latestArticle[2] ?>" alt="" class="card-img-top">
                        <div class="card-body">
                            <h5 class="card-title fw-bold text-truncate"><?= $latestArticle[1] ?></h5>
                            <div class="card-text card__preview mb-2"> <?= nl2br($latestArticle[3]) ?> </div>
                            <div class="button-group d-flex gap-2">
                                <a href="user/view-blog.php?blog_id=<?= $latestArticle[0] ?>" class="btn btn-outline-success btn-md">Read More</a>
                                <button class="btn btn-outline-danger d-flex align-items-center" id="likeButton" aria-label="<?= $latestArticle[0] ?>">
                                    <span class="material-symbols-outlined" id="heartIcon">favorite</span>
                                    <span class="like__label">Like</span>
                                </button>
                                <div class="count-group d-flex align-items-center gap-1">
                                    <p class="like__count fs-6" data-blog-id="<?= $latestArticle[0] ?>"><?= $latestArticle[6] ?></p>
                                    <span class="fs-6">likes</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
</section>

<section class="mt-5">
    <div class="row p-5 d-flex justify-content-between align-items-center text-white" id="about">
        <div class="col fs-5">
            <h5 class="text-center text-uppercase fs-1 fw-bolder mb-3">About</h5>
            <p class="text-justify lh-lg text-wrap">
                Welcome to The Owl's Lounge, a haven for the endlessly curious! We're your nightly companion, illuminating the vast landscape of knowledge with engaging blogs on a breathtaking array of subjects.
                Think of us as your wise old owl perched atop a branch, observing the world with keen eyes and a thirst for understanding. Here, you'll find yourself peering into every corner of the globe, from the bustling streets of Tokyo to the serene depths of the ocean floor.
                The Owl's Lounge isn't just a repository of knowledge; it's a vibrant community. We encourage you to interact, share your thoughts, and engage in stimulating discussions with fellow knowledge seekers. After all, learning is most rewarding when it's a shared experience.
                So, settle in, grab your favorite beverage, and prepare to embark on a journey of exploration with The Owl's Lounge. We'll be your guide, illuminating the way with insightful blogs that will keep you up late, perched on the edge of your seat, eager to discover more.
            </p>
        </div>
</section>

<section class="mt-5">
    <h1 class="text-center text-uppercase fw-bolder">Articles</h1>
    <div class="container py-3">
        <div class="row g-4 d-flex justify-content-center">
            <?php
            $data = showRecords($conn, 'tbl_blogs', "id != 0 ORDER BY RAND()");

            foreach (array_slice($data, 0, 6) as $article) {
            ?>
                <div class="col-lg-4 col-md-6 col-sm-10">
                    <div class="card shadow">
                        <img src="assets/blog-assets/<?= $article[2] ?>" alt="" class="card-img-top">
                        <div class="card-body">
                            <h5 class="card-title text-truncate fw-bold"><?= $article[1] ?></h5>
                            <div class="card-text card__preview mb-2"><?= nl2br($article[3]) ?></div>
                            <div class="button-group d-flex gap-2">
                                <a href="user/view-blog.php?blog_id=<?= $article[0] ?>" class="btn btn-outline-success btn-md">Read More</a>
                                <button class="btn btn-outline-danger d-flex align-items-center" id="likeButton" aria-label="<?= $article[0] ?>">
                                    <span class="material-symbols-outlined" id="heartIcon">favorite</span>
                                    <span class="like__label">Like</span>
                                </button>
                                <div class="count-group d-flex align-items-center gap-1">
                                    <p class="like__count fs-6" data-blog-id="<?= $article[0] ?>"><?= $article[6] ?></p>
                                    <span class="fs-6">likes</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
            <div class="button-wrapper d-flex justify-content-center">
                <a href="user/blogs.php">
                    <button class="shadow btn btn-outline-success border-none mx-auto mt-5 px-5 fs-2">View More</button>
                </a>
            </div>
        </div>
</section>

<?php include "user/partials/footer.php"; ?>
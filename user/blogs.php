<?php
require "../db/action.php";
include "partials/header.php";

session_start();

$adminID = $_SESSION['adminID'] ?? NULL;

if ($adminID != NULL) {
	header("location: ../admin/index.php");
	exit();
}

?>

<div class="container">
	<div class="row d-flex justify-content between mt-4">
		<div class="col-6">
			<form action="" method="post" class="d-flex gap-2 align-items-center">
				<label for="" class="form-label">Search</label>
				<input type="search" name="" id="search_blogs" class="form-control" placeholder="Search by title or category" onkeyup="searchBlogs(this.value)">
			</form>
		</div>
		<div class="col-6 d-flex align-items-center gap-2">
			<label for="" class="form-label">Filter</label>
			<select name="" id="filter_blogs" class="form-select" onchange="filterBlogs(this.value)">
				<option value="">Sort by</option>
				<option value="most_liked">Most Liked</option>
				<option value="least_liked">Least Liked</option>
				<option value="upload_date">Upload Date</option>
			</select>
		</div>
	</div>

	<section class="mt-5">
		<div class="container py-3">
			<div class="row g-4 d-flex justify-content-center" id="contentWrapper">
				<?php
				$data = showRecords($conn, 'tbl_blogs');

				foreach ($data as $article) {
					$date = strtotime($article[5]);
				?>
					<div class="col-lg-4 col-md-6 col-sm-10">
						<div class="card shadow">
							<img src="../assets/blog-assets/<?= $article[2] ?>" class="card-img-top viewThumbnail" alt="Article thumbnail" />
							<div class="card-body">
								<h5 class="card-title border-bottom text-truncate"><?= $article[1] ?></h5>
								<p class="card-text text-secondary"><?= $article[4] ?> &bullet; <?= date("M. d, Y", $date) ?></p>
								<div class="button-group d-flex gap-2">
									<a href="view-blog.php?blog_id=<?= $article[0] ?>" class="btn btn-outline-success btn-md">Read More</a>
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
			</div>
		</div>
	</section>
</div>

<?php include "partials/footer.php"; ?>
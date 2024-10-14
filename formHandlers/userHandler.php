<?php

require "../db/action.php";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['updateLike'])) {
        updateLike($conn);
    }
    if(isset($_POST['sendMessage'])) {
        uploadMessage($conn);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    filterBlogs($conn);
}

function updateLike($conn)
{

    $blog_id = $_POST['blog_id'];
    $like_count = $_POST['like_count'];

    if ($_POST['updateLike'] == 'add') {
        $like_count++;
    } else {
        $like_count--;
    }

    try {

        updateQuery($conn, ['likes' => $like_count], 'tbl_blogs', ["id" => $blog_id]);

        $data = showRecords($conn, 'tbl_blogs', "id = $blog_id");

        echo $data[0][6];
    } catch (Exception $e) {
        echo "ERROR: " . $e;
    }
}

function filterBlogs($conn)
{


    $filter_type = $_GET['filter_type'];

    $where_clause = "id != 0";

    switch ($filter_type) {
        case 'most_liked':
            $where_clause .= ' ORDER BY likes DESC';
            break;
        case 'least_liked':
            $where_clause .= ' ORDER BY likes';
            break;
        case 'upload_date':
            $where_clause .= ' ORDER BY upload_date DESC';
            break;
    }

    try {
        $data = showRecords($conn, 'tbl_blogs', $where_clause);
    } catch (Exception $e) {
        echo "ERROR: " . $e;
    }

    generateCard($conn, $data);
}

function generateCard($conn, $data)
{

    if ($_GET['keyword'] != '') {
        $data = showRecords($conn, 'tbl_blogs', "blog_title LIKE '%{$_GET['keyword']}%' OR category_name LIKE '%{$_GET['keyword']}%'");
    }

    if (count($data) > 0) {
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
                                <p class="like__count fs-6"><?= $article[6] ?></p>
                                <span class="fs-6">likes</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
<?php
        }
    }
    else {
        echo "<p class='text-center text-uppercase fw-bold fs-2'>no blogs found.</p>";
    }
}

function uploadMessage($conn) {

    $data = [];

    foreach($_POST as $name => $value) {
        if($name != 'sendMessage') {
            $data[$name] = str_replace("'", "\'", $value);
        }
    }

    try {
        $action = addQuery($conn, $data, 'tbl_messages');
    } catch (Exception $e) {
        echo "ERROR: " . $e;
    }

    echo "Concern has been sent.";

}
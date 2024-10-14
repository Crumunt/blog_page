    <?php
require "../db/action.php";
include "partials/header.php";

session_start();

$adminID = $_SESSION['adminID'] ?? NULL;

if ($adminID == NULL) {
    header("location: ../index.php");
    exit();
}

$data = showRecords($conn, 'tbl_messages');
?>

<main class="pb-3">

    <div class="container mb-5">
        <div class="row d-flex align-items-center rounded-2 gap-2 p-3 shadow" id="postHeader">
            <div class="col-lg-6 col-sm-10">
                <h1 class="text-uppercase text-white">Manage Messages</h1>
            </div>
            <div class="col-lg-5 col-sm-2 d-flex">
                <input type="search" name="" id="" placeholder="Search by id or concern" class="form-control" onkeyup="searchForm(this)" aria-label="messages">
                <button class="btn btn-primary">Search</button>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row g-4 d-flex gap-2 justify-content-center" id="contentWrapper" aria-label="Messages">
            <?php
            foreach ($data as $concern) {
                $date = strtotime($concern[3]);
            ?>
                <div class="card text-center col-lg-4 col-md-6 col-sm-10" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">Message #<?= $concern[0] ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted"><?= $concern[1] ?></h6>
                        <p class="card-text"><?= date("M. d, Y", $date) ?></p>
                        <button class="btn btn-success mb-1" onclick="loadContent(this)" data-bs-toggle="modal" data-bs-target="#staticBackdrop" value="<?= $concern[0] ?>" aria-label="messages">
                            View Message
                        </button>
                        <button class="btn btn-danger" value="<?= $concern[0] ?>" onclick="confirmMessage(this)" aria-label="messages" data-bs-toggle="modal" data-bs-target="#confirmDelete">Delete Message</button>
                    </div>
                </div>
            <?php
            } ?>
        </div>

        <!-- Modal -->
        <?php include "partials/modal.php"; ?>

    </div>
</main>
<?php
include "partials/header.php";

session_start();

$adminID = $_SESSION['adminID'] ?? NULL;

if ($adminID == NULL) {
    header("location: ../index.php");
    exit();
}

?>

<main>

    <div class="container">
        <div class="row d-flex justify-content-evenly align-items-center rounded-2 gap-2 p-3 shadow" id="postHeader">
            <div class="col-lg-7 col-md-11 col-sm-11 d-flex align-items-center gap-2">
                <h1 class="text-uppercase text-white d-inline">Manage Users</h1>
            </div>
            <div class="col-lg-4 col-md-11 col-sm-11 d-flex">
                <input type="search" name="" id="" placeholder="Search by name or id" class="form-control">
                <button class="btn btn-primary">Search</button>
            </div>
        </div>
    </div>

    <div class="container border shadow-sm">
        <div class="image-wrapper d-flex flex-column justify-content-center mx-auto my-4">
            <img src="../assets/user-regular.svg" class="align-self-center" alt="User Icon">
        </div>
        <p class="text-center h1">There are no users.</p>

    </div>

</main>
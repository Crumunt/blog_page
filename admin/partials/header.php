<?php
$activePage = basename($_SERVER["PHP_SELF"], ".php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Owl's lounge</title>

    <!-- CSS -->
    <link rel="stylesheet" href="../css/adminStyle.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../js/chart.js" defer></script>
    <script src="../js/admin.js" defer></script>

    <!-- CKEDITOR CDN -->
    <script src="https://cdn.ckeditor.com/ckeditor5/41.2.1/decoupled-document/ckeditor.js"></script>

    <?php include "../includes/font-bootstrap-link.php" ?>
</head>

<body>

    <header>
        <nav class="navbar">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="offcanvas offcanvas-start text-white" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                    <div class="offcanvas-header">
                        <img src="../assets/logo.png" alt="" class="logo-icon">
                        <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Hi Admin!</h5>
                        <button type="button" class="btn-close bg-light" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                            <li class="nav-item">
                                <a class="nav-link text-white fs-3 <?= ($activePage == 'index') ? "active" : "" ?>" href="index.php">Dashboard</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white fs-3 <?= ($activePage == 'manage-post') ? "active" : "" ?>" href="manage-post.php">Manage Posts</a>
                            </li>
                            <li class="nav-item">
                                <a href="manage-categories.php" class="nav-link text-white fs-3 <?= ($activePage == 'manage-categories') ? "active" : "" ?>" aria-current="page">Manage Categories</a>
                            </li>
                            <li class="nav-item">
                                <a href="manage-users.php" class="nav-link text-white fs-3 <?= ($activePage == 'manage-users') ? "active" : "" ?>" aria-current="page">Manage Users</a>
                            </li>
                            <li class="nav-item">
                                <a href="manage-messages.php" class="nav-link text-white fs-3 <?= ($activePage == 'manage-messages') ? "active" : "" ?>" aria-current="page">Messages</a>
                            </li>
                            <li class="nav-item mt-5">
                                <a href="../formHandlers/logout.php" class="w-100 btn btn-danger text-white fs-3" aria-current="page">Logout</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </header>
<?php

session_start();

require "../db/action.php";

if($_SERVER['REQUEST_METHOD'] == "POST") {

    if(isset($_POST['login'])) {
        verifyLogin($conn);
    }

}

function verifyLogin($conn) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $data = showRecords($conn, 'tbl_admin', "email = '$email'");

    $hashedPassword = $data[0][2];

    if(password_verify($password, $hashedPassword)) {
        $_SESSION['adminID'] = $data[0][0];
        $_SESSION['loggedIN'] = true;

        header("location: ../admin/index.php");
        exit();
    }

}
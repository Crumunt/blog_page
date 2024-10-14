<?php

session_start();

unset($_SESSION['adminID']);
unset($_SESSION['loggedIn']);

header("location: ../index.php");
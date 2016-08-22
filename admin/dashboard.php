<?php

include_once './db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location:index.php");
    exit();
}

$view = $_GET['view'];
if ($view == 'add') {
    loadview(array('add'));
} elseif ($view == 'logout') {
    logout();
}

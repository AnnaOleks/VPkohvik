<?php
session_start();
if (!isset($_SESSION['klient']) && !isset($_SESSION['admin'])) {
    header("Location: ?leht=login.php");
    exit();
}
if(isset($_POST['logout'])){
    session_destroy();
    header('Location: ?leht=avaleht.php');
    exit();
}
?>
<?php
session_start();
if (!isset($_SESSION['tuvastamine'])){
    header('Location:loginDT.php');
    exit();
}
if (isset($_POST['logout'])){
    session_destroy();
    //in the address bars opens login.php
    header('Location:login.php');
    exit();
}

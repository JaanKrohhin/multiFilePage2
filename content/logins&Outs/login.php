<?php
//login ja parool salvestatud andmebaasiga andmetega
session_start();
if (isset($_SESSION['tuvastamine'])){
    header('Location:databaseT.php');
    exit();
}
if (!empty($_POST['login']) && !empty($_POST['pass'])){
    $login=$_POST['login'];
    $pass=$_POST['pass'];
    if ($login=='admin' && $pass=='123'){
        $_SESSION['tuvastamine']='niilihtne';
        header('Location: databaseT.php');
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../../css/style.css">
    <title>Login</title>
</head>
<body>
<h1>Login Vorm</h1>
<form action="" method="post">
    Login:
    <input type="text" name="login" placeholder="kasutaja nimi"><br>
    Parool:
    <input type="password" name="pass">
    <br><input type="submit" value="Logi sisse">
</form>
</body>
</html>

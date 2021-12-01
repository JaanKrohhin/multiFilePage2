<?php
require("conf.php");
session_start();
if (!isset($_SESSION['tuvastamine'])){
    header('Location:loginDT2.php');
    exit();
}
global $connection;
if(!empty($_REQUEST['autorvorm'])){
    $order=$connection->prepare('INSERT INTO autor(eesnimi,perenimi, rahvus) VALUES(?,?, ?)');
    $order->bind_param('sss', $_REQUEST['eesnimi1'],$_REQUEST['perenimi1'], $_REQUEST['rahvus1']); // s - string
    $order->execute();
    //изменяет адресную строку
    //$_SERVER[PHP_SELF] - до имени файла
    header("Location: $_SERVER[PHP_SELF]");
}
//delete
if(isset($_REQUEST ['kustuta'])) {
    $order = $connection->prepare("DELETE FROM autor WHERE id=?");
    $order->bind_param("i", $_REQUEST['kustuta']);
    $order->execute();
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
    <title>Autor</title>
</head>
<body>
<h1>Autor tabel</h1>
<div>
    <p><?=$_SESSION["kasutaja"]?> on sisse logitud</p>
    <form action="logout2.php" method="post">
        <input type="submit" value="Logi välja" name="logout">
    </form>
</div>
<?php
$order=$connection->prepare("SELECT id,eesnimi,perenimi,rahvus FROM autor");
$order->bind_result($id,$eesnimi,$perenimi,$rahvus);
$order->execute();

echo "<table>";
echo "<tr>
<th>ID</th>
<th>Eesnimi</th>
<th>Perenimi</th>
<th>Rahvus</th>
<th>Tegevus</th>
</tr>";
while($order->fetch()){
    echo "<tr>";
    echo "<td>$id</td>";
    echo "<td>$eesnimi</td>";
    echo "<td>$perenimi</td>";
    echo "<td>$rahvus</td>";
    if ($_SESSION['onAdmin']==1){
        echo "<td><a href='$_SERVER[PHP_SELF]?kustuta=$id'>Kustuta</a>";
    }
    echo "</tr>";
}
echo "</table>";
?>
<div>
    <?php
    if ($_SESSION['onAdmin']==1){
        echo "<a href='$_SERVER[PHP_SELF]?lisa=jah'>Lisa...</a>";
        if (isset($_REQUEST['lisa'])){
            echo "<br><form action='' method='post'>
                    <input type='hidden' name='autorvorm' value='jah'><br>
                    Eesnimi
                    <input type='text' name='eesnimi1'><br>
                    Perenimi
                    <input type='text' name='perenimi1'><br>
                    Rahvus
                    <input type='text' name='rahvus1'>
                    <input type='submit' value='Lisa'>
                    </form>";
        }
    }
    ?>
</div>
</body>
</html>
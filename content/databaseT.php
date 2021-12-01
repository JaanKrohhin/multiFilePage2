<?php
require('conf.php');
session_start();
if (!isset($_SESSION['tuvastamine'])){
    header('Location:loginDT.php');
    exit();
}
global $connection;
//lisamine ISERT INTO
if(!empty($_REQUEST['puuvorm'])){
    $order=$connection->prepare('INSERT INTO puud(nimi, puulink) VALUES(?, ?)');
    $order->bind_param('ss', $_REQUEST['puunimi'], $_REQUEST['puulink']); // s - string
    $order->execute();
    //изменяет адресную строку
    //$_SERVER[PHP_SELF] - до имени файла
    header("Location: $_SERVER[PHP_SELF]");
}
//delete
if(isset($_REQUEST ['kustuta'])) {
    $order = $connection->prepare("DELETE FROM puud WHERE id=?");
    $order->bind_param("i", $_REQUEST['kustuta']);
    $order->execute();
}
//muutmine
if(isset($_REQUEST ['muuda'])) {
    $order=$connection->prepare("UPDATE puud SET nimi=?,puulink=? WHERE id=?");
    $order->bind_param("ssi", $_REQUEST['nimi'],$_REQUEST['pilt'],$_REQUEST['muuda']);//ssi - str str int
    $order->execute();
}
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="../css/style.css">
        <title>Puu Andmebaas</title>
    </head>
<body>
    <h1>Puu Andmebaas</h1>
<div>
    <p><?=$_SESSION["kasutaja"]?> on sisse logitud</p>
    <form action="logout.php" method="post">
        <input type="submit" value="Logi välja" name="logout">
    </form>
</div>
<div class="leftcolumn">
    <h2>Puud</h2>
    <?php
    //nüitamine puunimed
    global $connection;
    $order=$connection->prepare("SELECT id, nimi FROM puud");
    $order->bind_result($id,$nimi);
    $order->execute();
    echo "<ul>";
    while($order->fetch()){
        echo "<li><a href='$_SERVER[PHP_SELF]?id=$id'>".$nimi."</a></li>";
    }
    echo "</ul>";
    echo "<a href='$_SERVER[PHP_SELF]?lisa=jah'>Lisa...</a>";
    if (isset($_REQUEST['lisa'])){
    
        echo "<br><form action='' method='post'>
                <input type='hidden' name='puuvorm' value='jah'>
                <label for='puunimi'>Puu nimi</label>
                <input type='text' name='puunimi' id='puunimi'>
                <br><br>
                <label for='puulink'>Puu pilti link</label>
                <textarea name='puulink' id='puulink'></textarea>
                <input type='submit' value='Lisa'>
                </form>";
    }
    ?>
</div>

<div class="rightcolumn">   
    <h3>Puu pilt</h3>
    <?php
        //nüitamine puunimed
        global $connection;
        if(isset($_REQUEST['id'])){
            $order=$connection->prepare("SELECT id, nimi,puulink FROM puud WHERE id=?");
            $order->bind_param('i', $_REQUEST['id']);
            $order->bind_result($id,$nimi, $puulink);
            $order->execute();



            if($order-> fetch()){
                if(isset($_REQUEST['muutmine'])){
                    echo "<br><form action='$_SERVER[PHP_SELF]'>
                            <input type='hidden' name='muuda' value='$id'>
                            <h2>Puu Andmete Muutmine</h2>
                            Puunimi:
                            <input type='text' name='nimi' value='$nimi'><br>
                            Pilti link:
                            <textarea name='pilt'>$puulink</textarea>
                            
                            <input type='submit' value='Muuda'>
                            </form>";
                }
                echo "<strong>".$nimi."</strong><br>";
                echo "<img src='$puulink' alt='puulink' width=500px height=400px margin-right:1%>";
                if ($_SESSION['onAdmin']==1){
                    echo "<a href='$_SERVER[PHP_SELF]?kustuta=$id'>Kustuta</a>";
                    echo "<br><a href='$_SERVER[PHP_SELF]?id=$id&muutmine=jah'>Muuda</a>";
                }
            }else{
                echo "ERROR CODE: Existence";
            }
        }
        ?>
</div>
</body>
</html>
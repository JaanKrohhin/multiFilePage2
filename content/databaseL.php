<?php
require("conf.php");
function clearVarsExcept($username){
    return strtok(basename($_SERVER['REQUEST_URI']), "?")."?$username=".$_REQUEST[$username];
}
global $connection;
//функция, которая удаляет из адресной строки переменные
//"s"-string; $_REQUEST['loomanimi'] query in input textbox with name="loomanimi"
if (!empty($_REQUEST['loomanimi'])) {
    $order=$connection->prepare("INSERT INTO loomad(nimi,kirjeldus) VALUES(?, ?)");
    $order->bind_param("ss", $_REQUEST['loomanimi'],$_REQUEST['Kirjeldus']);
    $order->execute();
    //changes adress bar
    //$_SERVER[PHP_SELF] before file name
    // header("Location: $_SERVER[PHP_SELF]");
}
if (isset($_REQUEST['kustuta'])){
    $order=$connection->prepare('DELETE FROM loomad where id=?');
    $order->bind_param("i",$_REQUEST['kustuta']);
    $order->execute();
}
?>
<h1>Andmetabeli "Loomad" sisu näitamine ja lisamine</h1>
<?php
global $connection;
//showing data in a table
$order=$connection->prepare("SELECT id,nimi,kirjeldus FROM loomad");
$order->bind_result($id,$nimi,$kirjeldus);
$order->execute();
echo "<table>";
echo "<tr>
<th>ID</th>
<th>Loomanimi</th>
<th>Kirjeldus</th>
<th>Tegevus</th>
</tr>";
while($order->fetch()){
    echo "<tr>";
    echo "<td>$id</td>";
    echo "<td>$nimi</td>";
    echo "<td>$kirjeldus</td>";
    echo "<td><a href='".clearVarsExcept("leht")."&kustuta=$id'>Kustuta</a></td>";
    echo "</tr>";
}
echo "</table>";
?>
<br>
<form action="<?=clearVarsExcept('leht')?>" method="post">
    <label for="nimi">Loomanimi</label>
    <input type="text" name="loomanimi" id="nimi" maxlength="50">
    <label for="Kirjeldus">Kirjeldus</label>
    <input type="text" name="Kirjeldus" id="Kirjeldus">
    <input type="submit" value="Lisa">
</form>
<?php
$order->close();
?>

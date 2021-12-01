<?php

$parool='admin';//$_REQUEST['pass'];
$sool='txt';
$krypt=crypt($parool,$sool);
echo $krypt;
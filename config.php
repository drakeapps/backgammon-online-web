<?php


$mysqlhost = "localhost";
$mysqlname = "backgammon";
$mysqluser = "backgammon";
$mysqlpass = "npXYe3W4Kdxb5BBL";

//$dbh = mysqli_init();

//mysqli_real_connect($dbh, $mysqlhost, $mysqluser, $mysqlpass, $mysqlname);

$mysql = new mysqli($mysqlhost, $mysqluser, $mysqlpass, $mysqlname);




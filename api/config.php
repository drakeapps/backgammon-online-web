<?php


$mysqlhost = "localhost";
$mysqlname = "backgammon-dev";
$mysqluser = "backgammon-dev";
$mysqlpass = "t6Z43TfQPC2Xjvm3";

//$dbh = mysqli_init();

//mysqli_real_connect($dbh, $mysqlhost, $mysqluser, $mysqlpass, $mysqlname);

$mysql = new mysqli($mysqlhost, $mysqluser, $mysqlpass, $mysqlname);




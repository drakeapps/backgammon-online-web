<?php

include("config.php");
include("functions.php");

$req = $mysql->query("SELECT * FROM `devices` WHERE `user` = '".$_REQUEST['user']."' AND `device` = '".$_REQUEST['device']."' LIMIT 1");

if($req->num_rows != 1) {
  echo "-1";
  exit;
}

$row = $req->fetch_assoc();
$lastcheck = $row["lastcheck"];
//echo $lastcheck."<br />";

$rql = $mysql->query("UPDATE `devices` SET `lastcheck` = '".time()."' WHERE `user` = '".$_REQUEST['user']."' AND `device` = '".$_REQUEST['device']."'");

$query = "SELECT count(*) as num FROM `games` WHERE ((`white` = '".$_REQUEST['user']."' AND `turn` = 'white') OR (`black` = '".$_REQUEST['user']."' AND `turn` = 'black')) AND `finished` = '0' AND `lasttime` > '$lastcheck' ";

$req = $mysql->query($query);
$row = $req->fetch_assoc();
echo $row['num'];
exit;
  

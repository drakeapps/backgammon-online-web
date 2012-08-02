<?php

include("config.php");

$req = $mysql->query("SELECT * FROM `devices` WHERE `user` = '".$mysql->real_escape_string($_GET['user'])."' AND `device` = '".$mysql->real_escape_string($_GET['device'])."' LIMIT 1");

if($req->num_rows != 1) {
  echo "false";
} else {
	echo "true";
}


<?php

include("config.php");


$query = "SELECT * FROM `users` WHERE `username` = '".$mysql->real_escape_string($_POST['user'])."' LIMIT 1";
//echo $query;
$result = $mysql->query($query);
if($result->num_rows == 1) {
	$row = $result->fetch_assoc();
	$pass = sha1($row["username"].$_POST['pass'].$row["salt"]);
	//echo $pass;
	if($pass == $row["passhash"]) {
	  //valid login. generate unique device id
	  $device = sha1(time().$row["username"].$_SERVER['REMOTE_ADDR']);
	  $query = "INSERT INTO `devices` ( `device`, `user` ) VALUES ( '".$device."', '".$row["id"]."' )";
	  //echo $query;
	  $r = $mysql->query($query);
	  //$COOKIE['device'] = $device;
	  //$COOKIE['user'] = $row["id"];
	  //setcookie("device", $device);
	  //setcookie("user", $row["id"]);
	  //header("Location: loggedin.php");
	  
	  echo $device.";".$row["id"].";".$row["username"];
	  die;
	
	// next 2 errors should never happen
	// if they do, I'm a dumbass
	
	} else {
	  error("User/Password incorrect");
	}
	
} else {
	error("User/Password Incorrect");
}
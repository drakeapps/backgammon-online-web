<?php

include("config.php");


function email($game, $from) {
	
	global $mysql;
	
	
	$r = $mysql->query("SELECT * FROM `games` WHERE `id` = '".$mysql->real_escape_string($game)."' LIMIT 1");
	
	
	$headers = 'From: no-reply@drakeapps.com';
	
	if($r->num_rows < 1) {
		echo "failure 1";
		return false;
	}
	
	$row = $r->fetch_assoc();
	
	if($row['white'] == $from) {
		$user = $row['black']; 
	} else {
		$user = $row['white'];
	}
	
	$r = $mysql->query("SELECT * FROM `users` WHERE `id` = '".$mysql->real_escape_string($user)."' AND `emailnotify` = 1 LIMIT 1");
	
	$p = $mysql->query("SELECT * FROM `users` WHERE `id` = '".$mysql->real_escape_string($from)."' LIMIT 1");
	
	if($r->num_rows < 1 || $p->num_rows < 1) {
		echo "failure 2";
		return false;
	}
	
	$row = $r->fetch_assoc();
	
	$opp = $p->fetch_assoc();
	
	
	
	
	$message = "Hey, ".$row['username']."
	 
Your move with ".$opp['username']." on Backgammon Online

	
	To turn off notifications, visit: 
	http://api.drakeapps.com/backgammon/email.php?email=off&user=".$row['id']."&check=".sha1($row['salt'])."
	";
	
	mail($row['email'], "Your Move in Backgammon Online", $message, $headers);
	
}

if($_GET['email'] == "off") {
	
	$r = $mysql->query("SELECT * FROM `users` WHERE `id` = '".$mysql->real_escape_string($_GET['user'])."' LIMIT 1");
	if($r->num_rows < 1) {
		"user not found";
	}
	
	$row = $r->fetch_assoc();
	
	if($_GET['check'] == sha1($row['salt'])) {
		$r = $mysql->query("UPDATE  `backgammon`.`users` SET  `emailnotify` =  '0' WHERE  `users`.`id` = '".$mysql->real_escape_string($_GET['user'])."' LIMIT 1 ;");
		
		echo "email notifications turned off";
	} else {
		/*echo $_GET['check'];
		echo "<br />";
		echo sha1($row['salt']);
		echo "<br />";*/
		echo "wrong credentials<br />email support@drakeapps.com for assistance";
	}
	
	
}

//email(31,1);
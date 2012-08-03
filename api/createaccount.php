<?php

include("../config.php");


function error($text) {
	echo $text;
	die;
}

if(
		isset($_POST['email']) && 
		isset($_POST['user']) && 
		isset($_POST['pass']) && 
		$_POST['pass'] != "" && 
		sha1($_POST['user'].$_POST["pass"].$_POST['email']) == $_POST['hash']) {
		
		
	$username = $mysql->real_escape_string($_POST['user']);
	$pass = $_POST['pass'];
	
	
	
	if(strlen($username) < 6) {
		error("Username must be at least 6 characters long");
	} else if(strlen($pass) < 6) {
		error("Password must be at least 6 characters long");
	} else {
		
		
		
		$salt = substr(sha1(time().$username."derpderp"), 0, 8);
		$hash = sha1($username.$pass.$salt);
		$email = $mysql->real_escape_string($_POST['email']);
		
		$res = $mysql->query("SELECT `id` FROM `users` WHERE `username` = '".$username."' OR `email` = '".$email."' LIMIT 1");
		if($res->num_rows > 0) {
		
			error("Username or email already in use");
			
		} else {
		
		
			$query = "INSERT INTO `users` (`username`, `passhash`, `salt`, `email`) VALUES ( '$username', '$hash', '$salt', '$email')";
			
			if($result = $mysql->query($query)) {
				
				$_POST['login'] = true;
				
				/*
				// Creates 3 random games
				$opponent = 1;
				
				$board = "6;6;6;6;6;8;8;8;13;13;13;13;13;24;24
1;1;12;12;12;12;12;17;17;17;19;19;19;19;19";
				$r = $mysql->query("SELECT `id` FROM `users` WHERE `username` = '".$username."' LIMIT 1");
				$row = $r->fetch_assoc();
				$user = $row["id"];
				//for($i=0; $i<3; $i++) {
				$since = time() - 60*60*24*4;
				$res = $mysql->query("SELECT `id` FROM `users` WHERE `id` != '".$mysql->real_escape_string($user)."' AND `lastmove` > $since AND `moves` > 3 ORDER BY RAND() LIMIT 3");
				while($row123 = $res->fetch_assoc()) {
					$opponent = $mysql->real_escape_string($row123["id"]);
					$die1 = rand(1,6);
					$die2 = rand(1,6); 
				
					$query = "INSERT INTO `games` (
								`id`, `white`, `black`, `turn`, `die1`, `die2`, `lasttime`, `board` 
							) VALUES (
								NULL, '".$user."', '".$opponent."', 'white', '".$die1."', '".$die2."', '".time()."', '".$board."'
							);";
					$mysql->query($query);
				}
				
				*/
				
				
			}
			else {
				error("Failed to create account. Try again later");
			}
		}
	}
}


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
  
  
  
  
  
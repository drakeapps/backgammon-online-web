<?php

<?php

include("config.php");
include("functions.php");

function error($text) {
	echo $text;
	die;
}


$req = $mysql->query("SELECT * FROM `devices` WHERE `user` = '".$mysql->real_escape_string($_REQUEST['user'])."' AND `device` = '".$mysql->real_escape_string($_REQUEST['device'])."' LIMIT 1");

if($req->num_rows != 1) {
  //header("Location: login.php");
  error("UNAUTHORIZED");
}

if(isset($_POST['adduser'])) {
	$board = "6;6;6;6;6;8;8;8;13;13;13;13;13;24;24
1;1;12;12;12;12;12;17;17;17;19;19;19;19;19";
	if($_POST["submit"] == "Start Game") {
		if($res = $mysql->query("SELECT `id` FROM `users` WHERE `username` = '".clean($_POST['adduser'])."' LIMIT 1")) {
			if($res->num_rows == 1) {
				$row = $res->fetch_assoc();
				$opponent = $row["id"];
			} else  {
				error("User not found");
			}
		} else {
			error("User not found");
		}
	} else {
		$since = time() - 60*60*24*7;
		$res = $mysql->query("SELECT `id` FROM `users` WHERE `id` != '".$mysql->real_escape_string($_REQUEST['user'])."' AND `lastmove` > $since AND `moves` > 3 ORDER BY RAND() LIMIT 1");
		$row = $res->fetch_assoc();
		$opponent = $mysql->real_escape_string($row["id"]);
	}
	if($opponent != "") {
		$user = $mysql->real_escape_string($_REQUEST['user']);
		
		$die1 = rand(1,6);
		$die2 = rand(1,6);
		
		$query = "INSERT INTO `games` (
			`id`, `white`, `black`, `turn`, `die1`, `die2`, `lasttime`, `board` 
		) VALUES (
			NULL, '".$user."', '".$opponent."', 'white', '".$die1."', '".$die2."', '".time()."', '".$board."'
		);";
		
		if($req = $mysql->query($query)) {
			header("Location: gamelist.php?user=".$mysql->real_escape_string($_REQUEST['user'])."&device=".$mysql->real_escape_string($_REQUEST['device']));
			
			//echo "Created game";
			
		} else {
			error("Game cannot be created at this time");
		}
	}
	
} 

?>

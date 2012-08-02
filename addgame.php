<?php

include("config.php");
include("functions.php");


$req = $mysql->query("SELECT * FROM `devices` WHERE `user` = '".$mysql->real_escape_string($_REQUEST['user'])."' AND `device` = '".$mysql->real_escape_string($_REQUEST['device'])."' LIMIT 1");

if($req->num_rows != 1) {
  header("Location: login.php");
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
				echo "User not found";
			}
		} else {
			echo "User not found";
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
			echo "Game cannot be created at this time";
		}
	}
	
} 

?>
<!DOCTYPE html>
<html>
<head>
<!--<link rel="stylesheet" href="http://code.jquery.com/mobile/1.0a1/jquery.mobile-1.0a1.min.css" />
<script src="http://code.jquery.com/jquery-1.4.3.min.js"></script>
<script src="http://code.jquery.com/mobile/1.0a1/jquery.mobile-1.0a1.min.js"></script>
-->
<?php echo jquery(); ?>
<!--<link rel="stylesheet" href="style.css" type="text/css" />
--><title>Add Game</title>
</head>
<body>
<div data-role="page">
<div data-role="header"><h2>Start A New Game</h2></div>
<div data-role="content" id="content">

<?php 
if(!isset($_GET['pickuser'])) {
?>

<form method="post" action="addgame.php" id="random" >
<input id="random1" type="submit" name="random" value="Random" />
<input id="random2" type="hidden" name="adduser" />
<input id="random3" type="hidden" name="user" value="<?php echo $_REQUEST['user']; ?>" />
<input id="random4" type="hidden" name="device" value="<?php echo $_REQUEST['device']; ?>" />
</form>

<?php  } ?>

<h2>Against User:</h2>
<form method="post" action="addgame.php" id="against" >
<input id="against1" type="text" name="adduser" value="<?php echo $_GET['pickuser']; ?>" />
<input id="against2"type="hidden" name="user" value="<?php echo $_REQUEST['user']; ?>" />
<input id="against3"type="hidden" name="device" value="<?php echo $_REQUEST['device']; ?>" />
<input id="against4"type="submit" name="submit" value="Start Game" />
</form>

</div>
</div>

</body>
</html>
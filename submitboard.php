<?php

include("config.php");
include("functions.php");
include("email.php");

$req = $mysql->query("SELECT * FROM `devices` WHERE `user` = '".$mysql->real_escape_string($_GET['user'])."' AND `device` = '".$mysql->real_escape_string($_GET['device'])."' LIMIT 1");

if($req->num_rows != 1) {
  echo "bad credentials";
}

$user = $mysql->real_escape_string($_GET['user']);
$game = $mysql->real_escape_string($_GET['game']);
$black = $mysql->real_escape_string(substr($_GET['black'], 0, -1));
$white = $mysql->real_escape_string(substr($_GET['white'], 0, -1));
$move = time();

$board = $black."\n".$white;



$die1 = rand(1,6);
$die2 = rand(1,6);

$win = "";

$whitescore = $_GET['whitescore'];
if($whitescore == 15) {
	$win = " , `finished` = 1 ";
}
$blackscore = $_GET['blackscore'];
if($blackscore == 15) {
	$win = " , `finished` = 2 ";
}

// since version 1.0 (aka 9) the hash has been fixed
// prevents people taking a winning board and hash and using it in other games
// the game id is now embedded in the hash too
// the user id might be added at a later date, but i dont think there will be a need to
if($_GET['version'] > 8) {
 $hash = sha1($game.$_GET['black'].$_GET['white'].$whitescore.$blackscore."james is awesome");
} else {
 $hash = sha1($_GET['black'].$_GET['white'].$whitescore.$blackscore."james is awesome");
}
if($hash != $_GET['hash']) {
	echo "error. wrong hash";
	echo "<br />$hash<br />".$_GET['hash'];
	exit;
}

$query = "UPDATE `games` 
		SET `board` = '$board', `lasttime` = '$move', `die1` = '$die1', `die2` = '$die2', `moves` = `moves` + 1 , `turn` = 'black', `whitescore` = '$whitescore', `blackscore` = '$blackscore' $win
		WHERE `id` = '$game' 
			AND `white` = '$user'  LIMIT 1";

$query2 = "UPDATE `games` 
		SET `board` = '$board', `lasttime` = '$move', `die1` = '$die1', `die2` = '$die2', `moves` = `moves` + 1 , `turn` = 'white', `whitescore` = '$whitescore', `blackscore` = '$blackscore' $win
		WHERE `id` = '$game' 
			AND `black` = '$user' LIMIT 1";

//echo $query;

$winq = "";
if($win != "") {
	$winq = ", `wins` = `wins` + 1
	, `finishedgames` = `finishedgames` + 1";
}

$queryuser = "UPDATE `users`
	SET 
	`moves` = `moves`+1,
	`lastmove` = '".time()."'
	$winq
	
	WHERE `id` = '$user'
	
	LIMIT 1";


if($r = $mysql->query($query) && $s = $mysql->query($query2)) {
	echo "true";
	//$t = $mysql->query($queryuser);
	//email($game, $user);
} else {
	echo "mysql error";
}
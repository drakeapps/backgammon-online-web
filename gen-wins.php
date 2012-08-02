<?php

// builds the win, games, and moves count
// this is incremented when you make a move now
// but not before. this only needs to be run once
// though you could use it at any time to rebuild everything

include("config.php");

$users = $mysql->query("SELECT `id` FROM `users`");


while($row = $users->fetch_assoc()) {
	$user = $row["id"];
	//$user = 1;
	$games = $mysql->query("SELECT * FROM `games` WHERE (`black` = '$user' AND `blackscore` = 15) OR (`white` = '$user' AND `whitescore` = 15)");
	
	$lastmove = 0;
	
	$wins = $games->num_rows;
	$moves = 0;
	$gammons = 0;
	$gammoned = 0;
	$gamecount = 0;
	$gamecount += $games->num_rows;
	$finished = 0;
	$finished += $games->num_rows;
	
	while($r = $games->fetch_assoc()) {
		$moves += ceil($r["moves"] / 2.0);
		if($r["lasttime"] > $lastmove)
			$lastmove = $r["lasttime"];
		if($r["blackscore"] == 0 || $r["whitescore"] == 0)
			$gammons += 1;
	}
	
	$losses = $mysql->query("SELECT * FROM `games` WHERE (`black` = '$user' AND `whitescore` = 15) OR (`white` = '$user' AND `blackscore` = 15)");
	
	$finished += $losses->num_rows;
	$gamecount += $losses->num_rows;
	
	while($r = $losses->fetch_assoc()) {
		$moves += floor($r["moves"] / 2.0);
		if($r["lasttime"] > $lastmove)
			$lastmove = $r["lasttime"];
		if($r["blackscore"] == 0 || $r["whitescore"] == 0)
			$gammoned += 1;
	}
	
	$unfinished = $mysql->query("SELECT * FROM `games` WHERE (`black` = '$user' OR `white` = '$user') AND (`whitescore` < 15 AND `blackscore` < 15)");
	$gamecount += $unfinished->num_rows;
	
	while($r = $unfinished->fetch_assoc()) {
		$moves += floor($r["moves"] / 2.0);
		if($r["lasttime"] > $lastmove && $r["moves"] > 2)
			$lastmove = $r["lasttime"];
	}
	
	//echo "user $user: $wins/$finished ($gamecount) $moves moves ($lastmove) $gammons<br />";
	
	$update = "UPDATE `users` SET `moves` = '$moves', `wins` = '$wins', `finishedgames` = '$finished', `games` = '$gamecount', `lastmove` = '$lastmove', `gammons` = '$gammons', `gammoned` = '$gammoned'
		WHERE `id` = '$user'
		LIMIT 1";
	$q = $mysql->query($update);
	
}



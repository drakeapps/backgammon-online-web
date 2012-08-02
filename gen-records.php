<?php

// builds the win, games, and moves count
// this is incremented when you make a move now
// but not before. this only needs to be run once
// though you could use it at any time to rebuild everything

include("config.php");

$users = $mysql->query("SELECT `id` FROM `users`");

$truncate = $mysql->query("TRUNCATE TABLE `records`;");


while($row = $users->fetch_assoc()) {
	$user = $row["id"];
	//$user = 1;
	$games = $mysql->query("SELECT * FROM `games` WHERE (`black` = '$user') OR (`white` = '$user')");
	
	while($r = $games->fetch_assoc()) {
		$g = 0;
		$f = 0;
		$m = 0;
		$w = 0;
		$gam = 0;
		if($r["black"] == $user) {
			$opp = $r["white"];
		} else {
			$opp = $r["black"];
		}
		$g = 1;
		
		if($r["finished"] > 0)
			$f = 1;
		
		$m = $r["moves"];
		
		if($r["black"] == $user && $r["blackscore"] == 15) {
			$w = 1;
			if($r["whitescore"] == 0)
				$gam = 1;
		}
		if($r["white"] == $user && $r["whitescore"] == 15){
			$w = 1;
			if($r["blackscore"] == 0)
				$gam = 1;
		} 
		
		
		echo "insert $user|$opp";
		if(!($p = $mysql->query("INSERT INTO 
			`records` (`user`, `opponent`, `wins`, `games`, `gammons`, `moves`, `finished`)
			VALUES (
				'$user',
				'$opp',
				'$w',
				'$g',
				'$gam',
				'$m',
				'$f'
			)
		"))) {
			echo "  update $user|$opp";
			$p = $mysql->query("UPDATE
				`records`
				SET
					`wins` = `wins` + $w,
					`games` = `games` + $g,
					`gammons` = `gammons` + $gam,
					`moves` = `moves` + $m,
					`finished` = `finished` + $f
				WHERE 
					`user` = '$user' AND `opponent` = '$opp'
				LIMIT 1
					");
		}
		
		echo "<br />";
		
		
	}
	
	
}



<?php

include("config.php");
include("functions.php");

if(isset($_GET['free'])) {
	$free = "&free=free";
	$freemode = true;
} else {
	$free = "";
	$freemode = false;
}

$req = $mysql->query("SELECT * FROM `devices` WHERE `user` = '".clean($_GET['user'])."' AND `device` = '".clean($_GET['device'])."' LIMIT 1");

if($req->num_rows != 1) {
  header("Location: login.php");
}

$user = $_REQUEST['user'];
$device = $_REQUEST['device'];

$opponents = "SELECT `users`.`username`, `records`.`wins`, `records`.`games`, `records`.`gammons`,`records`.`moves`, `records`.`finished`  FROM `records`, `users` WHERE `user` = '".clean($user)."' AND `finished` > 0 AND `users`.`id` = `records`.`opponent`";

$opps = $mysql->query($opponents);

$youq = "SELECT * FROM `users` WHERE `id` = '".clean($user)."' LIMIT 1";

$you = $mysql->query($youq);


?>
<!DOCTYPE html>
<html>
<head>
<!--<link rel="stylesheet" href="style.css" type="text/css" />
--><?php echo jquery(); ?>
</head>
<body>
<div data-role="page">
<div data-role="header">
<h1>Player Record</h1>
<div data-role="navbar">
	<ul>
		<li><a href="gamelist.php?user=<?=$user?>&device=<?=$device?><?=$free?>&time=<?php /* hack to let jQuery keep reloading the page */ echo time(); ?>"  rel="external">Games</a></li>
		<li><a href="player.php?user=<?=$user?>&device=<?=$device?><?=$free?>" class="ui-btn-active" rel="external">Player Record</a></li>
		<li><a href="leaderboards.php?user=<?=$user?>&device=<?=$device?><?=$free?>" rel="external">Leaderboards</a></li>

		<!--<li><a href="addgame.php?user=<?=$user?>&device=<?=$device?>">Add Game</a></li>
		-->
	</ul>
</div>
</div>


<div data-role="content">
	
	<ul data-role="listview">
		<li>
			<?php
			$row = $you->fetch_assoc();
			
			echo "<h3>".$row["username"]."</h3>";
			echo "<p>";
			echo "Playing ".($row["games"] - $row["finishedgames"])." games. Finished ".$row["finishedgames"];
			echo "</p>";
			echo "<p>";
			echo "Won: ".$row["wins"]." &nbsp; Lost: ".($row["finishedgames"] - $row["wins"]);
			echo "</p>";
			echo "<p>";
			echo "Made ".$row["moves"]." moves";
			echo "</p>";
			
			
			?>
		</li>
		<li data-role="list-divider">Opponents <span class="ui-li-count"><?php echo $opps->num_rows; ?></span></li>
		
		<?php

		while($row = $opps->fetch_assoc()) {
		  echo "<li class=\"game\">\n";
		  //echo "<a href='#' onclick='choose(\"".$row["id"]."\")'>";
		  echo "<a href=\"addgame.php?user=$user&device=$device&pickuser=".$row['username']."\">";
		  echo "<h3>".$row["username"]."</h3>";
		  //echo "</a>";
		  echo "</a>";
		  echo "<p>";
		  echo "Won: ".$row["wins"]." &nbsp; Lost: ".($row["finished"] - $row["wins"]);
		  echo "</p>\n";
		  echo "<p>";
		  echo $row["moves"]." moves";
		echo " &nbsp; Playing ".($row["games"] - $row["finished"])." game". ( (($row["games"] - $row["finished"]) == 1) ? "" : "s");
		  echo "</p>\n";
		  echo "<p class=\"ui-li-aside\">";
		$percent = ($row["wins"]/$row["finished"]) * 100;
		if($percent >= 50) {
			echo "<span style=\"color: 00e600;\">";
			echo "Win ";
			printf("%.0f", $percent);
			echo "% of the time";
			echo "</span>";
		} else {
			    $percent = 100 - $percent;
				echo "<span style=\"color: e60000 !important;\">";
				echo "Lose ";
				printf("%.0f", $percent);
				echo "% of the time";
				echo "</span>";
			}
		
		if($row["gammons"] > 0) {
			if($row["gammons"] > 1) {
				echo " <br /><br /><em>".$row["gammons"]." gammons</em>";
			} else {
				echo " <br /><br /><em>1 gammon</em>";			
			}
		}
		  echo "</p>\n";
		  echo "</li>\n\n";

		}

		?>
	</ol>
	</div>

</html>


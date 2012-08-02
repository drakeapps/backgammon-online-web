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
$user = $_REQUEST['user'];
$device = $_REQUEST['device'];

$winratio = "SELECT * FROM `users` WHERE `finishedgames` > 9 AND `wins` > 0 ORDER BY (`wins` / `finishedgames`) DESC LIMIT 20";

$rating = "SELECT *, ((`wins` / `finishedgames`) * (1.5 + `gammons` - `gammoned`)) as rating
FROM `users` 
WHERE `finishedgames` > 0 AND `wins` > 0 
ORDER BY ((`wins` / `finishedgames`) * (1.5 + `gammons` - `gammoned`)) DESC LIMIT 20";

$wins = "SELECT * FROM `users` WHERE `finishedgames` > 0 AND `wins` > 0 ORDER BY `wins` DESC LIMIT 20";

$active = "SELECT * FROM `users` WHERE `finishedgames` > 0 ORDER BY `moves` DESC LIMIT 20";

switch($_GET['board']) {
	
	case "rating" :
		$games = $mysql->query($rating);
		$type = 2;
		break;
		
	case "wins":
		$games = $mysql->query($wins);
		$type = 3;
		break;
		
	case "activity":
		$games = $mysql->query($active);
		$type = 4;
		break;
	
	
	default:
		$games = $mysql->query($winratio);
		$type = 1;
		break;
}



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
<h1>Leaderboards</h1>
<div data-role="navbar">
	<ul>
		<li><a href="gamelist.php?user=<?=$user?>&device=<?=$device?><?=$free?>&time=<?php /* hack to let jQuery keep reloading the page */ echo time(); ?>"  rel="external">Games</a></li>
		<li><a href="player.php?user=<?=$user?>&device=<?=$device?><?=$free?>" rel="external">Player Record</a></li>
		<li><a href="leaderboards.php?user=<?=$user?>&device=<?=$device?><?=$free?>" class="ui-btn-active" rel="external">Leaderboards</a></li>

		<!--<li><a href="addgame.php?user=<?=$user?>&device=<?=$device?>">Add Game</a></li>
		-->
	</ul>
</div>
<div data-role="navbar">
	<ul>
		<li><a href="leaderboards.php?user=<?=$user?>&device=<?=$device?><?=$free?>" <?php if($type == 1) { ?>class="ui-btn-active"<?php } ?> rel="external">Win Percentage</a></li>
		<li><a href="leaderboards.php?user=<?=$user?>&device=<?=$device?><?=$free?>&board=rating" <?php if($type == 2) { ?>class="ui-btn-active"<?php } ?> rel="external">Rating</a></li>
		<!-- <li><a href="leaderboards.php?user=<?=$user?>&device=<?=$device?><?=$free?>&board=wins" <?php if($type == 3) { ?>class="ui-btn-active"<?php } ?> rel="external">Wins</a></li> -->
		<li><a href="leaderboards.php?user=<?=$user?>&device=<?=$device?><?=$free?>&board=activity" <?php if($type == 4) { ?>class="ui-btn-active"<?php } ?> rel="external">Activity</a></li>

		<!--<li><a href="addgame.php?user=<?=$user?>&device=<?=$device?>">Add Game</a></li>
		-->
	</ul>
</div>

</div>

<div data-role="content">
	
	<ol data-role="listview">
		<?php

		while($row = $games->fetch_assoc()) {
		  echo "<li class=\"game\">\n";
		  //echo "<a href='#' onclick='choose(\"".$row["id"]."\")'>";
		  echo "<a href=\"addgame.php?user=$user&device=$device&pickuser=".$row['username']."\">";
		  echo "<h3>".$row["username"]."</h3>";
		  echo "</a>";
		  echo "<p>";
		  echo "Won: ".$row["wins"]." &nbsp; Lost: ".($row["finishedgames"] - $row["wins"]);
		
		  echo "</p>\n";
		  echo "<p class=\"ui-li-aside\">";
		  if($type == 1) {
		  	printf("%.0f", ($row["wins"]/$row["finishedgames"])*100);
		  	echo "% wins";
		  } else if($type == 2) {
		  	//printf("%.2f",  ($row["wins"]/$row["finishedgames"])*($row['gammons'] - $rows['gammoned'] + 1.5 ));
		  	printf("%.2f", $row['rating']);
		  	echo " rating";
		  } else if($type == 4) {
		  	echo $row["moves"]." moves";
		  }
		if($row["gammons"] > 0 && $type != 4) {
			if($row["gammons"] > 1) {
				echo " <br /><br /><em>".$row["gammons"]." gammons</em>";
			} else {
				echo " <br /><br /><em>1 gammon</em>";			
			}
		}
		if($row["gammoned"] > 0 && $type == 2) {
			if($row["gammoned"] > 1) {
				echo " <br /><br /><em>".$row["gammoned"]." gammoned</em>";
			} else {
				echo " <br /><br /><em>1 gammoned</em>";			
			}
		}
		  echo "</p>\n";
		  echo "</li>\n\n";

		}

		?>
	</ol>
	</div>

</html>

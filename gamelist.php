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

$query = "SELECT `users`.`username`, `games`.`id`, `games`.`moves`, `games`.`lasttime`, `games`.`white`, `games`.`whitescore`, `games`.`blackscore` 
	FROM `games`,`users` 
	WHERE (
		(`white` = '".clean($user)."' AND `turn` = 'white' AND  `games`.`black` =  `users`.`id`) 
			OR 
		(`black` = '".clean($user)."' AND `turn` = 'black' AND  `games`.`white` =  `users`.`id`)
	) 
		AND 
	`finished` < 1  
	
	ORDER BY `games`.`lasttime` DESC
	
	
	";

$yourturn = $mysql->query($query);

$query = "SELECT `users`.`username`, `games`.`id`, `games`.`moves`, `games`.`lasttime`, `games`.`white`, `games`.`whitescore`, `games`.`blackscore` 
	FROM `games`,`users` 
	WHERE (
		(`white` = '".clean($user)."' AND `turn` = 'black' AND  `games`.`black` =  `users`.`id`) 
			OR 
		(`black` = '".clean($user)."' AND `turn` = 'white' AND  `games`.`white` =  `users`.`id`)
	) 
		AND 
	`finished` < 1 
	
	ORDER BY `games`.`lasttime` DESC
	
	";

$otherturn = $mysql->query($query);


$query = "SELECT `users`.`username`, `games`.`id`, `games`.`moves`, `games`.`lasttime`, `games`.`white`, `games`.`whitescore`, `games`.`blackscore` 
	FROM `games`,`users` 
	WHERE (
		(`white` = '".clean($user)."' AND  `games`.`black` =  `users`.`id`) 
			OR 
		(`black` = '".clean($user)."' AND  `games`.`white` =  `users`.`id`)
	) 
		AND 
	`finished` > 0 
	
	ORDER BY `games`.`lasttime` DESC
	
	";

$finished = $mysql->query($query);

?>
<!DOCTYPE html>
<html>
<head>
<!--<link rel="stylesheet" href="style.css" type="text/css" />
--><?php echo jquery(); ?>
<script type="text/javascript">
function choose(id) {
	window.HTMLOUT.chooseGame(id);
	window.location = "http://api.drakeapps.com/backgammon/loadgame.php";
}

</script>
</head>
<body>
<div data-role="page">
<div data-role="header">
<a href="gamelist.php?user=<?=$user?>&device=<?=$device?><?=$free?>&time=<?php /* hack to let jQuery keep reloading the page */ echo time(); ?>" rel="external" data-icon="refresh" data-role="button" data-inline="true">Refresh</a> 
<h1>Games</h1>
<a href="addgame.php?user=<?=$user?>&device=<?=$device?>" data-icon="plus" data-role="button" data-inline="true" >Add Game</a> 
<div data-role="navbar">
	<ul>
		<li><a href="gamelist.php?user=<?=$user?>&device=<?=$device?><?=$free?>&time=<?php /* hack to let jQuery keep reloading the page */ echo time(); ?>" class="ui-btn-active" rel="external">Games</a></li>
		<li><a href="player.php?user=<?=$user?>&device=<?=$device?><?=$free?>" rel="external">Player Record</a></li>
		<li><a href="leaderboards.php?user=<?=$user?>&device=<?=$device?><?=$free?>" rel="external">Leaderboards</a></li>
		
		<!--<li><a href="addgame.php?user=<?=$user?>&device=<?=$device?>">Add Game</a></li>
		-->
	</ul>
</div>
</div>

<?php if($freemode) { ?>
 	<script type="text/javascript"><!--
google_ad_client = "ca-pub-1633320757911200";
/* Backgammon Online Android */
google_ad_slot = "2223563852";
google_ad_width = 320;
google_ad_height = 50;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
	<?php } ?>

	<?php if($freemode && false) { ?>
		<script type="text/javascript">
		var admob_vars = {
		 pubid: 'a14cdef7d5d7f79', // publisher id
		 bgcolor: '000000', // background color (hex)
		 text: 'FFFFFF', // font-color (hex)
		 ama: false, // set to true and retain comma for the AdMob Adaptive Ad Unit, a special ad type designed for PC sites accessed from the iPhone.  More info: http://developer.admob.com/wiki/IPhone#Web_Integration
		 test: true // test mode, set to false to receive live ads
		};
		</script>
		<script type="text/javascript" src="http://mmv.admob.com/static/iphone/iadmob.js"></script>
	<?php } ?>
	<div data-role="content">
<ul class="list" data-role="listview">
<?php if($freemode && false) { ?>
<li class="game"><a href="http://www.appbrain.com/app/com.drakeapps.backgammon?install" rel="external"><h2>Upgrade to the Full Version</h2></a></li>
<?php } ?>



<li data-role="list-divider">Your Turn <span class="ui-li-count"><?php echo $yourturn->num_rows; ?></span></li>
<?php


$i=0;
while($row = $yourturn->fetch_assoc() ){
  
  if($i++ < 10) {
  echo "<li class=\"game\">\n";
  echo "<a href='#' onclick='choose(\"".$row["id"]."\")'>";
  echo "<h3>".$row["username"]." vs. you</h3>";
  echo "</a>";
  echo "<p>";
  if($row["white"] == $user) {
  	$yourscore = $row["whitescore"];
  	$oppscore = $row["blackscore"];
  } else {
  	$yourscore = $row["blackscore"];
  	$oppscore = $row["whitescore"];
  }
  echo $row["username"]." - <strong>$oppscore</strong>&nbsp;&nbsp;&nbsp;you - <strong>$yourscore</strong>";
  echo "</p>\n";
  echo "<p>Last move ".getTime($row["lasttime"])."</p>\n";
  echo "<p class=\"ui-li-aside\">".$row["moves"]." moves</p>\n";
  echo "</li>\n\n";

  }
}

?>

<li data-role="list-divider">Their Turn <span class="ui-li-count"><?php echo $otherturn->num_rows; ?></span></li>
<?php 

$i = 0;

if($user != 1) {
while($row = $otherturn->fetch_assoc()) {
  if($i++ < 10) {
  echo "<li class=\"game\">\n";
  //echo "<a href='#' onclick='choose(\"".$row["id"]."\")'>";
  echo "<h3>".$row["username"]." vs. you</h3>";
  //echo "</a>";
  echo "<p>";
  if($row["white"] == $user) {
  	$yourscore = $row["whitescore"];
  	$oppscore = $row["blackscore"];
  } else {
  	$yourscore = $row["blackscore"];
  	$oppscore = $row["whitescore"];
  }
  echo $row["username"]." - <strong>$oppscore</strong>&nbsp;&nbsp;&nbsp;you - <strong>$yourscore</strong>";
  echo "</p>\n";
  echo "<p>Last move ".getTime($row["lasttime"])."</p>\n";
  echo "<p class=\"ui-li-aside\">".$row["moves"]." moves</p>\n";
  echo "</li>\n\n";
  }
}
}
?>

<li data-role="list-divider">Finished Games (click to start a new game) <span class="ui-li-count"><?php echo $finished->num_rows; ?></span></li>

<?php 

$i=0;

while($row = $finished->fetch_assoc()) {
  if($i++ < 10) {
	
  if($row["white"] == $user) {
  	$yourscore = $row["whitescore"];
  	$oppscore = $row["blackscore"];
  } else {
  	$yourscore = $row["blackscore"];
  	$oppscore = $row["whitescore"];
  }
  
  echo "<li class=\"game\">\n";
  echo "<a href=\"addgame.php?user=$user&device=$device&pickuser=".$row['username']."\">";
  echo "<h3>".$row["username"]." vs. you</h3>";
  echo "</a>";
  
  echo "<p>";
  if($oppscore == 15) {
  	echo "<em>";
  }
  echo $row["username"]." - <strong>$oppscore</strong>&nbsp;&nbsp;&nbsp;";
  if($oppscore == 15) {
  	echo "</em>";
  }
  
  if($yourscore == 15) {
  	echo "<em>";
  }
  echo "you - <strong>$yourscore</strong>";
  if($yourscore == 15) {
  	echo "</em>";
  }
  
  echo "</p>\n";
  echo "<p>Last move ".getTime($row["lasttime"])."</p>\n";
  echo "<p class=\"ui-li-aside\">".$row["moves"]." moves</p>\n";
  echo "</li>\n\n";
  }
}

?>
</ul>
</div>

</div>
</body>
</html>



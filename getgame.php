<?php

include("config.php");
include("functions.php");


$req = $mysql->query("SELECT * FROM `devices` WHERE `user` = '".$mysql->real_escape_string($_GET['user'])."' AND `device` = '".$mysql->real_escape_string($_GET['device'])."' LIMIT 1");

if($req->num_rows != 1) {
  header("Location: login.php");
}

$user = $mysql->real_escape_string($_GET['user']);
$game = $mysql->real_escape_string($_GET['game']);


$WHITE = 2;
$BLACK = 1;


$res = $mysql->query("SELECT * FROM `games` WHERE `id` = '$game' AND (`white` = '$user' OR `black` = '$user') LIMIT 1");

if($res->num_rows != 1) {
	echo "Game not found.";
	exit;
}


$r = $res->fetch_assoc();

$yourColor = ( ((int) $r["white"]) == $user) ? $WHITE : $BLACK;




if($yourColor == $WHITE ) {
	$isTurn = ($r["turn"] === "white");
	$opponent = $r["black"];
} else {
	$isTurn = $r["turn"] === "black";
	$opponent = $r["white"];
}


$die1 = $r["die1"];
$die2 = $r["die2"];

$board = $r["board"];


echo "$yourColor;$isTurn;$opponent;$die1;$die2\n";

echo $board;




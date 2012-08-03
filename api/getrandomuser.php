<?php

include("../config.php");


// need hash check or gaping security hole
$res = $mysql->query("SELECT `id`,`name` FROM `users` WHERE `id` != '".$mysql->real_escape_string($user)."' AND `lastmove` > $since AND `moves` > 3 ORDER BY RAND() LIMIT 1");

$row = $res->fetch_assoc();

$username = $row["name"];
$userid = $row["userid"];

echo $username.";".$userid;



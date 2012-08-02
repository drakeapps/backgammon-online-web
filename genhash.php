<?php

$salt = substr(sha1(time().$username."derpderp"), 0, 8);
echo sha1($_GET['u'].$_GET['p'].$salt) ."<br />".$salt;
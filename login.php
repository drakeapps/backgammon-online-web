<?php

include("config.php");

if(isset($_POST['create'])) {
	if(isset($_POST['email']) && isset($_POST['user']) && isset($_POST['pass']) && $_POST['pass'] != "") {
		$username = $mysql->real_escape_string($_POST['user']);
		$pass = $_POST['pass'];
		if(strlen($username) < 4) {
			$error = "Username must be at least 4 characters long";
		} else if(strlen($pass) < 4) {
			$error = "Password must be at least 4 characters long";
		} else {
			
			$salt = substr(sha1(time().$username."derpderp"), 0, 8);
			$hash = sha1($username.$pass.$salt);
			$email = $mysql->real_escape_string($_POST['email']);
			$res = $mysql->query("SELECT `id` FROM `users` WHERE `username` = '".$username."' OR `email` = '".$email."' LIMIT 1");
			if($res->num_rows > 0) {
				$error = "Username/Email already in use";
			} else {
				$query = "INSERT INTO `users` (`username`, `passhash`, `salt`, `email`) VALUES ( '$username', '$hash', '$salt', '$email')";
				if($result = $mysql->query($query)) {
					$_POST['login'] = true;
					$opponent = 1;
					
					$board = "6;6;6;6;6;8;8;8;13;13;13;13;13;24;24
1;1;12;12;12;12;12;17;17;17;19;19;19;19;19";
					$r = $mysql->query("SELECT `id` FROM `users` WHERE `username` = '".$username."' LIMIT 1");
					$row = $r->fetch_assoc();
					$user = $row["id"];
					//for($i=0; $i<3; $i++) {
					$since = time() - 60*60*24*4;
					$res = $mysql->query("SELECT `id` FROM `users` WHERE `id` != '".$mysql->real_escape_string($user)."' AND `lastmove` > $since AND `moves` > 3 ORDER BY RAND() LIMIT 3");
					while($row123 = $res->fetch_assoc()) {
						$opponent = $mysql->real_escape_string($row123["id"]);
						$die1 = rand(1,6);
						$die2 = rand(1,6); 
					
						$query = "INSERT INTO `games` (
									`id`, `white`, `black`, `turn`, `die1`, `die2`, `lasttime`, `board` 
								) VALUES (
									NULL, '".$user."', '".$opponent."', 'white', '".$die1."', '".$die2."', '".time()."', '".$board."'
								);";
						$mysql->query($query);
					}
					//}
					
				}
				else {
					$error = "Failed to create account. Try again later";
				}
			}
		}
	}
}

if(isset($_POST['login'])) {
  $query = "SELECT * FROM `users` WHERE `username` = '".$mysql->real_escape_string($_POST['user'])."' LIMIT 1";
  //echo $query;
  $result = $mysql->query($query);
  if($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $pass = sha1($row["username"].$_POST['pass'].$row["salt"]);
    //echo $pass;
    if($pass == $row["passhash"]) {
      //valid login. generate unique device id
      $device = sha1(time().$row["username"].$_SERVER['REMOTE_ADDR']);
      $query = "INSERT INTO `devices` ( `device`, `user` ) VALUES ( '".$device."', '".$row["id"]."' )";
      //echo $query;
      $r = $mysql->query($query);
      //$COOKIE['device'] = $device;
      //$COOKIE['user'] = $row["id"];
      setcookie("device", $device);
      setcookie("user", $row["id"]);
      header("Location: loggedin.php");
    } else {
      $error = "User/Password Incorrect";
    }
  } else {
    $error = "User/Password Incorrect";
  }
  
}


?>
<!DOCTYPE html>
<html>

<head>
<title>Backgammon Login</title>
<!-- <link rel="stylesheet" href="style.css" type="text/css" /> -->
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.0a1/jquery.mobile-1.0a1.min.css" />
<script src="http://code.jquery.com/jquery-1.4.3.min.js"></script>
<script src="http://code.jquery.com/mobile/1.0a1/jquery.mobile-1.0a1.min.js"></script>
</head>
<body>
<div data-role="page">
<div data-role="header"><h1>Login</h1></div>
<div data-role="content" id="content">
<center>
<?php if(!isset($_REQUEST['create'])) { ?>
<form action="login.php" method="post">
<input type="submit" name="create" value="create an account" />
</form>
<br />
<?php } ?>

<form action="login.php" method="post">
<?php if(isset($error)) { 
  echo "<span class=\"error\">".$error."</span><br />";
  } ?>
username<br />
<input type="text" name="user" value="<?php echo $_POST["user"]; ?>"/><br />
password<br />
<input type="password" name="pass" value="<?php echo $_POST["pass"]; ?>"/><br />
<?php if(!isset($_REQUEST['create'])) { ?>
<input type="submit" name="login" value="login" /><br />
<?php } else { ?>
email<br />
<input type="text" name="email" value="<?php echo $_POST["email"]; ?>"/><br />
<?php } ?>

<?php if(isset($_REQUEST['create'])) { ?>
<input type="submit" name="create" value="create account" />
<?php } ?>

</form>
</center>
</div>
</div>
</body></html>
<html>
<head>
<script type="text/javascript">
window.HTMLOUT.setLogin("<?php echo $_COOKIE["user"]; ?>", "<?php echo $_COOKIE["device"]; ?>");
</script>
</head>
<body>
Yay! You logged in!<br />
Saving your credentials...<br />
<!-- <br /><a href="gamelist.php?user=<?php echo $_COOKIE["user"]; ?>&device=<?php echo $_COOKIE["device"]; ?>">gamelist</a>-->
<span style="display: none;"><?php echo $_COOKIE["user"].",".$_COOKIE["device"]; ?></span>
</body>
</html>
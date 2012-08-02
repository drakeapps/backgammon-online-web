<?php


function getTime($time) {
	if($time < 1000) {
		$time = time();
	}
	$cur = time();
	
	$diff = $cur - $time;
	
	$days = floor($diff / (60*60*24));
	$diff = $diff % (60*60*24);
	
	$hours = floor($diff / (60*60));
	$diff = $diff % (60*60);
	
	$minutes = floor($diff / 60);
	$diff = $diff % 60;
	
	
	$out = "";
	
	if($days > 0) {
		$out .= "$days day".( ($days == 1) ? "" : "s");
	}
	else if($hours > 0) {
		$out .= "$hours hour".( ($hours == 1) ? "" : "s");
	}
	else if($minutes > 0) {
		$out .= "$minutes minute".( ($minutes == 1) ? "" : "s");
	}
	else {
		$out .= "$diff second".( ($diff == 1) ? "" : "s");
	}
	
	$out .= " ago";
	
	return $out;
}

function clean($s) {
	global $mysql;
	return $mysql->real_escape_string($s);
}

function jquery() {
	/*return <<<EOF
		<link rel="stylesheet" href="http://code.jquery.com/mobile/1.0a2/jquery.mobile-1.0a2.min.css" />
		<script src="http://code.jquery.com/jquery-1.4.4.min.js"></script>
		<script src="http://code.jquery.com/mobile/1.0a2/jquery.mobile-1.0a2.min.js"></script>
EOF;*/
	return <<<EOF
		<link rel="stylesheet" href="http://code.jquery.com/mobile/1.0a1/jquery.mobile-1.0a1.min.css" /> 
		<script src="http://code.jquery.com/jquery-1.4.3.min.js"></script> 
		<script src="http://code.jquery.com/mobile/1.0a1/jquery.mobile-1.0a1.min.js"></script>
		
		<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-19257416-1']);
  _gaq.push(['_setDomainName', '.drakeapps.com']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
		
EOF;
		
}
<!DOCTYPE html>
<html>
<head>
	<title>It's All Good</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="robots" content="noindex" />
	<meta name="generator" content="<?php echo htmlentities($itsAllGood->product_version()); ?>"/>
	<?php /* The css is inline here for performance, it's nice when loading via mobile */ ?>
	<style type="text/css">
		body { 
			font-family: helvetica;
			font-size: 90%;
		}
		h2 {
			text-align: center;
		}

		th { 
			text-align: left; 
		}

		.success { 
			color: green; 
		}

		.fail { 
			color: red; 
		}

		table {
			margin: 0 auto;
		}

		table th,
		table td {
			vertical-align: top;
		}

		#uptime {
			margin: 15px 0;
			text-align: center;
		}

		#footer { 
			font-size: 9px; 
			border-top: 1px solid #ccc; 
			margin-top: 55px; 
			text-align: center; 
		}
	</style>
	<meta name="viewport" content="width=501" />
</head>
<body>
<?php

if($itsAllGood->allTestsPass){
        print "<h2 class=\"success\">Online And Operational</h2>";
} else {
        print "<h2 class=\"fail\">Service Failure</h2>";
}

print "<table id=\"stats\">\r\n";
print "\t<tr>\r\n";
print "\t\t<th>Check</th><th>Status</th><th>Value</th>\r\n";
print "\t</tr>\r\n";

foreach($itsAllGood->checkResults as $result){
	print "\t<tr>\r\n";
	print "\t\t<td>" . htmlentities($result['title']) . "</td>\r\n";
	print "\t\t<td>" . status($result['status']) . "</td>\r\n";
	print "\t\t<td>" . printData($result['values']) . "</td>\r\n";
	print "\t</tr>\r\n";
}
print "</table>\r\n";

// Just returns "up/down"
function status($status){
	if($status){
		return 'Up';
	}
	return '<span class="fail">Down</span>';
}

// Formats value for pretty printing
function printData($value){
	if(is_string($value)){
		return $value;
	}
	$str = '';

        foreach((array) $value['data'] as $key => $val){
		$str .= htmlentities($value['labels'][$key]).': '. htmlentities($val).'<br />';
	}
	return $str;
}

$utdata = shell_exec('uptime');
$uptime = explode(' up ', $utdata);
$uptime = explode(',', $uptime[1]);
$uptime = $uptime[0].', '.$uptime[1];

echo ('<div id="uptime">Uptime: '.$uptime.'</div>');

?>
<div id="footer">
	<p>Powered by <a href="http://code.google.com/p/itsallgood/">It's All Good</a>.</p>
</div>

<script type="text/javascript">
window.setTimeout(function(){
	window.location.href = window.location.href;
}, 60*1000);
</script>

<!-- End: <?php 
// benchmark timing
print (getmicrotime(microtime()) - getmicrotime($start));
// end benchmark timing	
?> -->
</body>
</html>

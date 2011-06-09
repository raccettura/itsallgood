<?php 
header("Content-Type: application/xml");
print '<?xml version="1.0" encoding="utf-8"?>'; 

?>
<ItsAllGood version="<?php echo htmlentities($itsAllGood->version); ?>" >
	<summary>
		<?php  
		if($itsAllGood->allTestsPass){
		        print "Online And Operational";
		} else {
		        print "Service Failure";
		}
		?>
	</summary>
	<tests>
		<?php
		foreach($itsAllGood->checkResults as $id => $result){
			print "\t<test id=\"".htmlentities($id)."\">\r\n";
			print "\t\t<name>" . htmlentities($result['title']) . "</name>\r\n";
			print "\t\t<status>" . status($result['status']) . "</status>\r\n";
			print "\t\t<values>" . printData($result['values']) . "</values>\r\n";
			print "\t</test>\r\n";
		}
		?>
	</tests>
	<?php
	
	$utdata = shell_exec('uptime');
	$uptime = explode(' up ', $utdata);
	$uptime = explode(',', $uptime[1]);
	$uptime = $uptime[0].', '.$uptime[1];
	
	print ("<hostUptime>".trim($uptime)."</hostUptime>\n");
	?>
</ItsAllGood>
<!-- Powered by It's All Good <http://code.google.com/p/itsallgood/> -->

<!-- End: <?php 
// benchmark timing
print (getmicrotime(microtime()) - getmicrotime($start));

// Just returns "up/down"
function status($status){
	if($status){
		return 'Up';
	}
	return 'Down';
}

// Formats value for pretty printing
function printData($value){
	if(is_string($value)){
		return $value;
	}
	$str = '';

    foreach((array) $value['data'] as $key => $val){
        $str .= '<value label="'.htmlentities($value['labels'][$key]).'">'. htmlentities($val).'</value>';
	}
	return $str;
}

?> -->

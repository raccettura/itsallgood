<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sun, 28 May 1999 05:00:00 GMT"); // Date in past

// benchmark timing
function getmicrotime($t) {
	list($usec, $sec) = explode(" ",$t);
	return ((float)$usec + (float)$sec);
}
$start = microtime();
// end start benchmark

require_once('lib/Log.class.php');
require_once('lib/ItsAllGood.class.php');
require_once('lib/CheckBase.class.php');

// If the config file isn't found, it means the install likely was never setup
if(file_exists('config.php')){
    require_once('config.php');
} else {
    die("Config file not found.");
}

$check = null;
if(isset($_REQUEST['check'])){
    $check = Array($_REQUEST['check']);
    if(strpos($_REQUEST['check'], ',') !== false){
        $check = explode(',', $_REQUEST['check']);
    }
}

$itsAllGood = new ItsAllGood($config, $check);

// If it's the pingdom bot, we'll just serve a message and stop, no need to send a full page.  Faster, better, cheaper.
if(strpos($_SERVER['HTTP_USER_AGENT'], 'Pingdom') !== false){
        if($itsAllGood->allTestsPass){
                print 'Online And Operational';
        } else {
                print 'Service Failure';
        }
        exit;
}

if(isset($_REQUEST['output']) && strtolower($_REQUEST['output']) == 'xml'){
	include('include/output.index.xml.inc.php');
} else {
	include('include/output.index.html.inc.php');
}
?>

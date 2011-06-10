<?php 
header("Content-Type: application/xml");
print '<?xml version="1.0" encoding="utf-8"?>'; 

?>
<ItsAllGood version="<?php echo htmlentities($itsAllGood->version); ?>" >
    <summary><?php  
        if($itsAllGood->allChecksPass){
                print "Online And Operational";
        } else {
                print "Service Failure";
        }
    ?></summary>
    <checks>
        <?php
        foreach($itsAllGood->checkResults as $id => $result){
            print "\t<check id=\"".htmlentities($id)."\">\r\n";
            print "\t\t<name>" . htmlentities($result['title']) . "</name>\r\n";
            print "\t\t<status>" . status($result['status']) . "</status>\r\n";
            print "\t\t<values>" . outputData($result['values']) . "</values>\r\n";
            print "\t</check>\r\n";
        }
        ?>
    </checks>
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
?> --><?php
/***************************************************
 * Utility Functions
 ****************************************************/

// Just returns "up/down"
function status($status){
    if($status){
        return 'Up';
    }
    return 'Down';
}

// Formats value for pretty printing
function outputData($value){
    $str = '';
    foreach((array) $value as $key => $val){
        // Nested <value/> nodes if we have multiple values
        if(sizeOf($val) > 1){
            $label = '';
            if(sizeOf($val) > 1){
                $label = ' label="'. htmlentities($val[0]). '"';
                $outStr = $val[1];
            } else {
                $outStr = $val;
            }
            $str .= '<value key="' . htmlentities($key) . '" ' . $label . '>'. htmlentities(valStringParser($outStr)).'</value>';

        // Otherwise no
        } else {
            $str .= htmlentities(valStringParser($val));
        }
    }
    return $str;
}

// Pretty prints nulls and booleans
function valStringParser($val){
    if(is_null($val)){
        return "Not Run";
    }
    else if(is_bool($val)){
        if($val || $val === 1){
            return "Success";
        } else{
            return "Fail";
        }
    }
    return $val;
}

?> 
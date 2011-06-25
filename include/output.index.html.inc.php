<!DOCTYPE html>
<html>
<head>
    <?php
        if($itsAllGood->allChecksPass){
                print "<title>It's All Good</title>";
        } else {
                print "<title>!!!!! It's All Good</title>";
        }
    ?>
    
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

        .success { 
            color: green; 
        }

        .fail { 
            color: red;
            font-weight: bold;
        }

        table {
            border-collapse: collapse;
            border-spacing: 0;
            line-height: 1.2em;
            margin: 0 auto;
        }

        tr:nth-child(2n) {
            background-color: #f8f8f8;
        }

        th,
        td {
            border: 1px solid #ddd;
            vertical-align: top;
        }
        
        th { 
            background-color: #d3e2e6;
            border-color: #fff;
            border-width: 0;
            color: #333;
            font-weight: bold;
            padding: 0.3em 1em;
            text-align: left; 
        }
        
        td {
            border: 1px solid #fff;
            color: #222;
            padding: 0.3em 0.6em;
        }
        
        td.status {
            text-align: center;
        }
        
        td.value span {
            color: #000;
        }

        #uptime {
            margin: 15px 0;
            text-align: center;
        }

        #footer { 
            border-top: 1px solid #ccc; 
            font-size: 9px; 
            margin: 6em auto 0 auto;
            text-align: center;
            width: 75%;
        }
    </style>
    <meta name="viewport" content="width=501" />
</head>
<body>
<?php

if($itsAllGood->allChecksPass){
        print "<h2 class=\"success\">Online And Operational</h2>\r\n";
} else {
        print "<h2 class=\"fail\">Service Failure</h2>\r\n";
}

print "<table id=\"stats\">\r\n";
print "\t<tr>\r\n";
print "\t\t<th>Check</th><th>Status</th><th>Value</th>\r\n";
print "\t</tr>\r\n";

foreach($itsAllGood->checkResults as $result){
    print "\t<tr>\r\n";
    print "\t\t<td class=\"check\">" . htmlentities($result['title']) . "</td>\r\n";
    print "\t\t" . status($result['status']) . "\r\n";
    print "\t\t<td class=\"value\">" . outputData($result['values']) . "</td>\r\n";
    print "\t</tr>\r\n";
}
print "</table>\r\n";

// Just returns "up/down"
function status($status){
    if($status){
        return '<td class="status success">Up</td>';
    }
    return '<td class="status fail">Down</td>';
}

// Formats value for pretty printing
function outputData($value){
    $str = '';
    foreach((array) $value as $key => $val){
        // Nested <value/> nodes if we have multiple values
        if(sizeOf($val) > 1){
            $label = '';
            if(sizeOf($val) > 1){
                $str .= '<span>'.htmlentities($val[0]) . ':</span> ' . htmlentities(valStringParser($val[1])) . "<br />";
            } else {
                $str .= htmlentities($val);
            }

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


if($config['showUptime']){

    $utdata = shell_exec('uptime');
    $uptime = explode(' up ', $utdata);
    $uptime = explode(',', $uptime[1]);
    $uptime = $uptime[0].', '.$uptime[1];

    echo ('<div id="uptime"><strong>Uptime:</strong> '.$uptime.'</div>');
}


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

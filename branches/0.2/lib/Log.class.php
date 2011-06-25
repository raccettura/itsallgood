<?php

class Logging{

    public function __construct($log, $verbosity = 1){
        if(is_writable($log)){
            $this->logFile = fopen($log, "a+");
            $this->verbosity = $verbosity;
        }
    }

    public function log($message, $verbosity =  1, $environment = null){
        if(!isset($this->logFile)){ 
            return false;
        }
        if(!$environment){
            $environment = "unknown";
        }

        $message = str_replace(array("\r", "\n"), " ", $message);

        if($verbosity <= $this->verbosity){
            $messageString = date(DATE_RFC822) . "\t" . $environment . "\t" . $this->logLevel($verbosity) . "\t" . $message. "\n";
            fwrite($this->logFile, $messageString);
        }
    }

    private function logLevel($level){
        switch($level){
            case 1:
                return "Info";
            case 2:
                return "Detail";
            case 3:
                return "Debug";
            default:
                return "Unknown";
        }
        // Huh?
        return "Unknown";
    }

    public function close() {
        if($this->logFile){
           fclose($this->logFile);
        }
   }
}
?>

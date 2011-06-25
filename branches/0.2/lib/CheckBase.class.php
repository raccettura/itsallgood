<?php

/* all methods in this base class should be prefixed with '_' */
class CheckBase {

    protected $config = array();

    public function __construct($config, &$logRef){
        $this->config = array_merge($this->config, $config);
        $this->logRef = $logRef;
    }

    protected function _log($message, $verbosity = 1){
        // We pass the name of the class in as the environment
        $this->logRef->log($message, $verbosity, get_class($this));
    }
}

?>

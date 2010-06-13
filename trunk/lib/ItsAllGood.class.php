<?php

class ItsAllGood {

    private $config;
    private $checks = Array();
    private $modules = Array();
    public $allTestsPass = null; // All tests passed boolean
    private $moduleDir = "./modules/"; // Where modules can be found
    private $selfName = "Core"; // What the log's env will read for self

    public function __construct($config){
        $this->config = $config;
	$this->log = new Logging($this->config['logFile'], 2);
        $this->iterate_checks();
        $this->log->close();
    }

    private function get_checks(){
        return $this->config['checks'];
   }

    private function iterate_checks(){
        $this->checks = $this->get_checks();
        $this->checkResults = Array();
        $this->allTestsPass = true;
        foreach($this->checks as $check){
            $results = $this->run_check($check);
            $this->checkResults[] = $results;
            $this->log->log($results['title'] . " (" . $check['type'] . "): " . $results['status'] . ": " . print_r($results['values'],true), 1, $this->selfName);
            if(!$results['status']){
                $this->allTestsPass = false;
            }
        }
    }

    private function run_check($check){
        if(!$this->is_check_module_installed($check['type'])){
            $this->log->log('Error: Check module not installed: '.$check['type'], 1, $this->selfName);
            return false;
        }

        $check_module = $this->load_check_module($check['type'], $check['config']);

        $this->results = Array();

        if(method_exists($check_module, "about")){
            $results['about'] = $check_module->about();
        }

        if(isset($check['label'])){
            $results['title'] = $check['label'];
        }
        else if(isset($results['about']['title'])){
            $results['title'] = $results['about']['title'];
        }

        $results['status']  = $check_module->check();
        $results['values'] = $check_module->get_values();
        return $results;
    }

    private function load_check_module($type, $config){
        include_once($this->moduleDir.$type . ".php");
        $class_name = 'Check_'.$type;
        if(!class_exists($class_name)){
            $this->log->log("Error: Failed loading check module: " . $type, 1, $this->selfName);
            return false;
        }
        return new $class_name($config, $this->log);
    }

    private function is_check_module_installed($module){
        $modules = $this->list_installed_modules();
        return in_array($module, $modules);
    }

    public function module_filter($value){
        // This is just the filesystem
        if($value == "." || $value == ".."){
            return false;
        }

        // Must be end in .php
        if(!substr($value, strlen($value)-4)){
            return false;
        }
        return true;
    }

    public function module_cleaner($value){
        return substr($value, 0, strlen($value)-4);
    }

    private function list_installed_modules(){
        if(sizeOf($this->modules) == 0){
            $contents = scandir($this->moduleDir);
            $this->modules = array_filter($contents, array($this, "module_filter"));
            $this->modules = array_map(array($this, "module_cleaner"), $this->modules);
        }
        return $this->modules;
    }
}

?>

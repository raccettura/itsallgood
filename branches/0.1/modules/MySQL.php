<?php

class Check_MySQL extends CheckBase {

    public function about(){
        $about = array(
            'title' => 'MySQL Check',
            'author' => 'Robert Accettura',
            'url' => 'http://code.google.com/p/itsallgood/',
            'description' => 'Checks that the MySQL server is up and operational.',
        );
        return $about;
    }

    protected $config = array(
        'method' => 'socket',
        'server' => 'localhost',
        'port'    => '3308',
        'username' => 'root',
        'password' => '',
        'db' => 'test'
    );

    public function check(){
        if(!class_exists('MySQLi')){
            $this->error = 'Error: mysqli extension is unavailable';
            return false;
        }
        if($this->config['server']){
             $connection = new mysqli($this->config['server'], $this->config['username'], $this->config['password'], $this->config['db'], $this->config['port']);
        } else {
             $connection = new mysqli(localhost, $this->config['username'], $this->config['password'], $this->config['db'], null, $this->config['socket']);
        }
        if ($connection->connect_error) {
            $this->error = 'Error:' . $connection->connect_errno . ': '. $connection->connect_error;
	    return false;
        }
        else if (mysqli_connect_error()) {
            $this->error = 'Error:' . mysqli_connect_errno() . ': '. mysqli_connect_error();
            return false;
        }
        $this->error = 'Success';
        return true;
    }

    public function get_values(){
        if(!isset($this->error)){
            $this->check();
        }
        return $this->error;
    }
}
?>

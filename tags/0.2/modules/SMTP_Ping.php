<?php

class Check_SMTP_Ping extends CheckBase {

    public $about = array(
        'title' => 'SMTP Ping',
        'author' => 'Robert Accettura',
        'url' => 'http://code.google.com/p/itsallgood/',
        'description' => 'Checks that the SMTP server is up',
    );

    protected $config = array(
        'hostname' => 'localhost',
        'port' => 25,
        'timeout' => 30
    );

    public function check(){
        $this->connection = $this->openConnection($this->config['hostname'], $this->config['port'], $this->config['timeout']);
        $this->closeConnection();
        if(!$this->connection){
            return false;
        }
        return true;
    }

    public function get_values(){
        if(!isset($this->connection)){
            $this->check();
        }
        if($this->response == ''){  $this->response = 'Success'; }
        return $this->response;
    }

    private function openConnection($host, $port, $timeout=30){
        $errno = 0;
        $errstr = 0;
        $this->_log('Opening SMTP Connection to: '. $host, 2);
        if(!$this->handle = @fsockopen($host, $port, $errno, $errstr, $timeout)){
            $this->response = 'Connection Failed';
            $this->_log('SMTP Connection to: '. $host . ' is down', 2);
            return false;
        }
        $response = fgets($this->handle, 2);
        $bytesLeft = socket_get_status($this->handle);
        if($bytesLeft['unread_bytes'] > 0) { 
            $response .= fread($this->handle, $bytesLeft['unread_bytes']); 
        }
        $this->response = $response;
        $this->_log('SMTP Response: '. $response, 2);
        return true;
    }

    private function closeConnection(){
        @fclose($this->handle);
    }
}
?>

<?php
class Model{
    public $instance;
    public $conn;
    public $token;
    
    function __construct(){
    $this->instance = ConnectDb::getInstance();
    $this->conn = $this->instance->getConnection();
    $headers = apache_request_headers();
    $this->token = substr($headers['Authorization'],7);
    }

    public function findUserByToken(){
        $sql = $this->conn->prepare("select userName from Users where authToken = ?");
        $sql->execute(array($this->token));
        $userName = $sql->fetchAll();
        return $userName[0]['userName'];
    }
    
    static function isTokenPresent($token){
        if ($token == '' || $token == null) {
            echo 'there is no Authorization';
            return false;
        } 
        else return true;
    } 
}
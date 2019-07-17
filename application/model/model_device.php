<?php
class Model_Device extends Model{

    function __construct(){
        $this->instance = ConnectDb::getInstance();
        $this->conn = $this->instance->getConnection();
        $headers = apache_request_headers();
        $this->token = substr($headers['Authorization'],7);

        if ($this->token == '' || $this->token == null) {
            echo 'there is no Authorization';
            die();
        }
    }

    function deviceGetData($deviceName){
        $deviceName = urldecode($deviceName);
        $userName = $this->findUserByToken();
        $sql = $this->conn->prepare("SELECT dataJson from devices where userName = ? and deviceName = ?");
        $sql->execute([$userName,$deviceName]);
        $sql = $sql->fetchAll();
        echo $sql[0]['dataJson'];
    }

    function deviceNew($post){
        $userName = $this->findUserByToken();

        $params;
        foreach ($post['parameters'] as $key) {
            $params[$key] = [];
        }
        $params = json_encode($params);
        $dataJson = $params;
        $deviceName = $post['name'];
        $UUID = uniqid();

        $sql = $this->conn->prepare("INSERT into devices (UUID, deviceName, userName, dataJson) VALUES (?,?,?,?)");
        $sql->execute([$UUID,$deviceName,$userName,$dataJson]);
    }

    function deviceDelete($put){
        $deviceName = $put['name'];
        $userName = $this->findUserByToken();
        $sql = $this->conn->prepare("DELETE from devices where deviceName = ? and userName = ?");
        $sql->execute(array($deviceName,$userName));
    }

    function devicePutData($put){
        $userName = $this->findUserByToken();
        $deviceName = $put['name'];

        $sql = $this->conn->prepare('SELECT dataJson from 
        devices where userName = ? and deviceName = ?');
        $sql->execute(array($userName,$deviceName));
        $sql = $sql->fetchAll();

        $json = json_decode($sql[0]['dataJson'],true);
        $paramKeys = array_keys($json);
        foreach ($paramKeys as $key => $value) {
            array_push($json[$value],$put[$value]);
        }
        $json = json_encode($json);

        $sql = $this->conn->prepare("UPDATE devices set dataJson = ? 
        where deviceName = ? and userName = ?");
        $sql->execute(array($json,$deviceName,$userName));
    }

    function deleteParameter($put){
        $userName = $this->findUserByToken();
        $deviceName = $put['name'];
        $parameters = $put['parameters'];

        $sql = $this->conn->prepare('SELECT dataJson from 
        devices where userName = ? and deviceName = ?');
        $sql->execute(array($userName,$deviceName));
        $sql = $sql->fetchAll();

        $json = json_decode($sql[0]['dataJson'],true);

        foreach ($parameters as $key) {
            if (array_key_exists($key,$json)){
                $index = array_search($key,array_keys($json));
                array_splice($json,$index,1);
            }
        }
        $json = json_encode($json);

        $sql = $this->conn->prepare("UPDATE devices set dataJson = ? 
        where deviceName = ? and userName = ?");
        $sql->execute(array($json,$deviceName,$userName));
    }

    function addParameter($put){
        $userName = $this->findUserByToken();
        $deviceName = $put['name'];
        $parameters = $put['parameters'];

        $sql = $this->conn->prepare('SELECT dataJson from 
        devices where userName = ? and deviceName = ?');
        $sql->execute(array($userName,$deviceName));
        $sql = $sql->fetchAll();

        $json = json_decode($sql[0]['dataJson'],true);

        foreach ($parameters as $key) {
            if (!array_key_exists($key,$json)){
                $json[$key]=[null];
            }
        }
        $json = json_encode($json);

        $sql = $this->conn->prepare("UPDATE devices set dataJson = ? 
        where deviceName = ? and userName = ?");
        $sql->execute(array($json,$deviceName,$userName));
    }
}
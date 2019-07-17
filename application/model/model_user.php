<?php
class Model_User extends Model{

    function registerNewUser($post){
        $sql = $this->conn->prepare('insert into users (userName,passHash) values (?,?);');

        $post = json_decode($post,true);
        $userName = $post['name'];
        $passHash = password_hash($post['pass'],PASSWORD_BCRYPT);

        try {
            $sql->execute(array($userName,$passHash));
        } catch (PDOException $e) {
            echo $e->getMessage();
           die();
        }
    }

    function userLogin($post){
        $post = json_decode($post,true);
        $user = $post['name'];
        $password = $post['pass'];

        $sql = $this->conn->prepare('select userName,passHash from users where userName = ?;');
        try {
            $sql->execute(array($user));
        } catch (PDOException $e) {
            echo $e->getMessage();
           die();
        }
        $sql = $sql->fetchAll();
        if($sql == null || $sql == ""){die();}
        $passHash = $sql[0]['passHash'];
        $isCorrectPass = password_verify($password,$passHash); 
        if(!$isCorrectPass){
            die();
        }
        $sql = $this->conn->prepare('update users set authToken = ? where users.userName = ?');
        $authToken = md5(uniqid($user));
        try {
            $sql->execute(array($authToken,$user));
        } catch (PDOException $e) {
            echo $e->getMessage();
           die();
        }
        return $authToken;
    }

    function get_devices($user){
        if(!Model::isTokenPresent($this->token)){
            die();
        }
        $sql = $this->conn->prepare("select deviceName,dataJson from devices where userName = (select userName from users where
        authToken = ?)");
        $sql->execute(array($this->token));
        $sql = $sql->fetchAll();
        return json_encode($sql);
    }
}
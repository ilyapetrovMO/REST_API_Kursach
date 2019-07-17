<?php
class Controller_User extends Controller{ 

    function __construct(){
        $this->model = new Model_User();
        $this->post = file_get_contents("php://input");
    }
    function action_register(){
        $data = $this->model->registerNewUser($this->post);
    }
    function action_login(){
        $token = $this->model->userLogin($this->post);
        echo $token;
    }
    function action_devices($userName){
        $devices = $this->model->get_devices($userName);
        echo $devices;
    }
}
<?php
class Controller_Device extends Controller{

    function __construct(){
        $this->model = new Model_Device();
        $this->post = file_get_contents("php://input");
        $this->post = json_decode($this->post,true);
    }
    
    function action_new(){
        $data = $this->model->deviceNew($this->post);
    }
    
    function action_delete(){
        $result = $this->model->deviceDelete($this->post);
    }

    function action_putdata(){
        $this->model->devicePutData($this->post);
    }
    
    function action_getdata($deviceName){
        $data = $this->model->deviceGetData($deviceName);
        echo $data;
    }

    function action_deleteparameter(){
        $this->model->deleteParameter($this->post);
    }

    function action_addparameter(){
        $this->model->addParameter($this->post);
    }
}
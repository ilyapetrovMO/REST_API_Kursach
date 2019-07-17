<?php
class Controller{
    public $model;
    public $post;

    function __construct(){
        $this->post = file_get_contents("php://input");
    }
}
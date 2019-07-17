<?php
class Route{
    function start()
    {
        $controller_name = "test";
        $action_name;

        $route = explode('/',$_SERVER['REQUEST_URI']);
        if(!empty($route[1])){
            $controller_name = $route[1];
        }
        if(!empty($route[2])){
            $action_name = $route[2];
        } else {
            Error404();
            die();
        }

        $model_name = 'model_'.$controller_name;
        $controller_name = 'controller_'.$controller_name;
        $action_name = 'action_'.$action_name;

        $model_file = $model_name.'.php';
        if(file_exists('application/model/'.$model_file)){
            include 'application/model/'.$model_file;
        }

        $controller_file = $controller_name.'.php';
        if (file_exists('application/controller/'.$controller_file)){
            include 'application/controller/'.$controller_file;
        }
        else {
            Route::Fuck();
        }

        $controller = new $controller_name;
        $action = $action_name;
        if(method_exists($controller,$action)){
            Route::httpSwitch($controller,$action,$route);
        }
        else{
            Route::Fuck();
        }
    }

    function httpSwitch($controller,$action,$route){
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $controller->$action($route[3]);
                break;

            default:
                $controller->$action();
                break;
        }
    }

    function Fuck(){
        echo "<h1>FUCK</h1>";
    }
    function Error404(){
        $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
        header ('HTTP/1.1 404 Not Found');
        header ('Status: 404 Not Found');
        header ('Host:'.$host);
        
    }
}
<?php
    session_start();
    require_once ("vendor/autoload.php");
    if(isset($_GET['controller'])){
        $controller = $_GET['controller'];
        if(isset($_GET['action'])){
            $action = $_GET['action'];
        }else{
            $action = 'index';
        }
    }else{
        $controller = 'home';
        $action = 'index';
    }

    $controller = ucfirst($controller) . 'Controller';
    if(!class_exists($controller)){
        $controller = 'HomeController';
        $action = 'error';
    }

    $obj = new $controller();
    if(!method_exists($obj,$action)){
        $obj = new HomeController();
        $action = 'error';
    }
    $obj->$action();
?>
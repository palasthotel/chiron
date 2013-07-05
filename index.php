<?php

include ("core/classes/bootstrap.php");

$path=$_GET['path'];
$path = strip_tags($path);
$args=explode('/', $path);
$class=$args[0];
$action=$args[1];

// Set default Class
if($class == ""){ $class = "public"; }
// Set default Action
if($action == ""){ $action = "homepage"; }

$class=$class."Controller";

$controller=new $class();
$method="action".$action;
$controller->$method($args);

?>

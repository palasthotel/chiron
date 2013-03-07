<?php

include ("core/classes/bootstrap.php");

$path=$_GET['path'];
$pathelems=explode('/', $path);
$class=$pathelems[0];
$action=$pathelems[1];

$class=$class."Controller";

$controller=new $class();
$method="action".$action;
$controller->$method();

?>

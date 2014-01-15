<?php

// This File contains the non-CMS Version of Chiron.

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

$class = $class."_controller";
$controller = new $class();
$method = "action_".$action;
$output = $controller->$method($args);
include("core/templates/header.tpl.php");
print ($output);
include("core/templates/footer.tpl.php");
?>

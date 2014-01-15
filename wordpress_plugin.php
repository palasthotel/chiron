<?php
/**
* Plugin Name: Chiron
* Description: The Teacher of Heros
* Version: 0.1
* Author: Palasthotel (in Person: Benjamin Birkenhake, Enno Welbers)
* Author URI: http://www.palasthotel.de
*/

require('core/classes/bootstrap.php');
require('drupal7/chiron.install');

function t($str){
	return $str;
}

function db_query($querystring){
	global $wpdb;
	$querystring = str_replace("{", $wpdb->prefix, $querystring);
	$querystring = str_replace("}", "", $querystring);
    global $chiron_connection;
    $result = $$chiron_connection->query($querystring) or die($querystring." failed: ".$chiron_connection->error);
    return $result;
}


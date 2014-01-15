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
    $result = $chiron_connection->query($querystring) or die($querystring." failed: ".$chiron_connection->error);
    return $result;
}

function chiron_wp_activate(){
	
	static $secondCall=FALSE;
	global $wpdb;
	global $chiron_connection;
	$options=get_option("chiron",array());
	if(!isset($options['installed'])){
		// Run the Installation
		$schema = chiron_schema();
		$chiron_connection=new mysqli(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
		
		// Create a Query for each Table within the Schema Definition
		foreach($schema as $tablename=>$data){
			$query="create table ".$wpdb->prefix."$tablename (";
			$first=TRUE;
			// Add each Field to the Query
			foreach($data['fields'] as $fieldname=>$fielddata){
				if(!$first){
					$query .= ",";
				}else{
					$first = FALSE;
				}
		            
				$query.="$fieldname ";
				// Check the Field-Types
				if($fielddata['type']=='int'){
					$query.="int ";
				}elseif($fielddata['type']=='text'){
					$query.="text ";
				}elseif($fielddata['type']=='serial'){
					$query.="int ";
				}elseif($fielddata['type']=='varchar'){
					$query.="varchar(".$fielddata['length'].") ";
				}else{
					die("unknown type ".$fielddata['type']);
				}

				if(isset($fielddata['unsigned']) && $fielddata['unsigned']){
					$query.=" unsigned";
				}
				
				if(isset($fielddata['not null']) && $fielddata['not null']){
					$query.=" not null";
				}

				if($fielddata['type']=='serial'){
					$query.=" auto_increment";
				}
			}
			$query.=",constraint primary key (".implode(",", $data['primary key']).")";
			$query.=") ";
			$query.="ENGINE = ".$data['mysql_engine'];
			$chiron_connection->query($query) or die($chiron_connection->error." ".$query);
		}
		
		// Tell Wordpress that we have installed
		$options['installed']=TRUE;
		update_option("chiron",$options);
		
	}else{		
		//TODO: implement update support
	}	
}

register_activation_hook(__FILE__, "chiron_wp_activate");


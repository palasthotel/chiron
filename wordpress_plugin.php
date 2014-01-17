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

function chiron_wp_admin_menu()
{        
        add_menu_page('Reader','Chiron','read','chiron_dashboard','chiron_wp_dashboard', 'dashicons-book-alt', '76');
		add_submenu_page('chiron_dashboard', 'News-Dashboard', 'News-Dashboard', 'read', 'chiron_dashboard', 'chiron_wp_dashboard' );
		add_submenu_page('chiron_dashboard', 'Manage Sources', 'Manage Sources', 'read', 'chiron_manage_sources', 'chiron_wp_manage_sources' );
		add_submenu_page('chiron_dashboard', 'Manage Categories', 'Manage Categories', 'read', 'chiron_manage_categories', 'chiron_wp_manage_categories' );
		add_submenu_page('chiron_dashboard', 'Settings', 'Settings', 'read', 'chiron_manage_subscriptions', 'chiron_wp_settings' );
}

add_action("admin_menu","chiron_wp_admin_menu");

function chiron_wp_dashboard(){
	print "<div class='wrap'>";
	print "<h2>Welcome to your News-Dashboard, young Hero or Heroine!</h2>";
	print "</div>";
}


function chiron_wp_manage_sources(){
	global $chiron;
	print "<div class='wrap'>";
	print "<h2>Sources <a class='add-new-h2' href='#'>Add New</a></h2>";
	print "<p>Manage your Sources, young Hero or Heroine!</p>";
	$no = $chiron->sources_get_all();
	print "<p>You have ".$no." Sources of Information.</p>";
	if($no>0){
		print "<table class='wp-list-table widefat'>";
		print '<thead>';
		print '<tr>';
		print '<th>Titel</th>';
		print '<th>URL</th>';
		print '</tr>';
		print '</thead>';
		$oddoreven = "odd";
		foreach($chiron->sources as $source){
			$rowclasses = array();
			if($oddoreven == "odd"){
				$rowclasses[] = "alternate";
			}
			$classes = implode(" ", $rowclasses);
			print "<tr class='".$classes."'>";
			print "<td>".$source['title']."</td>";
			print "<td>".$source['url']."</td>";
			print "</tr>";
			if($oddoreven == "odd"){
				$oddoreven = "even";
			}else{
				$oddoreven = "odd";
			}
		}
		print '<tfoot>';
		print '<tr>';
		print '<th>Titel</th>';
		print '<th>URL</th>';
		print '</tr>';
		print '</tfoot>';
		print "</table>";
	}
	print "</div>";
}



function chiron_wp_settings(){
	print "<div class='wrap'>";
	print "<h2>Chiron Settings</h2>";
	print "<p>Manage your Settings, young Hero or Heroine!</p>";
	print "</div>";
}


function chiron_wp_manage_categories(){
	print "<div class='wrap'>";
	print "<h2>Categories of your Sources <a class='add-new-h2' href='#'>Add New</a></h2>";
	print "<p>Manage the Categoreis of your Sources, young Hero or Heroine!</p>";
	print "</div>";
}


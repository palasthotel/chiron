<?php
/**
* Plugin Name: Chiron
* Description: The Teacher of Heros
* Version: 0.1
* Author: Palasthotel (in Person: Benjamin Birkenhake, Enno Welbers)
* Author URI: http://www.palasthotel.de
*/

// First, like in all Implementations, get the Database Credentials
global $table_prefix;

define(CHIRON_DB_SRV,DB_HOST);
define(CHIRON_DB_USR,DB_USER);
define(CHIRON_DB_PWD,DB_PASSWORD);
define(CHIRON_DB_DBS,DB_NAME);
define(CHIRON_DB_PRE, $table_prefix);

// Then bootstrap Chiron.
// This loads all Classes and creates Instances of the classes chiron_db and chiron_core.
require('core/classes/bootstrap.php');

function t($str){
	return $str;
}

function db_query($querystring){
	global $chiron_db;
	$querystring = str_replace("{", $wpdb->prefix, $querystring);
	$querystring = str_replace("}", "", $querystring);

    $result = $chiron_db->query($querystring) or die($querystring." failed: ".$chiron_db->error);
    return $result;
}

function chiron_wp_activate(){
	
	static $secondCall=FALSE;
	$chiron_db = new chiron_db(CHIRON_DB_SRV, CHIRON_DB_USR, CHIRON_DB_PWD, CHIRON_DB_DBS, CHIRON_DB_PRE);
	
	$options=get_option("chiron",array());
	if(!isset($options['installed'])){
		// Run the Installation
		// 1. Create the Databases based on the Schema
		$chiron_db->execute_schema();
			
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
		// Main Backend Menu Item
        add_menu_page('Reader','Chiron','read','chiron_dashboard','chiron_wp_dashboard', 'dashicons-book-alt', '76');
		// Submenu Items
		add_submenu_page('chiron_dashboard', 'News-Dashboard', 'News-Dashboard', 'read', 'chiron_dashboard', 'chiron_wp_dashboard' );
		add_submenu_page('chiron_dashboard', 'Manage Sources', 'Manage Sources', 'read', 'chiron_manage_sources', 'chiron_wp_manage_sources' );
		add_submenu_page('chiron_dashboard', 'Add new Source', 'Add new Source', 'read', 'chiron_add_source', 'chiron_wp_add_source' );
		add_submenu_page('chiron_dashboard', 'Refresh Sources', 'Refresh Sources', 'read', 'chiron_refresh_sources', 'chiron_wp_refresh_sources' );
		add_submenu_page('chiron_dashboard', 'Manage Categories', 'Manage Categories', 'read', 'chiron_manage_categories', 'chiron_wp_manage_categories' );
		add_submenu_page('chiron_dashboard', 'Add new Category', 'Add new Category', 'read', 'chiron_add_category', 'chiron_wp_add_category' );
		add_submenu_page('chiron_dashboard', 'Settings', 'Settings', 'read', 'chiron_manage_subscriptions', 'chiron_wp_settings' );
		add_submenu_page('chiron_dashboard', 'Debugging', 'Debugging', 'read', 'chiron_debugging', 'chiron_wp_debug' );
		// Functions which need not be available via Wordpress' Admin Menu
		add_submenu_page('null', 'Edit Source', 'Edit Source', 'read', 'chiron_edit_source', 'chiron_wp_edit_source' );
		add_submenu_page('null', 'Refresh Source', 'Refresh Source', 'read', 'chiron_refresh_source', 'chiron_wp_refresh_source' );
		add_submenu_page('null', 'Edit Category', 'Edit Category', 'read', 'chiron_edit_category', 'chiron_wp_edit_category' );
}

add_action("admin_menu","chiron_wp_admin_menu");

function chiron_wp_debug(){
	print "<div class='wrap'>";
	print "<h2>Debugging Chiron</h2>";
	echo '<pre>'; 
	print_r(wp_get_schedules()); 
	echo'</pre>';
	print "</div> <!-- // .wrap -->";
}

function chiron_wp_settings(){
	print "<div class='wrap'>";
	print "<h2>Chiron Settings</h2>";
	print "<p>Manage your Settings, young Hero or Heroine!</p>";
	print "</div> <!-- // .wrap -->";
}


function chiron_wp_dashboard(){
	global $chiron;
	print "<div class='wrap'>";
	print "<h2>Welcome to your News-Dashboard, young Hero or Heroine!</h2>";
	$sources_count = $chiron->sources_count();
	$items_count = $chiron->items_count();
	print "<p><strong>".$items_count[0]." items</strong> from <strong>".$sources_count[0]." sources</strong> are waiting to be read by you.</p>";
	
	if($_GET["day"]!=""){
	    if($_GET["day"]== date("Y-m-d",time())){
	      $date = date("Y-m-d", time()-60*60*24);
	    }else{
	      $date = $_GET["day"];
	    }
	  }else{
	    $date = date("Y-m-d", time()-60*60*24);
	  }
	  $timestamp = strtotime($date);
	
	print('<div class="tablenav">');    
	print('<div class="tablenav-pages">');    
	$yesterday = date("Y-m-d", $timestamp - 60*60*24);
	$tomorrow = date("Y-m-d", $timestamp + (60*60*24));
	print '<div class="tablenav-pages">';
	print " <a class='prev-page' href='?page=chiron_dashboard&day=".$yesterday."'>‹</a> ";
	print '<span class="paging-input">'."News of ".	date("l", $timestamp)." the ".date("j. F Y", $timestamp)."</span>";
	if($tomorrow != date("Y-m-d", time())){
	    print " <a class='next-page' href='?page=chiron_dashboard&day=".$tomorrow."'>›</a>";
	}
	print ("</div>");
	print ("</div>");
	print ("</div>");
	
	$day = date("Y-m-d", $timestamp);
	$chiron->sources_get_all();
	$chiron->items_get_by_day($day);
	if(count($chiron->items)>0){
		print "<table class='wp-list-table widefat'>";
		print '<thead>';
		print '<tr>';
		print '<th>Source</th>';
		print '<th>Title</th>';
		print '</tr>';
		print '</thead>';
		$oddoreven = "odd";
		foreach($chiron->items as $item){

			$rowclasses = array();
			if($oddoreven == "odd"){
				$rowclasses[] = "alternate";
			}
			$classes = implode(" ", $rowclasses);
			print "<tr class='".$classes."'>";
			print "<td>".$chiron->sources[$item->source]['title']."</td>";
			print "<td><a href='".$item->url."'>".$item->title."</a></td>";
			print "</tr>";
			if($oddoreven == "odd"){
				$oddoreven = "even";
			}else{
				$oddoreven = "odd";
			}
		}
		//print_r($chiron->sources);
		//			print_r($item);
		print '<tfoot>';
		print '<tr>';
		print '<th>Source</th>';
		print '<th>Title</th>';		
		print '</tr>';
		print '</tfoot>';
		print "</table>";
	}
	print "<p>".count($chiron->items)." yeasterdays news.</p>";
	print "</div>";
}


// Sources from here on

function chiron_wp_manage_sources(){
	global $chiron;
	print "<div class='wrap'>";
	print "<h2>Sources <a class='add-new-h2' href='?page=chiron_add_source'>Add New</a></h2>";
	print "<p>Manage your Sources, young Hero or Heroine!</p>";
	$no = $chiron->sources_get_all();
	print "<p>You have ".$no." magnificent Sources of Information.</p>";
	if($no>0){
		print "<table class='wp-list-table widefat'>";
		print '<thead>';
		print '<tr>';
		print '<th>Titel</th>';
		print '<th>URL</th>';
		print '<th>Last Checked</th>';
		print '<th>Status</th>';
		print "<th>Operations</th>";
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
			if($source['lastchecked']>0){
				$date = date("d. m. Y H:i:s", $source['lastchecked'] );
			}else{
				$date = "never";
			}
			print "<td>".$date."</td>";
			print "<td>".$source['status']."</td>";
			print "<td><a href='?page=chiron_edit_source&source_id=".$source['id']."'>edit</a> | <a href='?page=chiron_refresh_source&source_id=".$source['id']."'>refresh</a></td>";
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
		print '<th>Last Checked</th>';
		print '<th>Status</th>';
		print "<th>Operations</th>";
		print '</tr>';
		print '</tfoot>';
		print "</table>";
	}
	print "</div> <!-- // .wrap -->";
}


function chiron_wp_add_source(){
	global $chiron;
	print "<div class='wrap'>";
	print "<h2>Add a new Source</h2>";
	
	if(isset($_POST) && !empty($_POST)){
		$source = new chiron_source();
		$source->title = $_POST['title'];
		$source->url = $_POST['url'];
		$result = $source->add();
		if($result == "1"){
			print '<div id="message" class="updated below-h2"><p>Source added successfully.</p></div>';
		}
	}
	
	print "<div class='form-wrap'>";
	print '<form method="post">';
	print '<div class="form-field form-required"><label>URL of your new Source</label><input type="text" name="url" /><p>The URL under which your Source is awailable.</p></div>';
	print '<div class="form-field form-required"><label>Title of your new Source</label><input type="text" name="title"><p>If you leave it blank, the Title will be created from the Source itself. You may edit it later on.</p></div>';
	print '<input type="submit">';
	print '</form>';
	print "</div> <!-- // .form-wrap -->";	
	print "</div> <!-- // .wrap -->";
}

function chiron_wp_edit_source(){
	global $chiron;
	print "<div class='wrap'>";
	print "<h2>Edit Source</h2>";
	
	if(isset($_POST) && !empty($_POST)){
		$source = new chiron_source();
		$source->id = $_GET['source_id'];
		$source->title = $_POST['title'];
		$source->url = $_POST['url'];
		$result = $source->update();
		if($result == "1"){
			print '<div id="message" class="updated below-h2"><p>Source updated successfully.</p></div>';
		}else{
			print '<div id="message" class="updated below-h2"><p>Source <strong>not</strong> updated.</p></div>';
		}
	}
	
	if(isset($_GET['source_id']) && !empty($_GET['source_id'])){
		$source_id = $_GET['source_id'];
		$source = new chiron_source($source_id);
		print "<div class='form-wrap'>";
		print '<form method="post">';
		print '<div class="form-field form-required"><label>New URL of your Source</label><input type="text" name="url" value="'.$source->url.'"/><p>The URL under which your Source is awailable.</p></div>';
		print '<div class="form-field form-required"><label>New Title of your Source</label><input type="text" name="title" value="'.$source->title.'"><p>If you leave it blank, the Title will be created from the Source itself. You may edit it later on.</p></div>';
		print '<input type="submit">';
		print '</form>';
		print "</div> <!-- // .form-wrap -->";
		
	}else{
		print "<p>No Source selected for editing.</p>";
	}
	print "</div> <!-- // .wrap -->";
}


function chiron_wp_refresh_sources(){
	global $chiron;
	print "<div class='wrap'>";
	print "<h2>Refreshing some Sources</h2>";
	$sources = $chiron->sources_run_cron();
	foreach($sources as $source){
		print "<p>Updating Source <strong>".$source->title."</strong> … added ".$source->lastadded." items.";
		if($source->error!=""){
			print "<br/>Error:</strong> ".$source->error."";
		}
		print "</p>";
	}
	
	print '<form method="post"><input  class="button button-primary" type="submit" value="Refresh some more Sources" ></form>';

	
	print "</div> <!-- // .wrap -->";
}


function chiron_wp_refresh_source(){
	print "<div class='wrap'>";
	print "<h2>Refresh Source</h2>";
	if(isset($_GET['source_id']) && !empty($_GET['source_id'])){
		$source_id = $_GET['source_id'];
		$source = new chiron_source($source_id);
		$source->refresh();
		print "<p>Updating Source <strong>".$source->title."</strong> … added ".$source->lastadded." items.";
		if($source->error!=""){
			print "<br/>Error:</strong> ".$source->error."";
		}
		print "</p>";
		
	}else{
		print "<p>No Source selected for refreshing.</p>";
	}
	print "</div> <!-- // .wrap -->";
}



// Everything Categories beyond here

function chiron_wp_manage_categories(){
	global $chiron;
	$user = wp_get_current_user(); 
	$uid = $user->data->ID;
	print "<div class='wrap'>";
	print "<h2>Categories of your Sources <a class='add-new-h2' href='?page=chiron_add_category'>Add New</a></h2>";
	print "<p>Manage the Categories of your Sources, young Hero or Heroine!</p>";
	$categories = $chiron->categories_get_all_by_user($uid);
	$no = count($categories);
	if($no>0){
		print "<table class='wp-list-table widefat'>";
		print '<thead>';
		print '<tr>';
		print '<th>Titel</th>';
		print '<th>Weight</th>';
		print "<th>Operations</th>";
		print '</tr>';
		print '</thead>';
		$oddoreven = "odd";
		foreach($categories as $category){
			$rowclasses = array();
			if($oddoreven == "odd"){
				$rowclasses[] = "alternate";
			}
			$classes = implode(" ", $rowclasses);
			print "<tr class='".$classes."'>";
			print "<td>".$category->title."</td>";
			print "<td>".$category->weight."</td>";
			print "<td><a href='?page=chiron_edit_category&category_id=".$category->id."'>edit</a></td>";
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
		print '<th>Weight</th>';	
		print "<th>Operations</th>";
		print '</tr>';
		print '</tfoot>';
		print "</table>";
		print "<p>Remember, these are your Categories. Other Users might have other Categories for the same sources.</p>";
	}
	
	print "</div> <!-- // .wrap -->";
}

function chiron_wp_add_category(){
	global $chiron;
	print "<div class='wrap'>";
	print "<h2>Add a new Category</h2>";
	$user = wp_get_current_user(); 
	$uid = $user->data->ID;
	if(isset($_POST) && !empty($_POST)){
		$category = new chiron_category();
		$category->user = $uid;
		$category->title = $_POST['title'];
		$category->weight = $_POST['weight'];
		$result = $category->add();
		if($result == "1"){
			print '<div id="message" class="updated below-h2"><p>Category added successfully.</p></div>';
		}
	}
	
	print "<div class='form-wrap'>";
	print '<form method="post">';
	print '<div class="form-field form-required"><label>Title of your new Category</label><input type="text" name="title"><p>The Title of your Category, actually there is nothing more to it.</p></div>';
	print '<div class="form-field form-required"><label>Weight of your new Category</label><input type="text" name="weight" /><p>Weight of a Category, which defines the order or Categories</p></div>';
	print '<input type="submit">';
	print '</form>';
	print "</div> <!-- // .form-wrap -->";	
	print "</div> <!-- // .wrap -->";
}

function chiron_wp_edit_category(){
	global $chiron;
	print "<div class='wrap'>";
	print "<h2>Edit Category</h2>";
	$user = wp_get_current_user(); 
	$uid = $user->data->ID;
	if(isset($_POST) && !empty($_POST)){
		$category = new chiron_category();
		$category->id = $_GET['category_id'];
		$category->title = $_POST['title'];
		$category->weight = $_POST['weight'];
		$result = $category->update();
		if($result == "1"){
			print '<div id="message" class="updated below-h2"><p>Category added successfully.</p></div>';
		}
	}
	if(isset($_GET['category_id']) && !empty($_GET['category_id'])){
		$category_id = $_GET['category_id'];
		$category = new chiron_category($category_id);
		print "<div class='form-wrap'>";
		print '<form method="post">';
		print '<div class="form-field form-required"><label>Title of your new Category</label><input type="text" name="title" value="'.$category->title.'"><p>The new Title of your Category, actually there is nothing more to it.</p></div>';
		print '<div class="form-field form-required"><label>Weight of your new Category</label><input type="text" name="weight" value="'.$category->weight.'"/><p>The new Weight of a Category, which defines the order or Categories</p></div>';
		print '<input type="submit">';
		print '</form>';
		print "</div> <!-- // .form-wrap -->";	
		
	}else{
		print "<p>No Category selected for editin.</p>";
	}
	
	print "</div> <!-- // .wrap -->";
}


// Everything WP-Cron-Job from here
 
function chiron_wp_add_cron_intervals( $schedules ) {
 	$schedules['5minutes']['interval'] = 300;
	$schedules['5minutes']['display'] = __('Every 5 Minutes');
	return $schedules; // Do not forget to give back the list of schedules!
}

add_filter( 'cron_schedules', 'chiron_wp_add_cron_intervals' );

add_action( 'chiron_cron_hook', 'chiron_wp_cron_exec' );

if( !wp_next_scheduled( 'chiron_cron_hook' ) ) {
	wp_schedule_event( time(), '5minutes', 'chiron_cron_hook' );
}


function chiron_wp_cron_exec(){
	global $chiron;
	$sources = $chiron->sources_run_cron();
}







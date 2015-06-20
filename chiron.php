<?php
/**
* Plugin Name: Chiron
* Description: The Teacher of Heroes and Heroines
* Version: 0.9b
* Author: Palasthotel (in Person: Benjamin Birkenhake)
* Author URI: http://www.palasthotel.de
*/

// First, like in all Implementations, get the Database Credentials
global $table_prefix;

define(CHIRON_DB_SRV, DB_HOST);
define(CHIRON_DB_USR, DB_USER);
define(CHIRON_DB_PWD, DB_PASSWORD);
define(CHIRON_DB_DBS, DB_NAME);
define(CHIRON_DB_PRE, $table_prefix);
define(CHIRON_IMPLEMENTATION, "Wordpress");

// Then bootstrap Chiron.
// This loads all Classes and creates Instances of the classes chiron_db and chiron_core.
require('core/classes/bootstrap.php');

// Use own translation Tool
function chiron_t($str){
	if(function_exists('t')){
		return t($str);	
	}
	if(function_exists('__')){
		return __( $text, "chiron");
	}	
	return $str;
}

// Own Database Query-Wrapper based on the Data-Classe
function chiron_db_query($querystring){
	global $chiron_db;
	$querystring = str_replace("{", $wpdb->prefix, $querystring);
	$querystring = str_replace("}", "", $querystring);
    $result = $chiron_db->query($querystring) or die($querystring." failed: ".$chiron_db->error);
    return $result;
}

// Helper-Function for UTF8-Frakups
function chiron_is_utf8($string) {
    return (bool) preg_match('//u', $string);
}    

// Making all Strings UTF8, which aren't UTF8
function chiron_clean_string($string){
	if(!chiron_is_utf8($string)){
		return utf8_encode($string);
	}else{
		return $string;
	}
}         


function chiron_wp_activate(){
	global $wp_version;
	
	static $secondCall=FALSE;
	$chiron_db = new chiron_db(CHIRON_DB_SRV, CHIRON_DB_USR, CHIRON_DB_PWD, CHIRON_DB_DBS, CHIRON_DB_PRE);

	$options=get_option("chiron",array());
	if(!isset($options['installed'])){
		// Run the Installation
		// 1. Create the Databases based on the Schema
		$chiron_db->execute_schema();

		// Tell Wordpress that we have installed
		$options['installed'] = TRUE;
		update_option("chiron", $options);

	}else{		
		//TODO: implement update support
	}
		
}

register_activation_hook(__FILE__, "chiron_wp_activate");

// Let's add our own Favicon for the News Dashboard
function chiron_admin_head() {
   print '<link rel="icon" type="image/x-icon" href="'.plugins_url( 'chiron-favicon.png', __FILE__ ).'"/>';
}

// Only add the Favicon on "our" admin pages.
if($_GET['page']=='chiron_dashboard'){
	add_action('admin_head', 'chiron_admin_head');
}


// Adding all the lovely Menu-Items
function chiron_wp_admin_menu(){        
		
		// Main Backend Menu Item
        add_menu_page('Reader','Chiron','read','chiron_dashboard','chiron_wp_dashboard', 'dashicons-book-alt', '76');
		
		// Submenu Items
		add_submenu_page('chiron_dashboard', 'News-Dashboard', 'News-Dashboard', 'read', 'chiron_dashboard', 'chiron_wp_dashboard' );
		add_submenu_page('chiron_dashboard', 'Your Subscriptions', 'Your Subscriptions', 'read', 'chiron_manage_subscriptions', 'chiron_wp_manage_subscriptions' );
		add_submenu_page('chiron_dashboard', 'Your Categories', 'Your Categories', 'read', 'chiron_manage_categories', 'chiron_wp_manage_categories' );
		add_submenu_page('chiron_dashboard', 'All Sources', 'All Sources', 'read', 'chiron_manage_sources', 'chiron_wp_manage_sources' );
		add_submenu_page('chiron_dashboard', 'Settings', 'Settings', 'read', 'chiron_settings', 'chiron_wp_settings' );
		add_submenu_page('chiron_dashboard', 'Debugging', 'Debugging', 'read', 'chiron_debugging', 'chiron_wp_debug' );
		
		// Functions which need not be available via Wordpress' Admin Menu
		add_submenu_page('null', 'Add new Source', 'Add new Source', 'read', 'chiron_add_source', 'chiron_wp_add_source' );
		add_submenu_page('null', 'Refresh Sources', 'Refresh Sources', 'read', 'chiron_refresh_sources', 'chiron_wp_refresh_sources' );
		add_submenu_page('null', 'Add new Category', 'Add new Category', 'read', 'chiron_add_category', 'chiron_wp_add_category' );
		add_submenu_page('null', 'Edit Source', 'Edit Source', 'read', 'chiron_edit_source', 'chiron_wp_edit_source' );
		add_submenu_page('null', 'Refresh Source', 'Refresh Source', 'read', 'chiron_refresh_source', 'chiron_wp_refresh_source' );
		add_submenu_page('null', 'Edit Category', 'Edit Category', 'read', 'chiron_edit_category', 'chiron_wp_edit_category' );
		add_submenu_page('null', 'Manage Subscription', 'Manage Subscription', 'read', 'chiron_manage_subscription', 'chiron_wp_manage_subscription' );
		add_submenu_page('null', 'Delete Subscription', 'Delete Subscription', 'read', 'chiron_delete_subscription', 'chiron_wp_delete_subscription' );
		add_submenu_page('null', 'Add new Source and Subscription', 'Add new Source and Subscription', 'read', 'chiron_add_source_and_subscription', 'chiron_wp_add_source_and_subscription' );

}		

add_action("admin_menu","chiron_wp_admin_menu");



function chiron_wp_debug(){
	add_action('admin_head', 'chiron_admin_head');
	global $wp_version;
	print "<div class='wrap'>";
	print "<h2>Debugging Chiron</h2>";
	
	// Basic Data
	
	print "<h3>Basic Data</h3>";
	print "<ul>";
	print "<li><strong>Wordpress Version:</strong> ".$wp_version."</li>";
	print "</ul>";
	
	
	// Schedules
	print "<h3>Current Schedules</h3>";
	print "<ul>";
	$schedules = wp_get_schedules(); 
	foreach($schedules as $slug => $plan){
		print "<li><strong>".$plan['display']."</strong> runs every ".$plan['interval']." seconds. [".$slug."]</li>";
	}
	print "</ul>";
	
	// Crons
	print "<h3>Cron</h3>";
	$cron = get_option('cron');
	print "<ul>";
	foreach($cron as $timestamp => $nextcron){
		if($timestamp != "version"){
			print "<li>On ".date("d-m-Y, h:i:s", (int) $timestamp)." shallst run ";
			foreach($nextcron as $hook => $details){
				print $hook." ";
			}
			print "</li>";
		}
	}
	print "</ul>";
	print "<pre>";
	//print_r($cron);
	print "</pre>";
	print "</div> <!-- // .wrap -->";
}

function chiron_wp_settings(){
	add_action('admin_head', 'chiron_admin_head');
	print "<div class='wrap'>";
	print "<h2>Chiron Settings</h2>";
	print "<p>Manage your Settings, young Hero or Heroine!</p>";
	print "</div> <!-- // .wrap -->";
}


function chiron_wp_dashboard(){
	
	// Get Basic Information
	global $chiron;
	global $wp_version;
	$user = wp_get_current_user(); 
	$id_user = $user->data->ID;
	$sources_count = $chiron->sources_count();
	$items_count = $chiron->items_count();
	
	// Wrapper Div and Header
	print "<div class='wrap'>";
	print "<h2>Welcome to your News-Dashboard, young Hero or Heroine!</h2>";
	
	
	// Check Version
	$version = explode(".", $wp_version);
	if(!($version[0] >= "3" and $version[1]>=8)){
		print "<p>WARNING: Chiron hasn't been tested with Wordpress ".$wp_version.". Please deactivate the Plugin or use it on your own risk!</p>";
	}
	
	chiron_wp_quick_add_form();
	
	// Check and get the current or selected Date
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
		

	
	$day = date("Y-m-d", $timestamp);
	$chiron->items_get_by_day_and_user($day, $id_user);
	
	print "<p><strong>".$items_count[0]." items</strong> from <strong>".$sources_count[0]." sources</strong> and <strong>".count($chiron->items)." news of ".date("l", $timestamp)." the ".date("j. F Y", $timestamp)."</strong> are waiting to be read by you.</p>";
	
	$pagenav = "";
	$pagenav .= '<div class="tablenav">';    
	$pagenav .= '<div class="tablenav-pages">';    
	$yesterday = date("Y-m-d", $timestamp - 60*60*24);
	$tomorrow = date("Y-m-d", $timestamp + (60*60*24));
	$pagenav .= '<div class="tablenav-pages">';
	$pagenav .= " <a class='prev-page' href='?page=chiron_dashboard&day=".$yesterday."'>‹</a> ";
	$pagenav .= '<span class="paging-input" style="width:20em;">'."News of ".date("d. M. Y", $timestamp)."</span>";
	if($tomorrow != date("Y-m-d", time())){
	    $pagenav .= " <a class='next-page' href='?page=chiron_dashboard&day=".$tomorrow."'>›</a>";
	}else{
		$pagenav .= " <a class='next-page'>›</a>";
	}
	$pagenav .= "</div>";
	$pagenav .= "</div>";
	$pagenav .= "</div>";

	print $pagenav;
	
	if(count($chiron->items)>0){
		
		
		
		print "<table class='wp-list-table widefat chiron-news'>";
		print '<thead>';
		print '<tr>';
		print '<th>Source</th>';
		print '<th>Title</th>';
		print '</tr>';
		print '</thead>';
		
		$output = "";
		foreach($chiron->categories as $category){
			$output  = "";
					
			$found = 0;
			$oddoreven = "odd";
			foreach($chiron->subscriptions as $subscription){
				if($category->id == $subscription->id_category){					
					foreach($chiron->items as $item){						
						if($item->source == $subscription->id_source){
							$found ++;
							$rowclasses = array();
							if($oddoreven == "odd"){
								$rowclasses[] = "alternate";
							}
							$classes = implode(" ", $rowclasses);
							
							$output .= "<tr class='".$classes."'>";
							$output .= "<td>".chiron_clean_string($chiron->sources[$item->source]->title)."</td>";
							$title = "";
							if($item->title !=""){
								$title = $item->title;
							}else{
								$title = "~";
							}    
							
							if(!chiron_is_utf8($title)){
								$title = utf8_encode($title);
							}  
							
							$output .= "<td><a href='".$item->url."'><span class='dashicons dashicons-yes'></span>".$title."</a></td>";
							$output .= "</tr>";
							
							if($oddoreven == "odd"){
								$oddoreven = "even";
							}else{
								$oddoreven = "odd";
							}
							
						}						
					}
				}
			}
			if($found>0){
				$header  = "";
				$header .= "<tr>";
				$header .= "<td colspan='2'><h3 style='display:inline'>".$category->title."</h3> with ".$found." items</td>";
				$header .= "</tr>";
				print $header.$output;
			}			
		}	
	
		print '<tfoot>';
		print '<tr>';
		print '<th>Source</th>';
		print '<th>Title</th>';		
		print '</tr>';
		print '</tfoot>';
		print "</table>";
		print '<style>'."\n";
		print ' a span {color:white;}'."\n";
		print ' .alternate a span {color:#f9f9f9;}'."\n";	
		print ' a:visited, a:visited span { color:dimgray; }'."\n";
		print ' a:visited:hover { color:black; }'."\n";
		
		print '</style>';
		
		print $pagenav;
		
	}
	print "<p>".count($chiron->items)." yesterdays news.</p>";
	print "</div>";
}

function chiron_wp_quick_add_form(){
	// Quick add Feed
	print "<form class='chiron-quickadd' style='text-align:left;' action='admin.php?page=chiron_add_source_and_subscription' method='post'>";
	print "<input type='text' name='url' placeholder='Add a URL of a Feed here' style='width:50%; height:28px;'/>";
	print "<input type='submit' class='button' value='Quick add new Feed' style=''>";
	print "</form>";
}

// Sources from here on

function chiron_wp_manage_sources(){
	add_action('admin_head', 'chiron_admin_head');
	global $chiron;
	print "<div class='wrap'>";
	print "<h2>Sources <a class='add-new-h2' href='?page=chiron_add_source'>Add New</a></h2>";
	print "<p>Manage your Sources, young Hero or Heroine! Rember, these are all sources, that are used by all users of this Site. So be carefull, when editing
	or deleting a Source! Another user might think differently about it.</p>";
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
			print "<td>".$source->title."</td>";
			print "<td>".$source->url."</td>";
			if($source->lastchecked>0){
				$date = date("d. m. Y H:i:s", $source->lastchecked);
			}else{
				$date = "never";
			}
			print "<td>".$date."</td>";
			print "<td>".$source->status."</td>";
			print "<td><a href='?page=chiron_edit_source&source_id=".$source->id."'>edit</a>";
			print " | <a href='?page=chiron_refresh_source&source_id=".$source->id."'>refresh</a>";
			print " | <a href='?page=chiron_manage_subscription&source_id=".$source->id."'>subscribe</a>";
			print "</td>";
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


function chiron_wp_manage_subscriptions(){
	global $chiron;
	$user = wp_get_current_user(); 
	$uid = $user->data->ID;
	$result = $chiron->subscriptions_get_all_by_user($uid);
	$itemcount = $chiron->sources_get_item_count();
		
	print "<div class='wrap'>";
	print "<h2>Manage your Subscriptions</h2>";	
		
	$no = count($chiron->sources);
	print "<p>You have subscribed ".$no." magnificent Sources of Information.</p>";
	if($no>0){
		print "<table class='wp-list-table widefat'>";
		print '<thead>';
		print '<tr>';
		print '<th>Source</th>';
		print '<th>Category</th>';
		print '<th>URL</th>';
		print '<th>Items</th>';
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
			print "<td><strong>".$source->title."</strong></td>";
			print "<td>".$chiron->categories[$chiron->subscriptions[$source->id]->id_category]->title."</td>";
			print "<td>".$source->url."</td>";
			print "<td>".$itemcount[$source->id]."</td>";
			if($source->lastchecked>0){
				$date = date("d. m. Y H:i:s", $source->lastchecked);
			}else{
				$date = "never";
			}
			print "<td>".$date."</td>";
			print "<td>".$source->status."</td>";
			print "<td>";
			print " <a href='?page=chiron_refresh_source&source_id=".$source->id."'>refresh</a>";
			print " | <a href='?page=chiron_manage_subscription&source_id=".$source->id."'>edit</a>";
			print " | <a href='?page=chiron_delete_subscription&source_id=".$source->id."'>unsubscribe</a>";
			print "</td>";
			print "</tr>";
			
			if($oddoreven == "odd"){
				$oddoreven = "even";
			}else{
				$oddoreven = "odd";
			}
		}
		print '<tfoot>';
		print '<tr>';
		print '<th>Source</th>';
		print '<th>Category</th>';
		print '<th>URL</th>';
		print '<th>Items</th>';
		print '<th>Last Checked</th>';
		print '<th>Status</th>';
		print "<th>Operations</th>";
		print '</tr>';
		print '</tfoot>';
		print "</table>";
	}
	
	print "</div> <!-- // .wrap -->";
	
	print "<pre>";
	//print_r($chiron);
	print "</pre>";
}



// Everything Subscriptions beyond here

function chiron_wp_manage_subscription(){
	global $chiron;
	$user = wp_get_current_user(); 
	$uid = $user->data->ID;
	print "<div class='wrap'>";
	print "<h2>Manage a Subscription</h2>";
	
	// First check, wether there are Post-Variables
	if(isset($_POST) && !empty($_POST) && isset($_GET['source_id']) && !empty($_GET['source_id'])){
		$subscription = new chiron_subscription();
		$subscription->id_user = $uid;
		$subscription->id_source = $_GET['source_id'];
		$subscription->id_category = $_POST['category_id'];
		if(!$subscription->exists()){
			$result = $subscription->add();
			if($result){
				print '<div id="message" class="updated below-h2"><p>Subscription added successfully.</p></div>';
			}else{
				print '<div id="message" class="updated below-h2"><p>Sorry, but you already subscribed to that source.</p></div>';
			}
		}else{
			$result = $subscription->edit_category();
			if($result){
				print '<div id="message" class="updated below-h2"><p>Successfully changed the Category of your Subscription.</p></div>';
			}else{
				print '<div id="message" class="updated below-h2"><p>Sorry, but you already subscribed to that source with that Category.</p></div>';
			}
		}
		
		
	}
	
	
	// Then Render the Form (again)
	if(isset($_GET['source_id']) && !empty($_GET['source_id'])){
		$source_id = $_GET['source_id'];
		$source = new chiron_source($source_id);
		$subscription = new chiron_subscription();
		$subscription->id_source = $source_id;
		$subscription->id_user = $uid;
		if($subscription->exists()){
			$subscription->load_by_source_and_user();
		}
		
		print "<p>Adding Subscrption of the Source <strong>'".$source->title."'</strong></p>";
		$categories = $chiron->categories_get_all_by_user($uid);
		print "<div class='form-wrap'>";
		print '<form method="post">';
		if(count($categories)>0){
			print "<select name='category_id'>";
			print "<option value='0'>Uncategorized</option>";
			foreach($categories as $category){
				if($subscription->id_category == $category->id){
					$selected = " selected='selected'";
				}else{
					$selected = '';
				}
				print "<option value='".$category->id."' ".$selected.">".$category->title."</option>";
			}
			print "</select>";
		}
		print '<input type="submit">';
		print '</form>';
		print "</div> <!-- // .form-wrap -->";		
	}
	print "</div> <!-- // .wrap -->";
}



function chiron_wp_delete_subscription(){
	global $chiron;
	$user = wp_get_current_user(); 
	$uid = $user->data->ID;
	print "<div class='wrap'>";
	print "<h2>Unscribe from a Source</h2>";
	if(isset($_GET['source_id']) && !empty($_GET['source_id'])){
		$source_id = $_GET['source_id'];
		$source = new chiron_source($source_id);
	}
	$subscription = new chiron_subscription();
	$subscription->id_source = $source_id;
	$subscription->id_user = $uid;
	//print_r($subscription);
	if($subscription->exists()){
		$subscription->load_by_source_and_user();
		//print_r($subscription);
		$subscription->delete();
		print '<div id="message" class="updated below-h2"><p>Successfully unscribed you from Source "'.$source->title.'".</p></div>';
	}else{
		print '<div id="message" class="updated below-h2"><p>Sorry, but you\'re not subscribed to Source "'.$source->title.'".</p></div>';
	}
	print "<p>Go back to <a href='?page=chiron_manage_subscriptions'>your Subscriptions</a> or <a href='?page=chiron_dashboard'>your Newsdashboard</a>.</p>";
	
	print "</div> <!-- // .wrap -->";
}





function chiron_wp_add_source_and_subscription(){
	global $chiron;
	$user = wp_get_current_user(); 
	$uid = $user->data->ID;
	$categories = $chiron->categories_get_all_by_user($uid);
	
	
	print "<div class='wrap'>";
	print "<h2>Add New Feed Source</h2>";
	// Set the URL
	$url = "";
	if(isset($_POST['url']) and !empty($_POST['url'])){
		$url = $_POST['url'];		
	}
	
	$title = "";
	if(isset($_POST['title']) and !empty($_POST['title'])){
		$title = $_POST['title'];		
	}
	
	// Try to get the Title from SimplePie
	if($url !="" and $title == ""){
		$feed = new SimplePie();
	    $feed->set_feed_url($url);
	    $feed->init();
	    $feed->handle_content_type();
		$title = $feed->get_title();
	}
	
	$category = "0";
	if(isset($_POST['category_id']) and !empty($_POST['category_id'])){
		$category = $_POST['category_id'];
	}
	
	// Actually save the Data
	if($url != "" and $category !="0"){
		// First Check wether the Source exists
		$source = new chiron_source();
		$source->url = $url;
		// If the Source doesn't exist, create it.
		if(!$source->exists()){
			$source->type = '1';
			$source->title = $title;
			$source->add();
			print '<div id="message" class="updated below-h2"><p>Source added successfully.</p></div>';
		}else{
			$source->load_by_url();
			print '<div id="message" class="updated below-h2"><p>Source already exists. I will try to subscribe you to it.</p></div>';
		}

		// Second, add the Subscription
		$subscription = new chiron_subscription();
		$subscription->id_user = $uid;
		$subscription->id_source = $source->id;
		$subscription->id_category = $category;
		if(!$subscription->exists()){
			$result = $subscription->add();
			if($result){
				print '<div id="message" class="updated below-h2"><p>Subscription added successfully.</p></div>';
			}else{
				print '<div id="message" class="updated below-h2"><p>Sorry, but you already subscribed to that source.</p></div>';
			}
		}else{
			print '<div id="message" class="updated below-h2"><p>Your are already subscribed to that Source.</p></div>';
			$result = $subscription->edit_category();
			if($result){
				print '<div id="message" class="updated below-h2"><p>Successfully changed the Category of your Subscription.</p></div>';
			}else{
				print '<div id="message" class="updated below-h2"><p>Sorry, but you already subscribed to that source with that Category.</p></div>';
			}
		}
		
	}
	
	
	print "<div class='form-wrap'>";
	print '<form method="post">';
	print '<div class="form-field form-required"><label>URL of your new Source</label><input type="text" name="url" value="'.$url.'"/><p>The URL under which your Source is awailable.</p></div>';
	print '<div class="form-field form-required"><label>Title of your new Source</label><input type="text" name="title" value="'.$title.'"><p>If this field is blank, you may add a Title here. Otherwise you may edit it now. Or you may edit it later on.</p></div>';
	print '<div class="form-field form-required"><label>Category of your new Source</label>';
	if(count($categories)>0){
		print "<select name='category_id'>";
		print "<option value='0'>Uncategorized</option>";
		foreach($categories as $category){
			if($subscription->id_category == $category->id){
				$selected = " selected='selected'";
			}else{
				$selected = '';
			}
			print "<option value='".$category->id."' ".$selected.">".$category->title."</option>";
		}
		print "</select>";
	}
	print "</div>";
	print '<input type="submit">';
	print '</form>';
	
	print "</div> <!-- // .wrap -->";
}

// Everything WP-Cron-Job from here
 
function chiron_wp_add_cron_intervals( $schedules ) {
 	$schedules['5minutes']['interval'] = 300;
	$schedules['5minutes']['display'] = __('Every 5 Minutes');
	$schedules['1minute']['interval'] = 60;
	$schedules['1minute']['display'] = __('Every Minute');
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

// Widgets

add_action( 'wp_dashboard_setup', 'chiron_wp_add_widgets' );


/**
 * Add a widget to the dashboard. Implementation of action 'wp_dashboard_setup'.
 */
function chiron_wp_add_widgets() {

	wp_add_dashboard_widget(
		'chiron_quick_add_form_widget',     // Widget slug.
		'Quick add Feed',         			// Title.
		'chiron_wp_dashboard_widget_quick_add_feed_function' // Display function.                 
    );	
}

/**
 * Create the function to output the contents of our Dashboard Widget.
 */
function chiron_wp_dashboard_widget_quick_add_feed_function($drafts = false) {
	chiron_wp_quick_add_form();	
}

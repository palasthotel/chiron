<?php

class chiron_core {
	public $chiron_core_db;
  	public $sources;
  	public $items;
	public $my_categories;
	public $my_subscriptions;
	
	
	public function __construct() {    
		$this->chiron_core_db = new chiron_core_db();
	}
	
	// Methods for multiple Sources
	
	public function sources_count(){
		$count = $this->chiron_core_db->sources_count();
		return $count;
	}

	public function sources_run_cron(){
		$sources = $this->chiron_core_db->sources_get_least_updated("5");
		foreach($sources as $source){
			$source->refresh();
		}
		return $sources;
	}
	
	public function sources_get_all(){
    	$this->sources = $this->chiron_core_db->sources_get_all();
    	return count($this->sources);
  	}

	public function sources_get_some_by_ids($ids_sources){
		$this->sources = $this->chiron_core_db->sources_get_some_by_ids($ids_sources);
		return count($this->sources);
	}
	
	public function sources_get_item_count(){
		return $this->chiron_core_db->sources_get_item_count();
	}

	// Methods for multiple Items
	
	
  	public function items_get_by_day($day){
   		$this->items = $this->chiron_core_db->items_get_by_day($day);
    	return count($this->items);
  	}

	public function items_get_by_day_and_user($day, $id_user){
		$this->categories = $this->categories_get_all_by_user($id_user);
   		$this->subscriptions = $this->chiron_core_db->subscriptions_get_all_by_user($id_user);
		$ids_sources = array();
		foreach($this->subscriptions as $subscription){
			$ids_sources[] = $subscription->id_source;
		}
		$this->sources_get_some_by_ids($ids_sources);
		$this->items = $this->chiron_core_db->items_get_by_day_and_sources($day, $ids_sources);
    	return count($this->items);
  	}
  
  	public function items_get_by_feed($feed, $feeds){
    	$query = "SELECT * FROM item WHERE source = '".$feed."' ORDER BY date DESC LIMIT 20";
    	$result = mysql_query($query) or print('Query failed: ' . mysql_error());   
    	//print_r($pages);
    	while($item = mysql_fetch_array($result)){
      		$items[] = $item;
    	}
    	return $items;
  	}

	public function items_count(){
		$count = $this->chiron_core_db->items_count();
		return $count;
	}
	
	
	// Methods for Categories
	
	public function categories_get_all_by_user($uid){
		return $this->chiron_core_db->categories_get_all_by_user($uid);
	}
	
	
	// Methods for Subscriptions
	
	public function subscriptions_get_all_by_user($id_user){
		$this->categories = $this->categories_get_all_by_user($id_user);
		$this->subscriptions = $this->chiron_core_db->subscriptions_get_all_by_user($id_user);
		$ids_sources = array();
		foreach($this->subscriptions as $subscription){
			$ids_sources[] = $subscription->id_source;
		}
		$this->sources_get_some_by_ids($ids_sources);
		return count($this->subscriptions);	
		
	}

  
  	
}
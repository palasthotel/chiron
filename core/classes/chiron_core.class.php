<?php

class chiron_core {
	public $chiron_core_db;
  	public $sources;
  	public $items;
	
	
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

	// Methods for multiple Items
	
	public function items_get_latest($limit=10){
    	if(isset($this->feeds[0]) and $this->feeds[0]!=""){
      		$query = "SELECT * FROM item WHERE ";
      		foreach($this->feeds as $source){
        		$parts[] = "source = '".$source."' ";
      		}
      		$query .= implode(" OR ", $parts) ;   
      		$query .= "ORDER BY date DESC, id DESC LIMIT ".$limit;
    	}else{
      		$query = "SELECT * FROM item ORDER BY date DESC, id DESC LIMIT ".$limit;
    	}

    	$items = array();
    	if($query !=""){
      		$result = mysql_query($query) or print('Query failed: ' . mysql_error());   
      		while($item = mysql_fetch_array($result)){
        		$object = new item();
        		$object->load($item);
        		$this->items[] = $object;
      		}
    	}
    	return count($items);
  	}

  	public function items_get_by_day($day){
   		$this->items = $this->chiron_core_db->items_get_by_day($day);
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

  
  	
}
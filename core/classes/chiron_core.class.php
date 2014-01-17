<?php

class chiron_core {
	public $chiron_core_db;
  	public $sources;
  	public $items;
	
	
	public function __construct() {    
		$this->chiron_core_db = new chiron_core_db();
	}
	
	
	public function sources_get_all(){
    	$this->sources = $this->chiron_core_db->sources_get_all();
    	return count($this->sources);
  	}
	
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
    $query = "SELECT * FROM item WHERE date >= '$day 00:00:00' AND date <= '$day 23:59:59'";
    $result = mysql_query($query) or print('Query failed: ' . mysql_error());   
    while($item = mysql_fetch_array($result)){
      $object = new item('', array());
      $object->load($item);      
      $this->items[] = $object;
    }
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

  public function perform_cron() {
    $query = "SELECT * FROM source ORDER BY last_updated ASC limit ".FEEDS_PER_CRON.";";
    $result = mysql_query($query) or print('Query failed: '.mysql_error());
    while($item = mysql_fetch_array($result)) {
      $feed=new feed();
      $feed->load($item);
      $feed->refresh();
    }
  }

  
  	
}
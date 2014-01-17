<?php

class chiron {
	public $db;
  	public $feeds;
  	public $items;
  	public $storage;
	
	
	public function __construct() {
    	$this->db = mysql_connect(DB_SRV, DB_USR, DB_PWD);
    	mysql_select_db(DB_DBS) or die('Could not select database');
	}
	
	
	public function feeds_get_all(){
    	$query = "SELECT * FROM source ORDER BY title";
    	$result = mysql_query($query) or print('Query failed: ' . mysql_error());
    	$feeds = array();
    	while($feed = mysql_fetch_array($result)){
      		$this->feeds[$feed["id"]] = $feed;
    	}
    	return count($this->feeds);
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
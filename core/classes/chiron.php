<?php

class chiron {
  public $feeds;
  public $items;
	public $storage;
	
	
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
        $this->items[] = $item;
      }
    }
    return count($items);
  }

  public function items_get_by_day($day){
    $query = "SELECT * FROM item WHERE date >= '$day 00:00:00' AND date <= '$day 23:59:59'";
    $result = mysql_query($query) or print('Query failed: ' . mysql_error());   
    while($item = mysql_fetch_array($result)){      
      $this->items[] = $item;
    }
    return count($items);
  }	
}
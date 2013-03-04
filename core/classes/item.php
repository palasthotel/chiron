<?php 


class item extends SimplePie_Item {
  public $id;
  public $source;
  public $date;
  public $title;
  public $text;
  public $url;
  public $image;
  public $item_meta;
  
  public function exists(){ 
    global $db;
    $query = "SELECT count(url) FROM item WHERE url='".mysql_real_escape_string($this->get_permalink())."'";  
    $result = mysql_query($query);
    $return = mysql_fetch_array($result);
    if($return[0]==0){
      return false;
    }else {
      return true;
    }
  }
  
  public function add($feed){
    if(!$this->exists()){
      $date = $item->get_date("Y-m-d H:i:s");
      if($date == "0000-00-00 00:00:00"){
        $date = date("Y-m-d H:i:s");
      }
      $query = "INSERT INTO `item` (`id` , `source` ,  `date` , `title` ,  `text` ,  `url` ) VALUES ( NULL , '".$feed->id."', '".$date."', '".addslashes($this->get_title())."', '".addslashes($this->get_content())."', '".mysql_real_escape_string($this->get_permalink())."' );";
      $result = mysql_query($query) or print('Query failed: ' . mysql_error()); 
      return 1;   
    }else{
      return 0;
    }
  }
  
}
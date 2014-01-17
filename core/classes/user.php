<?php 


class chiron_user {
  public $id;
  public $slug;
  public $email;
  public $password;
  public $onetimecode;
  
  
  public function add(){
    if($this->exists($email)){
			return false;
		}else{
			global $db;			
			$query = "INSERT INTO `user` (`id` , `slug` ,  `email` , `password` ,  `onetimecode` ) VALUES ( NULL , '', '".$this->email."', '".$this->password."', NULL );";
      $result = mysql_query($query) or print('Query failed: ' . mysql_error()); 
			$this->load_by_email($email);
      return true;   
		}
  }
  
  public function update(){
    
  }
  
  public function delete(){
    
  }

  public function exists(){
		global $db;
		$query = "SELECT count(email) FROM user WHERE email='".$this->email."'";  
		$result = mysql_query($query);
    $return = mysql_fetch_array($result);
    if($return[0]==0){
      return false;
    }else {
      return true;
    }		
	}
	
	public function login(){
		$query = "SELECT id FROM user WHERE email='".$this->email."' and password='".$this->password."'";  
		$result = mysql_query($query);
    $return = mysql_fetch_array($result);
		$this->id = $return['id'];
	}
	
	public function sanitize(){
		$this->email = strip_tags($this->email);		
	}
	
	public function load_by_email($email){
		global $db;
		$query = "SELECT id FROM user WHERE email='".$this->email."'";  
		$result = mysql_query($query);
    $return = mysql_fetch_array($result);
		$this->id = $return['id'];
	}
  
  
  // User Meta Actions
    
  // Check wether one Meta-Data-set exists  
  public function exists_meta($meta_key, $meta_value){
    
  }  
  
  // Add Meta Data-Set
  public function add_meta($meta_key, $meta_value){
    
  }
  
  // Delete Meta Data-Set
  public function delete_meta($meta_id){
    
  }
    
  // Load all Meta-Data of one Item, that belong to one User
  public function load_users_meta($user){
  
  }
  
  public function encrypt_password(){
    $this->password = md5($this->password);
  }
  
  
}
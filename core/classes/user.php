<?php 


class user extends chironbase {
  public $id;
  public $slug;
  public $email;
  public $firstname;
  public $lastname;
  public $is_active;
  public $is_blocked;
  public $is_admin;
  public $date_firstlogin;
  public $date_lastlogin;
  public $password
  public $onetimecode;
  
  
  public function add(){
    
  }
  
  public function update(){
    
  }
  
  public function delete(){
    
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
  
  // Add the Meta-Set "Page";
  public function add_page($page_name){
    
  }
  
  // Get the ID of the Page
  public function get_page_id_by_name($page_name){
    
  }
  
  
}
<?php


class private_controller { 
	
	public function check_login(){
		if(!is_numeric($_SESSION['hero'])){
			return false;
		}else{
			return true;
		}
	}
	
	public function action_lesson(){
		if(!$this->check_login()){
			return "<h2>You haven't entered Chirons School yet.</h2>";
		}
		$output = "<h2>Chiron's todays lesson for you.</h2>";
		return $output;
	}
	
	

}

?>
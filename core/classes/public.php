<?php

class public_controller {

	public function action_newsstream()
	{
		global $chiron;
		$chiron->feeds_get_all();
		$items=$chiron->items_get_by_feed('203',$chiron->feeds);
		$myfeeds=array();
		foreach($chiron->feeds as $key=>$feed)
		{
			unset($feed[0]);
			unset($feed[1]);
			unset($feed[2]);
			unset($feed[3]);
			unset($feed[4]);
			$feed['favicon']=$home.'favicon.php?url='.urlencode($feed['url']);
			$myfeeds[$feed['id']]=$feed;
		}
		$mytems=array();
		$number=1;
		foreach($items as $item) {
			unset($item[0]);
			unset($item[1]);
			unset($item[2]);
			unset($item[3]);
			unset($item[4]);
			unset($item[5]);
			unset($item[6]);
			$item['image']="http://exmachina.ws/reader/images/symbol-".$number.".jpg";
			$item['source']=$myfeeds[$item['source']];
			$mytems[]=$item;
			$number++;
			if($number==4) {
				$number=1;
			}
		}
		echo json_encode($mytems);
	}

	public function action_cron()
	{
		global $chiron;
		$chiron->perform_cron();
	}
	
	public function action_homepage(){
	  	global $chiron;
	}
	
	public function action_apply(){
		$output  = "";
		$user = new user();
		$classes = array();
		$classes['email'] = array();
		$classes['password'] = array();
		$errors = array();
		$errors['email'] = "";
		$errors['password'] = "";
		$application = true;
		$anewheroisborn = false;
		
		if($_POST['email']!="" or $_POST['password']!=""){			
				$user->email = $_POST['email'];
				$user->password = $_POST['password'];
				$user->sanitize();				
				// Check Email
				if($user->email == ""){
					$errors['email'] = "You didn't leave an Email at all.";
					$classes['email'][] = "error";
					$application = false;
				}elseif(!filter_var($user->email, FILTER_VALIDATE_EMAIL)){
					$errors['email'] = "Sorry, but this is no valid Email-Address.";
					$classes['email'][] = "error";
					$application = false;
				}elseif($user->exists()){
					$errors['email'] = "Sorry, but this Email is already enlisted in Chirons School.";
					$classes['email'][] = "error";
					$application = false;
				}
				// Check Password
				if($user->password == ""){
					$errors['password'] = "You didn't came up with a password.";
					$classes['password'][] = "error";
					$application = false;
				}else{
					$user->encrypt_password();
				}
			
				if($application){
					if($user->add()){
						$anewheroisborn = true;
						$output = "Gratulation, you are Hero No. ".$user->id." at Chirons School";
					}else{
						$output = "Woops, althoug you did";
					}					
				}				
		}
		if(!$anewheroisborn){
				$output .= "<h2>Apply for an admission to Chirons school.</h2>";
				$output .= "<form action='?path=public/apply' method='post'>";
				$output .= "<p class='".implode(' ', $classes['email'])."'>Email: <input type='text' name='email' value='".$user->email."'/> ".$errors['email']."</p>";
				$output .= "<p class='".implode(' ', $classes['password'])."'>Password: <input type='password' name='password' value='".$user->password."'/> ".$errors['password']."</p>";
				$output .= "<input type='submit'/>";
				$output .= "</form>";
		}
	
		print $output;
	}
	
		public function action_enter(){
			$classes = array();
			$classes['email'] = array();
			$classes['password'] = array();
			$errors = array();
			$errors['email'] = "";
			$errors['password'] = "";
			$enteredschool = false;
			
			if(!$enteredschool){
					$output .= "<h2>Enter Chirons school.</h2>";
					$output .= "<form action='?path=public/enter' method='post'>";
					$output .= "<p class='".implode(' ', $classes['email'])."'>Email: <input type='text' name='email' value='".$user->email."'/> ".$errors['email']."</p>";
					$output .= "<p class='".implode(' ', $classes['password'])."'>Password: <input type='password' name='password' value='".$user->password."'/> ".$errors['password']."</p>";
					$output .= "<input type='submit'/>";
					$output .= "</form>";
			}
			print $output;
		}
	
	
}
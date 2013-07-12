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
				$output .= "<p class='".implode(' ', $classes['email'])."'>Email:</p>";
				$output .= "<p><input type='text' name='email' value='".$user->email."'/></p>";
				if($errors['email'] !=""){
					$output .= "<p class='".implode(' ', $classes['email'])."'>".$errors['email']."</p>";
				}
				$output .= "<p class='".implode(' ', $classes['password'])."'>Password:</p>";
				$output .= " <p><input type='password' name='password' value='".$user->password."'/></p>";
				if($errors['password'] != ""){
					$output .= "<p class='".implode(' ', $classes['email'])."'>".$errors['password']."</p>";
				}
				$output .= "<input type='submit'/>";
				$output .= "</form>";
		}
	
		return $output;
	}
	
		public function action_enter(){
			$output  = "";
			$user = new user();
			$classes = array();
			$classes['email'] = array();
			$classes['password'] = array();
			$errors = array();
			$errors['email'] = "";
			$errors['password'] = "";
			$enteredschool = false;
			$application = true;
			
			if($_POST['email']!="" or $_POST['password']!=""){			
					$user->email = $_POST['email'];
					$user->password = $_POST['password'];
					$user->sanitize();	
					
					// Check Password
					if($user->password == ""){
						$errors['password'] = "You didn't enter any password, friend.";
						$classes['password'][] = "error";
						$application = false;
					}else{
						$user->encrypt_password();
					}
								
					// Check Email
					if($user->email == ""){
						$errors['email'] = "You didn't leave an Email at all.";
						$classes['email'][] = "error";
						$application = false;
						$user->password = "";
					}elseif(!filter_var($user->email, FILTER_VALIDATE_EMAIL)){
						$errors['email'] = "Sorry, but this is no valid Email-Address.";
						$classes['email'][] = "error";
						$application = false;
						$user->password = "";
					}elseif(!$user->exists()){
						$errors['email'] = "Sorry, but Noone with this Email is not enlisted in Chirons School.";
						$classes['email'][] = "error";
						$application = false;
						$user->password = "";
					}
					
					// Check Email AND Password
					if($application){
						$user->login();
						if($user->id !=""){
							$output .= "<h2>Welcome back to Chirons school, Hero No. ".$user->id."!</h2>";
							$enteredschool = true;						
							$_SESSION['hero'] = $user->id;
						}else{						
							$user->password = "";
							$errors['password'] = "Woops, that didn't seem to be the correct Password, young Apprentice!";
							$classes['password'][] = "error";
						}
					}

								
			}
			
			
			if(!$enteredschool){
					$output .= "<h2>Enter Chirons school.</h2>";
					$output .= "<form action='?path=public/enter' method='post'>";
					$output .= "<p class='".implode(' ', $classes['email'])."'>Email:</p>";
					$output .= "<p><input type='text' name='email' value='".$user->email."'/></p>";
					if($errors['email'] !=""){
						$output .= "<p class='".implode(' ', $classes['email'])."'>".$errors['email']."</p>";
					}
					$output .= "<p class='".implode(' ', $classes['password'])."'>Password:</p>";
					$output .= " <p><input type='password' name='password' value='".$user->password."'/></p>";
					if($errors['password'] != ""){
						$output .= "<p class='".implode(' ', $classes['password'])."'>".$errors['password']."</p>";
					}
					$output .= "<input type='submit'/>";
					$output .= "</form>";
			}
			return $output;
		}
		
		public function action_leave(){
			$_SESSION['hero'] = '';
			session_destroy();
			$output .= "<h2>You have left Chirons school, for now. Take care on your ways!</h2>";
			return $output;
		}
	
	
}
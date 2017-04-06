<?php

//A class to help work woith sessions.
//manage logging users in and output_add_rewrite_var

//keep in mind inadvisable to store DB-related objects 
//in sessions



class Session {
	
	//protected static $db_fields = array('username','email', 'password', 'firstname', 'lastname', 'country', 'town', 'street_name', 'postcode', 'member_type', 'marital_status', 'number_of_children', 'occupation', 'skills', 'additional_skills', 'payment', 'additional_info');
	private $logged_in=false;
	public $timeout;
	public $members_id;
	
	function __construct(){
		session_start();
		$this->check_login();
	}
	
	public function is_logged_in(){
		return $this->logged_in;
	}
	
	public function set_database_columns($columns){
		foreach($columns as $col){
			array_push($db_fields, $col);
		}
	}
	
	public function login($member){
		//database should find user based on username/password
		if($member){
			$this->members_id = $_SESSION['members_id'] = $member->get_attribute('members_id');
			$this->logged_in = true;
			$this->timeout = $_SESSION['timeout'] = time();
			$_SESSION['db_fields'] = $this->set_database_columns($col);
			$_SESSION['db_fields'] = $member->get_database_columns($col);
		}
	}
	
	public function logout(){
		unset($_SESSION['members_id']);
		unset($this->members_id);
		unset($this->timeout);
		$this->logged_in = false;
	}
	
	private function check_login(){
		if(isset($_SESSION['members_id'])){
			$this->members_id = $_SESSION['members_id'];
			$this->logged_in = true;
		}
		else{
			unset($this->members_id);
			$this->logged_in = false;
		}
	}
}

$session = new Session();

?>
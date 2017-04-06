<?php
//If need database require it before we start

require_once('initialise.php');
//require_once(LIB_PATH.DS.'member_platform.php');


class Enum {
	
	//make sure we create new Enum() only if values for add attribute been added.
	//i.e if empty and not set then don't create new Enum!
	protected static $enum_list = array('member_type', 'marital_status');
	//protected static $counter = count($enum_list);
	
	protected $attributes = array();
	
	protected $column_name;
	
	public static $enum_objects = array();
	
	//foreach($objects as $object)
	// echo "dropdown values are.." $object->get_attribute()
	
	
	
	public function __construct($column_name){
		
		$this->column_name = $column_name;
		$temp = self::$enum_list;
		$temp = array_push($temp, $column_name);
		self::$enum_list = $temp;		
	}
	
	public static function find_enum($column_name){
		global $database;
		$table_name = Member::get_table_name();
		$sql = "SELECT * FROM ". $table_name ." "; 
		$sql .= "WHERE ". $column_name . "like '%clientid%'";
		
		$temp = self::$enum_list;
		
		foreach($temp as $temporary){
			
			if($temporary == $column_name){
				
				
			}
			
		}
		
	}
	
	public function get_attribute($field){
		return $this->{$field};
	}
	public function set_attribute($field, $value){
		$this->{$field} = $value;
	}
	
	public function addAttribute($attributeName, $attributeValue){
		
        //$temp = $this->{$attributes};
		/*$temp = */ array_push(/*temp*/$this->{$attributes}, $attributeName);
		//$this->{$attributes} = $temp;
    }
	
	public function removeAttribute($attributeName){
		
        //unset($this->{$attributeName});
		//opposite of array push.
    }
	
	private function has_attribute($attribute){
		//get_object_vars returns an asssociative array with all attributes
		//(inlcuding private ones) as keys and current values as value.
		
		$object_vars = $this->attributes();
		//want to check key exists, dont care about value here
		//returns true or false	
		
		return array_key_exists($attribute, $object_vars);
	}
	
	protected function attributes() {
		//return associative array of attribute keys and their values.
		$attributes = array();
		foreach(self::$db_fields as $field) {
			if(property_exists($this, $field)){
				$attributes[$field] = $this->$field;
			}
		}
		return $attributes;
	}
	
			
	protected function sanitised_attributes(){
		
		global $database;
		$clean_attributes = array();
		//sanitise values before submitting.
		//make them SQL safe
		foreach($this->attributes() as $key => $value){
			$clean_attributes[$key] = $database->escape_value($value);
		}
		return $clean_attributes;
					
	}
		
	public function get_safe_attributes(){
		$attributes = $this->sanitised_attributes();
		return $attributes;
	}
	
	
}

?>
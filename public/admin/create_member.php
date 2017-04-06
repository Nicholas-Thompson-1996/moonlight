<?php
require_once("../../includes/initialise.php");
?>

<?php
global $database;
$id = $_SESSION['members_id'];
$temp = Member::find_by_id($id);
$table_name = Member::get_table_name();
$sql = "SELECT * FROM ". $table_name ." WHERE `members_id` = '{$id}' AND `member_type` = 'Admin'";
if($database->query($sql)){
	if($database->affected_rows() == 0){
		$session->logout();
		redirect_to("http://localhost/moonlight/public/admin/login.php");
	}
}
if((time() - $_SESSION['timeout'] > 600)){
	$session->logout();
}
else{
	$_SESSION['timeout'] = time();
}
if(!$session->is_logged_in()){
	redirect_to("http://localhost/moonlight/public/admin/login.php");
}
?>
<?php
//gives our forms a submit tag a name = "submit" attribute
	
	$db_column_names = Member::get_database_columns();
	$schedule_names = Schedule::get_database_columns();
	$required_cols = Member::get_required_columns();
	$data_type_cols = Member::get_data_types();
	$enum_lists = Member::get_enum_list();
	$choices = Member::get_enum_choices();
	$set_lists = Member::get_set_list();
	$set_choice = Member::get_set_choices();
	//Generates nice column names.
	$i = 0;
	
	$column_names = array();
	$error = array();
	$display = array();
	foreach($db_column_names as $column){
		$column = str_replace('_',' ', $column);
		$column = ucwords(strtolower($column));
		$column_names[$i] = $column;
		$error[$i] = '';
		$display[$i] = '';
		$i++;
	}
	$i = 0;

if(isset($_POST['submit'])){
	
	$success = 1;
	//print_r($_POST);
	$i = 0;			
	$display = array();
	$member = new Member();
	foreach($db_column_names as $dynamic){
			//field is column name and field1 is database name
			$field1 = $dynamic;
			$new;
			if(!empty(trim($_POST[$field1]))){
				/* if($dynamic == 'member_type'){
					if(trim($_POST[$field1]) != 'Member' && trim($_POST[$field1]) != 'Admin'){
						$error[$i] = "Must enter 'Member' or 'Admin' ";
						$success = 0;
					}
				}
				if($dynamic == 'marital_status'){
					if(trim($_POST[$field1]) != 'Married' && trim($_POST[$field1]) != 'Single' && trim($_POST[$field1]) != 'Engaged' && trim($_POST[$field1]) != 'Long Term Relationship'){
						$error[$i] = "Must enter 'Married', 'Single', 'Engaged' or 'Long Term Relationship' ";
						$success = 0;
					}
				}
				if($dynamic == 'skills'){
					if(trim($_POST[$field1]) != 'Cook' && trim($_POST[$field1]) != 'Drive' && trim($_POST[$field1]) != 'Knit' && trim($_POST[$field1]) != ''){
						$error[$i] = "Must enter 'Drive', 'Cook', 'Knit' or leave blank ";
						//implement these using html...
						$success = 0;
					}
				} */
				if($dynamic == 'username'){
					$user = trim($_POST[$field1]);
					$table_name = Member::get_table_name();
					$search = "SELECT * FROM ".$table_name." WHERE `username` = '{$user}' OR `email` = '{$user}'";
					if($database->query($search)){
						if($database->affected_rows() != 0){
							$success = 0;
							$error[$i] = "That username is already taken";
						}
					}
				}
				if($dynamic == 'email'){
					$mail = trim($_POST[$field1]);
					$table_name = Member::get_table_name();
					$search = "SELECT * FROM ".$table_name." WHERE `username` = '{$mail}' OR `email` = '{$mail}'";
					if($database->query($search)){
						if($database->affected_rows() != 0){
							$success = 0;
							$error[$i] = "That email is already taken";
						}
					}
				}
				$new = trim($_POST[$field1]);
				$display[$i] = $new;
				$member->set_attribute($field1, $new); //updates attributes of member in class
				
			}
			else{
				
				$dim = strcmp($_POST[$field1], '0');
							
				if($dim === 0){
					$dynamic = $_POST[$field1];
					$member->set_attribute($field1, $dynamic);
					$display[$i] = $dynamic;
					$error[$i] = '';
				}
				else if($required_cols[$i] === 0){
					$dynamic = ""; //or should it be set to 'Null'
					$member->set_attribute($field1, $dynamic);
					$display[$i] = $dynamic;
					$error[$i] = '';
				}
				else{
					$success = 0;
					$display[$i] = '';
					$error[$i] = "Field is empty"; 
				}			
			}
			$i++;
			
	} 
			
	$i = 0;	
	if($success == 1){	
		$member->create(); 
		
		$schedule_hours = array_splice($schedule_names,2);
		$days = Schedule::get_days();
		$find = $member->get_attribute('members_id');
		//put here initial values for hour1 etc...
		
		foreach($days as $day){
			$schedule = new Schedule();
			$schedule->set_attribute('members_id', $find);
			$schedule->set_attribute('day', $day);
		
			foreach($schedule_hours as $value){
				$schedule->set_attribute($value, 0);
			}
			$schedule->create();
		}		
			
		$message = "New member added successfully";
	}
	else{
		
		$message = "Could not add member";
	}
	
	
}
else{
	//form not submitted
	$message = "";
	
}

?>

<?php
	include_layout_template('admin_header.php');
?>
<html>
	<a href="index.php">&laquo; Back</a><br />
	<h2>Create New Member</h2>
		<?php echo output_message($message); ?>
	
	<body>
	
	<form action="create_member.php" method="post">
	
		<table style="width:100%" align="left">
		
				<tr><th align="left">New Member:</th><td><?php echo " Add details below"?></td></tr>			
				<?php $i = 0; //gets attributes and displays them as well as Column names.
				foreach($db_column_names as $field1){
					$field = $column_names[$i];
					
					if($data_type_cols[$i] == 'Text'){// <textarea name="Text1" cols="40" rows="5"></textarea> same as varchar for now ?>
					
						<tr><th align="left"><?php echo "{$field}"; if($required_cols[$i] == 1){ echo "*";}?></th>
						<td><input type="text" name="<?php echo "{$field1}" ?>" maxlength="30" value="<?php
					echo "{$display[$i]}"; ?>" /><?php echo " {$error[$i]} "; ?></td></tr>
					
					<?php } else if ($data_type_cols[$i] == 'Single'){ ?>
					
					<?php if($field1 == 'password'){ ?>
						<tr><th align="left"><?php echo "{$field} *";?></th>
						<td><input type="password" name="<?php echo "{$field1}" ?>" maxlength="30" value="<?php
						echo "{$display[$i]}"; ?>" /><?php echo " {$error[$i]} "; ?></td></tr>
					<?php } else { ?>
					
						<tr><th align="left"><?php echo "{$field}"; if($required_cols[$i] == 1){ echo "*";}?></th>
						<td><input type="text" name="<?php echo "{$field1}" ?>" maxlength="30" value="<?php
					echo "{$display[$i]}"; ?>" /><?php echo " {$error[$i]} "; }?></td></tr>
					
					<?php } else if ($data_type_cols[$i] == 'Integer'){?>
						
						<tr><th align="left"><?php echo "{$field}"; if($required_cols[$i] == 1){ echo "*";}?></th>
						<td><input type="text" name="<?php echo "{$field1}" ?>" maxlength="30" value="<?php
						echo "{$display[$i]}"; ?>" /><?php echo " {$error[$i]} "; ?></td></tr>
						
					<?php } else if ($data_type_cols[$i] == 'Enum'){?>
						
					<?php 
						$a = 0;
						
						for($a = 0; $a < count($enum_lists); $a++){
							
							if($enum_lists[$a] == $field1){
								break;	
							}
						}
						
						$choose = $choices[$a];						
					?>
						<tr><th align="left"><?php echo "{$field}"; if($required_cols[$i] == 1){ echo "*";}?></th>
						<td>
						<select name="<?php echo "{$field1}" ?>"><!-- have to echo each enum... so for each enum we must store enum array...-->
					<?php for($j = 0; $j < count($choose); $j++){	?>	
						<option value="<?php echo "{$choose[$j]}" ?>"><?php echo "{$choose[$j]}" ?></option>
					<?php } ?>	
						</select>
						</td>
						</tr>
					<?php } else if ($data_type_cols[$i] == 'Set'){?>
					
					<?php 
					
						$c = 0;
						for($c = 0; $c < count($set_lists); $c++){
							
							if($set_lists[$c] == $field1){
								break;	
							}
						}
						
						$set_choose = $set_choice[$c];						
					?>
						<tr><th align="left"><?php echo "{$field}"; if($required_cols[$i] == 1){ echo "*";}?></th>
						<td>
						
					<?php for($k = 0; $k < count($set_choose); $k++){ ?> 	
						<input type="checkbox" name="<?php echo "{$field1}" ?>" value="<?php echo "{$set_choose[$k]}";?>"><?php echo "{$set_choose[$k]}";?> <br>    
					<?php } ?>
						<?php echo " {$error[$i]} "; ?>
						</td>
						</tr>
					
					<?php } else if ($data_type_cols[$i] == 'Date'){?>	
					
				
				<?php
					}
					else {
					}
					$i++;
				} 				
				?>		
		
		
				<td>
					
				</td>
				<tr>
					<td colspan="2">
						<input type="submit" name="submit" value="Create" />
					</td>	
				</tr>
		</table>
			<br />
			
	</form>
<?php
	include_layout_template('admin_footer.php');
?>
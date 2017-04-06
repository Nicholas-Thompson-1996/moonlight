<?php
require_once("../../includes/initialise.php");
?>

<?php
global $database;
$id = $_SESSION['members_id']; //set default value to -1...
$temp = Member::find_by_id($id);
$table_name = Member::get_table_name();
$sql = "SELECT * FROM ". $table_name ." WHERE `members_id` = '{$id}' AND `member_type` = 'Admin'";
if($database->query($sql)){
	if($database->affected_rows() == 0){
		$session->logout();
		redirect_to("http://localhost/moonlight/public/admin/login.php");
	}
}
if((time() - $_SESSION['timeout']) > 600){
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
//IMPORTANT WHEN CREATING NEW COLUMN. USER MUST ENTER LOWER CASE FOR NAMES ONLY
//SEPERATE WORDS MUST BE SEPERATED BY _ SYMBOL.
//ALSO ABILITY TO MAKE REQUIRED COLUMN
//After a specific Column
//Default Value to prevent database query.
//column added to database column names in member.
//unique?
//length?
//ALTER TABLE `members` ADD `schedule` VARCHAR(30) NOT NULL AFTER `personal_info`; 

//call add column statically on member:
//then for each member that already exists. add attribute and assign a value.
//usually a default such as null!!!
//remind database owner this operation is costly and thus to be avoided if necessary.
//add column before adding members.

//update $db_fields i.e add  

//length can be determined using sql.
//get required columns, database types


$error1 = '';
$error2 = '';
$error3 = '';
$error4 = '';

//gives our forms a submit tag a name = "submit" attribute
if(isset($_POST['submit'])){
	
	//all fields are required here and must be set.
	//if(required = true add required
	//if(input type == whatever... do required action
	if(!empty(trim($_POST['column_name']))){
		$dynamic1 = $_POST['column_name'];
	
		if(isset($_POST['type'])){
			$type = $_POST['type'];
		
			if(isset($_POST['required'])){
				
				//for now no number in column name
				//all values set.
				$requires = $_POST['required'];
				
				
				$temp = str_replace(' ', '', $dynamic1);
				if(ctype_alpha($temp)){
					$dynamic1 = str_replace(' ', '_', $dynamic1);
					$dynamic1 = strtolower($dynamic1);
					
					if($requires == 'Yes'){
						$requires = 1;
					}
					else{
						$requires = 0;
					}
					
					if($type == "Enum" || $type == "Set"){ //redirect to another page... send in URL required, data_type  
					
						if(isset($_POST['num'])){
							
							$num = $_POST['num'];
						
						redirect_to("enum_set.php?column_name=$dynamic1&type=$type&requires=$requires&num=$num");
						//echo ""
						}
						else{
							$error4 = "For Enum and Set columns you must specify how many fields/options you requires."; 
							$message = '';
						}
							
					}
					else{
						Member::addColumn($dynamic1, $type, $requires);
						$message = "column added successfully";
						$columnName = $dynamic1;
						
						$check = Member::get_database_columns();
						foreach($check as $element){
							echo "{$element}";
						}
					}
					/* if($type == "Text"){
						
						
						if($requires == "Yes"){
							$requires = 1;
						}
						else{
							$requires = 0;
						}
					}
					else if($type == "Single"){
						
						Member::$add_data_types("Single");
						Member::$addColumn($dynamic1, "Single");
						if($requires == "Yes"){
							Member::$add_required_column(1);
						}
						else{
							Member::$add_required_column(0);
						}
					}
					else if($type == "Integer"){
						
						Member::$add_data_types("Integer");
						Member::$addColumn($dynamic1, "Integer");
						if($requires == "Yes"){
							Member::$add_required_column(1);
						}
						else{
							Member::$add_required_column(0);
						}
					}	
					else if($type == "Enum"){
						
						Member::$add_data_types("Enum");
						Member::$addColumn($dynamic1, "Enum");
						if($requires == "Yes"){
							Member::$add_required_column(1);
						}
						else{
							Member::$add_required_column(0);
						}
					}
					else if($type == "Set"){
						
						Member::$add_data_types("Set");
						Member::$addColumn($dynamic1, "Set");
						if($requires == "Yes"){
							Member::$add_required_column(1);
						}
						else{
							Member::$add_required_column(0);
						}
					} */
					
					//addColumn name = $dynamic1
					
				}
				else{
					$error1 = "Only letters and spaces allowed in column name: No numbers or other characters like ',.?!\"* "; 
					$message = 'Not Submitted';
					$columnName = '';
				}
				
				
			}
			else{
				$error2 = " Required column was not set.";
				$message = 'Not Submitted';
				$columnName = '';
				
			}
		}
		else{
			$error3 = " Type of column was not set.";
			$message = 'Not Submitted';
		}
	}
	else{
		$error1 = "Column name was not set.";
		$columnName = '';
		$message = 'Not Submitted';
		
		if(!isset($_POST['required'])){
			$error2 = " Required column was not set.";
		}
	}
	//if(isset($_POST['required']))
	//if(isset($_POST['column_name']))
	
	
	
}
else{
	//form not submitted
	$message = '';
	$columnName = '';
	$error = '';
}

?>

<?php
	include_layout_template('admin_header.php');
?>

		<h2>Add Column</h2>
		<?php echo output_message($message); ?>
		
		<form action="add_column.php" method="post">
			<table>
				<tr>
					<td>New Column Name (*)</td> 
					<td>
						<input type="text" name="column_name" maxlength="30" value="<?php
						echo htmlentities($columnName); ?>" />
					</td>
					<td><?php echo " {$error1} "; ?></td>
				</tr>
				<tr>
					<td>Required (Force the user to enter an input value?) (*) <!-- make radio field here-->
					<td>
						<input type="radio" name="required" value ="Yes">Yes <br> <!-- define defaults, iterate through current members and add attribute -->
						<input type="radio" name="required" value ="No">No <br> <!-- set to null here or define defaults -->
					</td>
					<td><?php echo " {$error2} "; ?></td>
				</tr>
				<tr>
					<td>Choose input type (*)</td>
					<td>
						<select name="type"> <!-- have to echo each enum... so for each enum we must store enum array...-->
						<option value="Text">Text/Paragraph</option>
						<option value="Single">Single</option>
						<option value="Integer">Integer</option> <!--//enum dropbox... enum can't be null... if they select null then have a blank value for null... -->
						<option value="Enum">Enum</option>
						<option value="Set">Set</option>
						<option value="Date">Date</option>
						</select>
					</td>
					<td><?php echo " {$error3} "; ?></td>
				</tr>
				<tr>
					<td>How many values would you like for your enum/set?</td>
					<td>
						<select name="num"> <!-- have to echo each enum... so for each enum we must store enum array...-->
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option> <!--//enum dropbox... enum can't be null... if they select null then have a blank value for null... -->
						<option value="5">5</option>
						<option value="6">6</option>
						<option value="7">7</option>
						<option value="8">8</option>
						<option value="9">9</option>
						<option value="10">10</option>
						<option value="11">11</option>
						<option value="12">12</option>
						<option value="13">13</option>
						<option value="14">14</option>
						<option value="15">15</option>
						</select>
					</td>
					<td><?php echo " {$error4} "; ?></td>
				</tr>
					
					<td colspan="2">
						<input type="submit" name="submit" value="Save" />
					</td>	
				
			</table>
			<br />
			
		</form>
<?php
	include_layout_template('admin_footer.php');
?>
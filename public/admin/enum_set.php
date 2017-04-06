<?php
require_once("../../includes/initialise.php");
?>

<?php
global $database; //if is set for session.. else logout
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

if(isset($_GET['requires'])){
	$req = $_GET['requires'];
	echo " r:{$req} ";
}
else{
	$req = 0;
}
if(isset($_GET['column_name'])){
	$col_name = $_GET['column_name'];
	echo " c:{$col_name} ";
}
else{
	echo "error";
}
if(isset($_GET['type'])){
	$type = $_GET['type'];
	echo " t:{$type} ";
}
else{
	$type = "Enum";
}
if(isset($_GET['num'])){
	$number = $_GET['num'];
	echo " n:{$number} ";
}
else{
	$number = 2;
}

$error = array();
$dynamic = array();
$choices = array();

for($i = 0; $i < $number; $i++){
	$dynamic[$i] = '';
	$error[$i] = '';
}
//gives our forms a submit tag a name = "submit" attribute
$success = 0;
if(isset($_POST['submit'])){
	
	//all fields are required here and must be set.
	//if(required = true add required
	//if(input type == whatever... do required action
	for($j=0; $j < $number; $j++){
		if(!empty(trim($_POST['value'.'{$j}']))){
			$dynamic[$j] = trim($_POST['value'.'{$j}']);
			$choices[$j] = $dynamic[$j];
		}	
		else{	
			$error[$j] = "Field cannot be empty";
			$success = 0;
		}
		
	}
	if($success == 1){
		//add enum/set
		
		addColumn1($col_name, $type, $req, $choices); //plus fourth optional parameter which defaults...
		//overload method... call with array parameter at end and make specific for enum and set.
		
	}
	else{
			echo " Errors have been made ";
	}
	
	
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

		<h2>Add Values for Enum or Set</h2>
		<?php echo output_message($message); ?>
		
		<form action="enum_set.php" method="post">
			<table>
				<?php for($j=0; $j < $number; $j++){ ?>
				<tr><!-- enter number of fields to add and use this to display number of fields -->
					<td>Enter Value<?php echo "{$j}"; ?></td>
					<td>
						<input type="text" name="value<?php echo "{$j}"; ?>" maxlength="30" value="<?php
						echo htmlentities($dynamic[$j]); ?>" />
					</td>
				</tr>
				<?php } ?>
				<td colspan="2">
				<input type="submit" name="submit" value="Save" />
				</td>	
				
			</table>
			<br />
			
		</form>
<?php
	include_layout_template('admin_footer.php');
?>
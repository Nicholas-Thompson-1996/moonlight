<<<<<<< HEAD
<?php
require_once('includes/initialise.php');
?>

<?php include_layout_template('admin_index_header.php'); ?>
	
<table>
	<tr>
		<td> <button class="btn--raised btn--blue g--10 m--4" onclick="location.href='/public/admin/login.php'"><h2 class="color--white ">Admin Login</h2></button> </td>
		<td> <button class="btn--raised btn--blue g--10 m--2" onclick="location.href='/public/member/login.php'"><h2 class="color--white ">Member Login</h2></button> </td>
	</tr>

	<tr>
		<td> <button class="btn--raised btn--blue g--10 m--4" onclick="location.href='index.php'"><h2 class="color--white ">Become a Member</h2></button> </td>
		<td> <button class="btn--raised btn--blue g--10 m--2" onclick="location.href='index.php'"><h2 class="color--white ">Report A Bug</h2></button> </td>
	</tr>

	
	<!-- This is the side bar of the webpage -->
	<aside class="nav--super-vertical g--2 g-m--3 g-s--6 g-t--12 no-margin-vertical">

			<div class="g--12 logo-area no-margin-vertical">
				<h4 class="color--midnight-blue no-margin-vertical">Moonlight</h4>
			</div>

			<div class="g--12 no-margin-vertical">
				<!-- the information in [] are to be fed into the website via php -->
				<a >Organisation ID: [ ]  </a>
				<a >  Total Members: [ ] </a>
				<a > Type: [ ]</a>
				<a> Tags: [ ] </a>
				<a>Description: [ ] </a>
			</div>

	</aside>
	<!--<h2>Menu</h2> -->
	<!--<ul> -->
	<!--	<li><a href="logfile.php">View Log file</a></li>
	<!--	<li><a href="view_members.php">View Members</a></li>
	<!--	<li><a href="create_member.php">Create New Member</a></li>
	<!--	<li><a href="logout.php">Logout</a></li>
	<!--</ul> 

<?php include_layout_template('admin_index_footer.php'); ?>
=======
<html>
 <head>
 <Title>Registration Form</Title>
 <style type="text/css">
     body { background-color: #fff; border-top: solid 10px #000;
         color: #333; font-size: .85em; margin: 20; padding: 20;
         font-family: "Segoe UI", Verdana, Helvetica, Sans-Serif;
     }
     h1, h2, h3,{ color: #000; margin-bottom: 0; padding-bottom: 0; }
     h1 { font-size: 2em; }
     h2 { font-size: 1.75em; }
     h3 { font-size: 1.2em; }
     table { margin-top: 0.75em; }
     th { font-size: 1.2em; text-align: left; border: none; padding-left: 0; }
     td { padding: 0.25em 2em 0.25em 0em; border: 0 none; }
 </style>
 </head>
 <body>
 <h1>Register here!</h1>
 <p>Fill in your name and email address, then click <strong>Submit</strong> to register.</p>
 <form method="post" action="index.php" enctype="multipart/form-data" >
       Name  <input type="text" name="name" id="name"/></br>
       Email <input type="text" name="email" id="email"/></br>
       <input type="submit" name="submit" value="Submit" />
 </form>
 <?php
     // DB connection info
     //TODO: Update the values for $host, $user, $pwd, and $db
     //using the values you retrieved earlier from the Azure Portal.
     $host = "eu-cdbr-azure-west-d.cloudapp.net";
     $user = "b176a0f0c09f80";
     $pwd = "f0e1202a";
     $db = "projectdatabase";
     // Connect to database.
     try {
         $conn = new PDO( "mysql:host=$host;dbname=$db", $user, $pwd);
         $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
     }
     catch(Exception $e){
         die(var_dump($e));
     }
     // Insert registration info
     if(!empty($_POST)) {
     try {
         $name = $_POST['name'];
         $email = $_POST['email'];
         $date = date("Y-m-d");
         // Insert data
         $sql_insert = "INSERT INTO registration_tbl (name, email, date)
                    VALUES (?,?,?)";
         $stmt = $conn->prepare($sql_insert);
         $stmt->bindValue(1, $name);
         $stmt->bindValue(2, $email);
         $stmt->bindValue(3, $date);
         $stmt->execute();
     }
     catch(Exception $e) {
         die(var_dump($e));
     }
     echo "<h3>Your're registered!</h3>";
     }
     // Retrieve data
     $sql_select = "SELECT * FROM registration_tbl";
     $stmt = $conn->query($sql_select);
     $registrants = $stmt->fetchAll();
     if(count($registrants) > 0) {
         echo "<h2>People who are registered:</h2>";
         echo "<table>";
         echo "<tr><th>Name</th>";
         echo "<th>Email</th>";
         echo "<th>Date</th></tr>";
         foreach($registrants as $registrant) {
             echo "<tr><td>".$registrant['name']."</td>";
             echo "<td>".$registrant['email']."</td>";
             echo "<td>".$registrant['date']."</td></tr>";
         }
          echo "</table>";
     } else {
         echo "<h3>No one is currently registered.</h3>";
     }
 ?>
 </body>
 </html>
>>>>>>> ba4243d4d591fd393f067d83a8e7b5103741dbbf

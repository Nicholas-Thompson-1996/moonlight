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
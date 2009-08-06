<?php
/**
 * Admin.php
 *
 * This is the Admin Center page. Only administrators
 * are allowed to view this page. This page displays the
 * database table of users and banned users. Admins can
 * choose to delete specific users, delete inactive users,
 * ban users, update user levels, etc.
 *
 * Written by: Jpmaster77 a.k.a. The Grandmaster of C++ (GMC)
 * Last Updated: August 2, 2009 by Ivan Novak
 */
include("../include/session.php");
?>

<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Jpmaster77's Login Script</title>
	<link rel="stylesheet" href="../-css/960/reset.css" type="text/css" />
	<link rel="stylesheet" href="../-css/960/960.css" type="text/css" />
	<link rel="stylesheet" href="../-css/960/text.css" type="text/css" />	
	<link rel="stylesheet" href="../-css/style.css" type="text/css" />
</head>
<body>

<?php
/**
 * displayUsers - Displays the users database table in
 * a nicely formatted html table.
 */
function displayUsers(){
   global $database;
   $q = "SELECT username,userlevel,email,timestamp "
       ."FROM ".TBL_USERS." ORDER BY userlevel DESC,username";
   $result = $database->query($q);
   /* Error occurred, return given name by default */
   $num_rows = mysql_numrows($result);
   if(!$result || ($num_rows < 0)){
      echo "Error displaying info";
      return;
   }
   if($num_rows == 0){
      echo "Database table empty";
      return;
   }
   /* Display table contents */
   echo "<table id='display'>";
   echo "<tr class='title'><td colspan='2'>Username</td><td>Level</td><td colspan='2'>Email</td><td colspan='2'>Last Active</td></tr>";
   echo "<div class='clear'></div>";
   for($i=0; $i<$num_rows; $i++){
      $uname  = mysql_result($result,$i,"username");
      $ulevel = mysql_result($result,$i,"userlevel");
      $email  = mysql_result($result,$i,"email");
      $time   = mysql_result($result,$i,"timestamp");

      echo "<tr><td colspan='2'>".$uname."</td><td>".$ulevel."</td><td colspan='2'>".$email."</td><td colspan='2'>".$time."</td></tr>";
   }
   echo "</table>";
}

/**
 * displayBannedUsers - Displays the banned users
 * database table in a nicely formatted html table.
 */
function displayBannedUsers(){
   global $database;
   $q = "SELECT username,timestamp "
       ."FROM ".TBL_BANNED_USERS." ORDER BY username";
   $result = $database->query($q);
   /* Error occurred, return given name by default */
   $num_rows = mysql_numrows($result);
   if(!$result || ($num_rows < 0)){
      echo "Error displaying info";
      return;
   }
   if($num_rows == 0){
      echo "<p class='grid_12'>Database table empty</p>";
      return;
   }
   /* Display table contents */
   echo "<table id='display'>";
   echo "<tr class='title'><tr colspan='2'>Username</td><td colspan='2'>Time Banned</td></tr>";
   for($i=0; $i<$num_rows; $i++){
      $uname = mysql_result($result,$i,"username");
      $time  = mysql_result($result,$i,"timestamp");

      echo "<tr><td colspan='2'>".$uname."</td><td colspan='2'>".$time."</td></tr>";
   }
   echo "</table>";
}
   
/**
 * User not an administrator, redirect to main page
 * automatically.
 */
if(!$session->isAdmin()){
   header("Location: ../main.php");
}
else{
/**
 * Administrator is viewing page, so display all
 * forms.
 */
?>
<html>
<title>Jpmaster77's Login Script</title>
<body>
<div id="main" class="container_12">

<h1>Admin Center</h1>
<font size="5" color="#ff0000">
<b>::::::::::::::::::::::::::::::::::::::::::::</b></font>
<font size="4">Logged in as <b><?php echo $session->username; ?></b></font><br><br>
Back to [<a href="../main.php">Main Page</a>]<br><br>
<?php
if($form->num_errors > 0){
   echo "<font size=\"4\" color=\"#ff0000\">"
       ."!*** Error with request, please fix</font><br><br>";
}

/**
 * Display Users Table
 */
?>
<h3>Users Table Contents:</h3>
<?php
displayUsers();
?>
<hr>
<?php
/**
 * Update User Level
 */
?>
<div class="update">
	<h3>Update User Level</h3>
	<?php echo $form->error("upduser"); ?>
	<form action="adminprocess.php" method="POST">
		<p class="grid_4">Username: <input type="text" name="upduser" maxlength="30" value="<?php echo $form->value("upduser"); ?>"></p>
		<p class="grid_2">Level:
			<select name="updlevel">
				<option value="1">1</option>
				<option value="5">5</option>
				<option value="9">9</option>
			</select>
		</p>
		<input type="hidden" name="subupdlevel" value="1">
		<input type="submit" value="Update Level">
	</form>
</div>
<hr>
<?php
/**
 * Delete User
 */
?>
<div class="update">
	<h3>Delete User</h3>
	<?php echo $form->error("deluser"); ?>
	<form action="adminprocess.php" method="POST">
		<p class="grid_4">Username: <input type="text" name="deluser" maxlength="30" value="<?php echo $form->value("deluser"); ?>"></p>
		<input type="hidden" name="subdeluser" value="1">
		<input type="submit" value="Delete User">
	</form>
</div>
<hr>
<?php
/**
 * Delete Inactive Users
 */
?>
<div class="update">
	<h3>Delete Inactive Users</h3>
	This will delete all users (not administrators), who have not logged in to the site<br>
	within a certain time period. You specify the days spent inactive.<br><br>
	<form action="adminprocess.php" method="POST">
		<p class="grid_2">Days: 	<select name="inactdays">
						<option value="3">3</option>
						<option value="7">7</option>
						<option value="14">14</option>
						<option value="30">30</option>
						<option value="100">100</option>
						<option value="365">365</option>
					</select>
		</p>
		<input type="hidden" name="subdelinact" value="1">
		<input type="submit" value="Delete All Inactive">
	</form>
</div>
<hr>
<?php
/**
 * Ban User
 */
?>
<div class="update">
	<h3>Ban User</h3><?php echo $form->error("banuser"); ?>
	<form action="adminprocess.php" method="POST">
		<p class="grid_4">Username: <input type="text" name="banuser" maxlength="30" value="<?php echo $form->value("banuser"); ?>"></p>
		<input type="hidden" name="subbanuser" value="1">
		<input type="submit" value="Ban User">
	</form>
</div>
<hr>
<?php
/**
 * Display Banned Users Table
 */
?>
<h3>Banned Users Table Contents:</h3>
<?php
displayBannedUsers();
?>
<hr>
<?php
/**
 * Delete Banned User
 */
?>
<div class="update">
	<h3>Delete Banned User</h3><?php echo $form->error("delbanuser"); ?>
	<form action="adminprocess.php" method="POST">
		<p class="grid_4">Username: <input type="text" name="delbanuser" maxlength="30" value="<?php echo $form->value("delbanuser"); ?>"></p>
		<input type="hidden" name="subdelbanned" value="1">
		<input type="submit" value="Delete Banned User">
	</form>
</div>

<hr>

Back to [<a href="../main.php">Main Page</a>]<br><br>


</div>
</body>
</html>
<?php
}
?>


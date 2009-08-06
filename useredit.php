<?
/**
 * UserEdit.php
 *
 * This page is for users to edit their account information
 * such as their password, email address, etc. Their
 * usernames can not be edited. When changing their
 * password, they must first confirm their current password.
 *
 * Written by: Jpmaster77 a.k.a. The Grandmaster of C++ (GMC)
 * Last Updated: August 2, 2009 by Ivan Novak
 */
include("include/session.php");
$page = "useredit.php";
?>

<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Jpmaster77's Login Script</title>
	<link rel="stylesheet" href="-css/960/reset.css" type="text/css" />
	<link rel="stylesheet" href="-css/960/960.css" type="text/css" />
	<link rel="stylesheet" href="-css/960/text.css" type="text/css" />	
	<link rel="stylesheet" href="-css/style.css" type="text/css" />
</head>
<body>
<div id="main" class="container_12">
<?
/**
 * User has submitted form without errors and user's
 * account has been edited successfully.
 */
if(isset($_SESSION['useredit'])){
   unset($_SESSION['useredit']);
   
   echo "<h1>User Account Edit Success!</h1>";
   echo "<p><b>$session->username</b>, your account has been successfully updated. "
       ."<a href=\"main.php\">Main</a>.</p>";
}
else{
?>

<?
/**
 * If user is not logged in, then do not display anything.
 * If user is logged in, then display the form to edit
 * account information, with the current email address
 * already in the field.
 */
if($session->logged_in){
?>

<h1>User Account Edit : <? echo $session->username; ?></h1>
<?
if($form->num_errors > 0){
   echo "<td><font size=\"2\" color=\"#ff0000\">".$form->num_errors." error(s) found</font></td>";
}
?>
<div id="userupdate">
	<form action="process.php" method="POST">
		<p class="grid_2">Name: </p><p class="left"><input class="left" type="text" name="name" maxlength="50" value="
			<?
			if($form->value("name") == ""){
				echo $session->userinfo['name'];
			}else{
				echo $form->value("name");
			}
			?>"><? echo $form->error("name"); ?></p>
		<div class="clear"></div>
		<p class="grid_2">Current Password: </p><p class="left"><input type="password" name="curpass" maxlength="30" value="<?echo $form->value("curpass"); ?>"><? echo $form->error("curpass"); ?></p>
		<div class="clear"></div>
		<p class="grid_2">New Password: </p><p class="left"><input class="left" type="password" name="newpass" maxlength="30" value="<? echo $form->value("newpass"); ?>"><? echo $form->error("newpass"); ?></p>
		<div class="clear"></div>
		<p class="grid_2">Email: </p><p class="left"><input class="left" type="text" name="email" maxlength="50" value="
			<?
			if($form->value("email") == ""){
				echo $session->userinfo['email'];
			}else{
				echo $form->value("email");
			}
			?>"><? echo $form->error("email"); ?></p>
		<div class="clear"></div>
		<p><input type="hidden" name="subedit" value="1">
		<input type="submit" value="Edit Account"></p>
	</form>
</div>
<?
echo "[<a href=\"main.php\">Main</a>] ";
echo "[<a href=\"userinfo.php?user=$session->username\">My Account</a>]&nbsp;";

}
}

?>
</div>
</body>
</html>

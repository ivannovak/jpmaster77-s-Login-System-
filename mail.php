<?php
	
/**
 * You can gather more detials by following 
 * the tutorial posted at:
 * 
 * http://ivannovak.com/email-account-activation/
 *
 * Author:  Ivan Novak
 * Last Updated: August 2, 2009 by Ivan Novak
 */
	
	
	include("include/session.php");
	
	if(!$session->logged_in){
		header("Location: ".$session->referrer);
	}
	
//	$result1=$database->query("SELECT * FROM ".TBL_USERS." WHERE username='$username'") or die(mysql_error());
//	$row100 = mysql_fetch_array($result1);

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
	<h1>User Message System</h1>
	<form method="post" action="mail.php">
		<input type="submit" name="mailAction" value="Compose" /><input type="submit" name="mailAction" value="Inbox" />
	</form>

<?php

	if(!empty($_POST['mailAction']) && isset($_POST['mailAction'])){
		$action = $_POST['mailAction'];
	} else {
		$action = 'Inbox';
	}



	if(($action == 'Compose') || ($action == 'Reply')) {
	
		if(isset($_POST['mailSubject']) && !empty($_POST['mailSubject'])){
			$mailSubject = 'Re: '.$_POST['mailSubject'];
		} else {
			$mailSubject = "";
		}
		
		if(isset($_POST['mailFrom']) && !empty($_POST['mailFrom'])){
			$mailTo = $_POST['mailFrom'];
		} else {
			$mailTo = "";
		}
		
		
		?>
			<form action="mail.php" method='post'>
				<div id="compose">
					<p class="grid_1">To:</p><p class="grid_2"><input type='text' name='mailTo' size='20' value='<?php echo $mailTo; ?>'></p>
					<p class="grid_1 clear">Subject:</p><p class="grid_2"><input type='text' name='mailSubject' size='20' value='<?php echo $mailSubject; ?>'></p>
					<p class="grid_1 clear">Message:</p><p class="grid_4"><textarea rows='16' cols='45' name='mailMessage'></textarea></p>
					<p class="grid_1 clear"><input type="submit" name="mailAction" value="Send" /></p>
				</div>
			</form>
		<?php
	}
	
	
	if($action == 'Send') {
			
		if(empty($_POST['mailSubject']) || !isset($_POST['mailSubject'])){
			echo "Subject Blank";
		} else {
			$subject = $_POST['mailSubject'];
		}
		
		if(empty($_POST['mailTo']) || !isset($_POST['mailTo'])){
			echo "To Blank";
		} else {
			$mailTo = $_POST['mailTo'];
		}
		
		if(empty($_POST['mailMessage']) || !isset($_POST['mailMessage'])){
			echo "Message Blank";
		} else {
			$message = $_POST['mailMessage'];
		}
		
		$date = date('m/d/Y')." at ".date('g:i.s')." ".date('a');
		
		$q	= "INSERT INTO mail (UserTo, UserFrom, Subject, Message, SentDate, status) 
						VALUES ('$mailTo','$session->username','$subject','$message','$date','unread')";
		if(!($send = $database->query($q))){
			echo "A letter could not be sent to ".$mailTo."!";
		} else {
			echo "Message Sent to ".$mailTo."!";
		}
		
	}
	
	
	if($action == "Inbox") {
	
		$user = $session->username;
		$q = "SELECT * FROM mail WHERE UserTo = '$user' ORDER BY SentDate DESC";
		$getMail = $database->query($q) or die(mysql_error());

		echo "<div id='inbox'>";
		
		if(mysql_num_rows($getMail) == 0){
			echo "<p>you have no mail</p><br /><br />";
		} else {			
			?>
			<table>
				<tr class="title">
					<td colspan="2" align="center">Action</td>
					<td>Status</td>
					<td>From</td>
					<td>Subject</td>
					<td>Time</td>
				</tr>
			</div>
			<?php
			echo "<form action='mail.php' method='post'>";
			while($mail = mysql_fetch_array($getMail)){
				?>
					<tr>
						<input type="hidden" name="mail_id" value="<?php echo $mail['mail_id']; ?>" />
						<td align="center"><input type="submit" name="mailAction" value='View' /></td>
						<td align="center"><input type="submit" name="mailAction" value="Delete" /></td>
						<td><?php echo $mail['status']; ?></td>
						<td><?php echo $mail['UserFrom']; ?></td>
						<td><?php echo $mail['Subject']; ?></td>
						<td><?php echo $mail['SentDate']; ?></td>
					</tr>
				<?php
			}

			echo "</form>";
		}			
		echo "</table>";
	
	}
	
	
	if($action == "View") {
	
		
		$mail_id = $_POST['mail_id'];
		$user = $session->username;
		$result = $database->query("SELECT * FROM mail WHERE UserTo = '$user' AND mail_id = '$mail_id'") or die ("cant do it");
		$row = mysql_fetch_array($result);
		
		
		if($row['UserTo'] != $session->username) {
			echo "<font face=verdana><b>This isn't your mail!";
			exit;
		}
		
		$q = "UPDATE mail SET status='read' WHERE UserTo='$session->username' AND mail_id='$row[mail_id]'";
		$database->query($q) or die("An error occurred resulting that this message has not been marked read.");
		
		?>
			<form method="post" action="mail.php">
				<div id="single">
					<p class="grid_1">From: </p><p class="grid_2"><?php echo $row['UserFrom']; ?><input type="hidden" name="mailFrom" value="<?php echo $row['UserFrom']; ?>" /></p>
					<p class="grid_1 clear">Subject: </p><p class="grid_2"><?php echo $row['Subject']; ?><input type="hidden" name="mailSubject" value="<?php echo$row['Subject']; ?>" /></p>
					<p class="grid_4 clear">body: <br /><?php echo $row['Message']; ?><br /></p>
					<p class="grid_4 clear" align="right"><input type="submit" name="mailAction" value="Reply" /></p>
				</div>
			</form>
		<?php
	}
	
	
	if($action == 'Delete') {
		$id = $_POST['mail_id'];
		$query = $database->query("DELETE FROM mail WHERE mail_id='$id' LIMIT 1");
		
		if(!$query) {
			echo "The message wasn\'t deleted";
		} else {
			header("Location: mail.php");
		}
	}
echo "<div class='clear'>";
echo "[<a href=\"main.php\">Main</a>]&nbsp;";
echo "[<a href=\"userinfo.php?user=$session->username\">My Account</a>]&nbsp;";
echo "[<a href=\"process.php\">Logout</a>]";
echo "</div>";
?>

</div>
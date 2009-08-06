<?php

/**
 * author.php
 * You can gather more details by following the
 * Adding Protected Pages to the Jp77 System
 * tutorial found here: http://ivannovak.com/b5rj
 */

include("include/session.php");

if(!$session->isAuthor()){
	header("Location: main.php");
} else {
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
<p>you have author privileges</p>
</div>

</body>
</html>
<?php
}
?>
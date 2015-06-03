<?php

require_once('aes.php');
require_once('login.php');

if(isset($_SESSION['login_user'])) {
  header("location: viewer.php");
}


?>

<html>
<head>
<title> -- Login -- </title>
</head>

<body>
<div id = "login">
<form action="login.php" method="post" autocomplete="off">
<p>
<label>UserName :</label>
<input id="name" name="username" placeholder="username" type="text">
</p>
<p>
<label>Password :</label>
<input id="password" name="password" placeholder="****" type="password">
</p>
<p>
<input name="submit" type="submit" value="Login">
</p><p>
<label>New user?</label>
<input type="checkbox" name="newuser" value="newuser">
</p>
<span><?php echo $error; ?></span>
</form>
</div>
</body>
</html>


<?php
require "rpcxml.php";

$username = null;
$password = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	if(!empty($_POST["username"]) && !empty($_POST["password"])) {
		$username = $_POST["username"];
		$password = $_POST["password"];
		
$oneauth=$username.":".$password;
$request = xmlrpc_encode_request('one.user.info',array($oneauth,-1));
$r = do_call($request);
#		if($username == 'user' && $password == 'password') 
		if(isset($r['ID']))

		    {
			session_start();
			$_SESSION["authenticated"] = 'true';
			$_SESSION["oneauth"] = $oneauth;
			$_SESSION["user"]=$r;
			header('Location: index.php');
		}
		else {
			header('Location: login.php');
		}
		
	} else {
		header('Location: login.php');
	}
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">
	<title>Opennebula-Login</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<div align=center id="page" style="border:1px solid grey; margin : 0 auto; width:300px; height:200px;">
	<!-- [banner] -->
	<header id="banner">
		<hgroup>
			<h1>Login</h1>
		</hgroup>		
	</header>
	<!-- [content] -->
	<section id="content">
		<form id="login" method="post">
			<label for="username">Username:</label>
			<input id="username" name="username" type="text" required><p>
			<label for="password">Password:</label>
			<input id="password" name="password" type="password" required>					
			<p />
			<input type="submit" value="Login">
		</form>
	</section>
	<!-- [/content] -->
	
</div>
<!-- [/page] -->
</body>
</html>
<?php } ?>

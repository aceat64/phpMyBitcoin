<html>
<head>
<title>phpMyBitcoin Installer</title>
</head>
<body>
<h1>phpMyBitcoin Installer</h1>
<?php

// Check for __SECURITY_SALT__ and __SECURITY_CIPHERSEED__ in core.php, if they aren't there, die
$core_content = file_get_contents('../config/core.php');
if(strpos($core_content,'__SECURITY_SALT__') === false || strpos($core_content,'__SECURITY_CIPHERSEED__') === false) {
	die('The installer has already been run!');
}

if(!empty($_POST)) {
	// TODO: Verify that this will prevent injecting PHP code into database.php, THIS IS KIND OF A BIG DEAL
	foreach($_POST as &$post) {
		$post = addslashes($post);
	}

	$link = mysqli_connect($_POST['hostname'],$_POST['username'],$_POST['password'],$_POST['database']);

	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		echo '<h2>Unable to connect to MySQL database, please check your settings and try again!</h2>';
	} else {
		$sql = file_get_contents('../config/schema/database.sql');
		if (!mysqli_multi_query($link,$sql)) {
			die('MySQL error: ' . mysqli_error($link));
		}

		$chars = array_merge(range('A', 'Z'), range('a', 'z'),range(0, 9));
		$security_salt = ''; 

		for ($c = 0; $c < 32; $c++) { 
			$security_salt .= $chars[mt_rand(0,count($chars)-1)]; 
		}

		$security_cipherseed = '';
		while (strlen($security_cipherseed) <= 32) {
			$security_cipherseed .= mt_rand();
		}

		// Replace __SECURITY_SALT__ and __SECURITY_CIPHERSEED__ in core.php
		$core_content = str_replace('__SECURITY_SALT__',$security_salt,$core_content);
		$core_content = str_replace('__SECURITY_CIPHERSEED__',$security_cipherseed,$core_content);
		file_put_contents('../config/core.php',$core_content);

		// Setup the database.php file
		$database_content = file_get_contents('../config/database.php');
		$database_content = str_replace('__DRIVER__','mysqli',$database_content);
		$database_content = str_replace('__HOSTNAME__',$_POST['hostname'],$database_content);
		$database_content = str_replace('__USERNAME__',$_POST['username'],$database_content);
		$database_content = str_replace('__PASSWORD__',$_POST['password'],$database_content);
		$database_content = str_replace('__DATABASE__',$_POST['database'],$database_content);
		$database_content = str_replace('__PREFIX__',$_POST['prefix'],$database_content);
		file_put_contents('../config/database.php',$database_content);

		?>
	<p>Create the first user account:</p>
	<form action="users/installer" method="post">
		<input type="hidden" name="data[User][salt]" value="<?php echo $security_salt; ?>" />
		Username: <input type="text" name="data[User][username]" /><br />
		Password: <input type="password" name="data[User][password]" /><br />
		<input type="submit" value="Submit" />
	</form>
</body>
</html>
		<?php

		die();
	}
}

$writeable = array(
	'app/config/core.php' => '../config/core.php',
	'app/config/database.php' => '../config/database.php',
	'app/tmp' => '../tmp/',
	'app/tmp/cache' => '../tmp/cache',
	'app/tmp/cache/models' => '../tmp/cache/models',
	'app/tmp/cache/persistent' => '../tmp/cache/persistent',
	'app/tmp/cache/views' => '../tmp/cache/views',
	'app/tmp/logs' => '../tmp/logs',
);

$errors = 0;

foreach($writeable as $name => $item) {
	if (!is_writeable($item)) {
		echo '<p>Can write to '.$name.'? NO!</p>';
		$errors++;
	}
}

if($errors == 0) {
	?>
	<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
		Hostname: <input type="text" name="hostname" /><br />
		Username: <input type="text" name="username" /><br />
		Password: <input type="password" name="password" /><br />
		Database: <input type="text" name="database" /><br />
		Table Prefix: <input type="text" name="prefix" /><br />
		<input type="submit" value="Submit" />
	</form>
	<?php
} else {
	echo '<p>Please resolve the permissions errors before continuing.</p>';
}
?>
</body>
</html>

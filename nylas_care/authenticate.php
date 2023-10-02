

<?php
	
//set session
if(!isset($_SESSION['uid']) || (trim($_SESSION['uid']) == '')) {
//$username=strip_tags($_GET['username']);
		header("location: index.php");
		exit();
	}


?>
<?php


$ipaddress = '';
if (getenv('HTTP_CLIENT_IP'))
	$ipaddress = getenv('HTTP_CLIENT_IP');
else if(getenv('HTTP_X_FORWARDED_FOR'))
	$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
else if(getenv('HTTP_X_FORWARDED'))
	$ipaddress = getenv('HTTP_X_FORWARDED');
else if(getenv('HTTP_FORWARDED_FOR'))
	$ipaddress = getenv('HTTP_FORWARDED_FOR');
else if(getenv('HTTP_FORWARDED'))
	$ipaddress = getenv('HTTP_FORWARDED');
else if(getenv('REMOTE_ADDR'))
	$ipaddress = getenv('REMOTE_ADDR');
else
	$ipaddress = 'UNKNOWN';




/* LOGIN ADMIN */
if(isset($_POST['login'])){

	if(!isset($_SESSION['attempts'])){
		$_SESSION['attempts'] = 0;
    }
    
	if(isset($_SESSION['timer']) && (time() - $_SESSION['timer'] > 300)){
		session_unset($_SESSION['timer']);
		session_destroy();
	}

if(!isset($_SESSION['timer'])){

    // HERE PUT YOUR DB QUERY TO VERIFY USER 
    // Only an example
    $user = $db->verifyUser($variable);


	if ($user){
		$_SESSION['users']        = $user;
		header('Location: homepage.php');
	}else{
		$_SESSION['attempts'] = $_SESSION['attempts'] + 1;
		if($_SESSION['attempts'] < 3){
			$_SESSION['validation'] = 'Please check your credentials !';
			header('Location: index.php');
		}else{
			$_SESSION['timer'] 			  = time();
			$_SESSION['validation']	  	  = $ipaddress . ' is locked for a period of 5 minutes ! please try again later';
			$_SESSION['validation']['ip'] = $ipaddress;
			unset($_SESSION['attempts']);
			header('Location: index.php');
		}
	} // else if user not validated 
}else{
	$time       = time();
	$remaining  = $time - $_SESSION['timer'];
	$remain     = 300 - $remaining ;
	unset($_SESSION['attempts']);
	$_SESSION['validation']	  	  = $ipaddress . ' is locked for a period of '. (round($remain / 60)) . ' ! please try again later';
	header('Location: index.php');
}


} // end of login form


?>
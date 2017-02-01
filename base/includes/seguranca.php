<?php
include_once('includes/config.php');

if( file_exists ('base/classes/DB.class.php') ){
	require_once( 'base/classes/DB.class.php' );
	require_once( 'base/classes/Funcao.class.php' );
}else{
	require_once( '../base/classes/DB.class.php' );	
	require_once( '../base/classes/Funcao.class.php' );	
}
$database = new DB();

session_save_path('base/tmp/session');
ini_set('session.gc_probability', 1);

session_start();


function idBrowser($useragent) {
//	$useragent = $_SERVER['HTTP_USER_AGENT'];
	
  if (preg_match('|MSIE ([0-9].[0-9]{1,2})|',$useragent,$matched)) {
    $browser_version=$matched[1];
    $browser = 'IE';
  } elseif (preg_match( '|Opera/([0-9].[0-9]{1,2})|',$useragent,$matched)) {
    $browser_version=$matched[1];
    $browser = 'Opera';
  } elseif(preg_match('|Firefox/([0-9\.]+)|',$useragent,$matched)) {
    $browser_version=$matched[1];
    $browser = 'Firefox';
  } elseif(preg_match('|Chrome/([0-9\.]+)|',$useragent,$matched)) {
    $browser_version=$matched[1];
    $browser = 'Chrome';
  } elseif(preg_match('|Safari/([0-9\.]+)|',$useragent,$matched)) {
    $browser_version=$matched[1];
    $browser = 'Safari';
  } else {
    // browser not recognized!
    $browser_version = 0;
    $browser= 'other';
  }
  return "$browser $browser_version";
}


function getIp() {
	/*  $ipaddress = '';
     if ($_SERVER['HTTP_CLIENT_IP'])
         $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
     else if($_SERVER['HTTP_X_FORWARDED_FOR'])
         $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
     else if($_SERVER['HTTP_X_FORWARDED'])
         $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
     else if($_SERVER['HTTP_FORWARDED_FOR'])
         $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
     else if($_SERVER['HTTP_FORWARDED'])
         $ipaddress = $_SERVER['HTTP_FORWARDED'];
     else if($_SERVER['REMOTE_ADDR'])
         $ipaddress = $_SERVER['REMOTE_ADDR'];
     else
         $ipaddress = 'UNKNOWN';

     return $ipaddress; */
	
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

     return $ipaddress; 
}



function validaPagina($local) {
	if (!isset($_SESSION['userId']) OR !isset($_SESSION['userNome'])) {
		expulsaVisitante($local); 
		return false;
	}
	return true;
}


function expulsaVisitante($local) {
	$database = new DB();
	$log = array( 'data_encer' => date('Y-m-d H:i:s')
				,'status' => 2
				);
	$query = $database->update( 'log_sessao', $log, array( 'id' => $_SESSION['sessionId'] ), 1 );
	if( $query ){
		unset($_SESSION['userId'], $_SESSION['userNome'], $_SESSION['perfilId'], $_SESSION['sessionId']);
		session_destroy();
		session_unset();
		session_write_close();
		
	//	header("Location: index.php?error=".$local);
		echo '<META HTTP-EQUIV="Refresh" CONTENT="1;URL=index.php?error='.$local.'">';
	}
}



?>
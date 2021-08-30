<?php

function whattimeisit(){


	// TIME INTERVAL
	// $tz = isset($_GET['tz']) ? strip_tags($_GET['tz']) : 'America/Toronto';
	// date_default_timezone_set( $tz );
	// $twilight = json_decode( file_get_contents('https://api.sunrise-sunset.org/json?lat=43.6532&lng=-79.3832&date=today&formatted=0'));
	// $sunrise = strtotime( $twilight->results->sunrise );
	// $sunset = strtotime( $twilight->results->sunset );
	// if( $sunrise < time() && time() < $sunset ){
		// echo 'sun';
	// }else{
		// echo 'night';
	// }
	// exit;




	extract( parse_ini_file('config/ibm.ini') );  
	
	$temp_file = sys_get_temp_dir().'/WAV_'.uniqid();
	
	// file_put_contents( '/var/www/app75/files/5555516/media/request.txt', print_r($_REQUEST,true) );

	IBM::say("Toronto time is ".date('g:i A').'', $apikey, $temp_file);
	
	while( ob_get_level() ){ ob_get_clean(); }	
	
	header('Content-Length: '.filesize( $temp_file ));
	header('Content-Type: audio/x-wav');
	header('Connection: close');

	// echo file_get_contents( $temp_file ); exit;
	
	if( $file = fopen($temp_file, "r") ){
		while( !feof($file) ){
			echo fgets($file, 5000);
			flush();
		}
		fclose($file);
	}
	
	exit;
	
}
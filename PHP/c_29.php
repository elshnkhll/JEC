<?php

/* 
TEST URL: http://robocallz.com/?q=c_29&mode=debug&frm=1&tz=Asia/Hong_Kong&vlt=1400
AVILABLE $_GET keys:
    ['tz']   => America/Toronto - time zone
    ['btn']  => 36 means first request after reset button
    ['rssi'] => wifi signal strength - -33
    ['vlt']  => battery level [0:100] - 45
    ['mac']  => device mac address  - 24:6F:28:77:E1:30
    ['lcip'] => local network IP - 192.168.2.25

*/	


function c_29() {

	$dbg = (string)@$_GET['mode'] == 'debug';
	$dbg2 = (string)@$_GET['mode'] == 'debug2';
	$frm = (int)@$_GET['frm'];
	
	$tz = isset($_GET['tz']) ? strip_tags($_GET['tz']) : 'America/Toronto';
	date_default_timezone_set( $tz );
	// date_default_timezone_set( 'Asia/Dubai' );
	
	$scrn_wdth = 296;
	$scrn_hght = 128;
	$size = (int)($scrn_wdth * $scrn_hght / 8);
	$invert = false;
	$frnt_lght = -1;
	$flip_x = 0;
	$flip_y = 0;
	$frm_bffrs = '';
	$images_rr = array();
	
	
	include( __DIR__ . '/../include/frame_0.php');

	// voltage
	include(__DIR__ . '/../include/frame_3.php');

	// charge needed !!!
	if( (int)$_GET['vlt'] < 27 ) $images_rr[0] = $images_rr[ $last_id ];
	
	// OPTIONALLY:
	if( ((int)@$_GET['btn'] == 36) || strpos($dt, ':55') ){
		// weather
		include(__DIR__ . '/../include/frame_1.php');
		
		$now_int = strtotime($dt);
		
		$frnt_lght = ($sunrise<$now_int) && ($now_int<$sunset) ? '0' : '1' ;

		// info
		include(__DIR__ . '/../include/frame_2.php'); 
	}
	
	// see it in browser
	if( $dbg ){
			header('Content-type: image/png');
			imagepng( $images_rr[ $frm ] );
			exit;
	}else{
		
		header_remove();
		while( ob_get_level() ){ ob_get_clean(); }

		$now = DateTime::createFromFormat('U.u', microtime(true));	
		$scnds = 60 - $now->format("s.u");
		header("X-ESP32-Time: $dt:$scnds");
		header('X-ESP32-Seconds: '.$scnds);
		
		if( $frnt_lght > -1 ){
			header("X-ESP32-FrontLight: $frnt_lght");
		}
		
		if( (int)$_GET['vlt'] < 25 ) header("X-ESP32-Sleep: 1");
	
		
		if( $dt == '9:00' ){
			// header('X-ESP32-Alarm: /Glitch_Techs.wav');
			// header('X-ESP32-Alarm: /Alley_Cat.wav');
		}		
		
			
		if( strpos($dt, '8:55') ){
			header('X-ESP32-Alarm: /app75/?q=whattimeisit&t='.uniqid());
		}

		// header("X-ESP32-SetPref: abcdefg|ikglmnoprstuf");

		header("X-ESP32-FrameBuffers: $frm_bffrs");
		$size = $size * strlen( $frm_bffrs );
		header("X-Content-length: $size");
		
		foreach( $images_rr as $k=>$v){
			for($x = 0; $x < $scrn_wdth; $x++) {
				$xx = ( $flip_x ) ? $scrn_wdth - $x - 1 : $x;
				$row = '';
				for($y = 0; $y < $scrn_hght; $y++) {
					$yy = ( $flip_y ) ? $scrn_hght - $y - 1 : $y;
					$rgb = imagecolorat($images_rr[$k], $xx, $yy);
					$row = $row.( (($rgb & 0xFF) < 128) ? '0' : '1' );
					// you can invert image by swaping --  ^     ^
					if( strlen($row) == 8 ){
						if( $dbg2 ){
							echo $row;
						}else{
							echo chr( bindec( $row ) );
						}
						flush(); 
						$row = '';
					}
				}
				if( $dbg2 ){
					echo "\n";
				}
			}
		}
	}	
	
	
	// log battery charge level
	//if( strpos($dt, ':00') || strpos($dt, ':15') || strpos($dt, ':30') || strpos($dt, ':45') ){
		//exec('php channels/clock/scripts/log_voltage.php \'{"vlt" : '.(int)$_GET['vlt'].'}\' > /dev/null 2>&1 &');
	//}
	
}




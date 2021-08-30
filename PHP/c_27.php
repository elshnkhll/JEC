<?php

	// http://46.101.154.176/JEC/c1.php?mode=debug
function c_27() {
	
	$dbg = (string)@$_GET['mode'] == 'debug';
	$dbg2 = (string)@$_GET['mode'] == 'debug2';
	$scrn_wdth = 264;
	$scrn_hght = 176;
	$size = (int)($scrn_wdth * $scrn_hght / 8);
	
	$f_p = array(
		'0' => array('x' =>   9, 'w' => 55),
		'1' => array('x' =>  74, 'w' => 29),
		'2' => array('x' => 114, 'w' => 55), 
		'3' => array('x' => 181, 'w' => 55),
		'4' => array('x' => 245, 'w' => 55),
		'5' => array('x' => 309, 'w' => 55),
		'6' => array('x' => 373, 'w' => 55),
		'7' => array('x' => 436, 'w' => 59),
		'8' => array('x' => 502, 'w' => 55),
		'9' => array('x' => 566, 'w' => 55),
		':' => array('x' => 630, 'w' => 22)
	);

	$src = imagecreatefrompng('channels/clock/include/Cataclysmo_1.png');
	$img = imagecreatetruecolor($scrn_wdth, $scrn_hght);	
	$wht = imagecolorallocate($img, 255, 255, 255);
	imagefill($img, 0, 0, $wht);

	$tz = isset($_GET['tz']) ? strip_tags($_GET['tz']) : 'America/Toronto';
	date_default_timezone_set( $tz );
	$dt = date('g:i');
	$array = str_split($dt);
	$wdth = 0;
	foreach($array as $char) {
		$wdth = $wdth + $f_p[$char]['w'];
	}
	
	$dstntn_x = (int)(($scrn_wdth - $wdth - 3*8) / 2);
	foreach($array as $char) {
		imagecopy($img, $src, $dstntn_x, 5, $f_p[$char]['x'], 0, $f_p[$char]['w'], $scrn_hght);
		$dstntn_x = $dstntn_x + $f_p[$char]['w'] + 8;
	}
	
	if( $dbg ){
		header('Content-type: image/png');
		imagepng( $img );
	}else{
		
		while( ob_get_level() ){ ob_get_clean(); }	
		$scnds = date('s');
		header("Connection: keep-alive");
		header("X-ESP32-Time: $dt:".date('s'));
		header('X-ESP-Seconds:'.date('s'));
		header("Content-length: $size");
		header("X-Content-length: $size");
		for($x = 0; $x < $scrn_wdth; $x++) {
			$row = '';
			for($y = $scrn_hght-1; $y >= 0; $y--) {
				$rgb = imagecolorat($img, $x, $y);
				$row = $row.( (($rgb >> 16) < 128) ? '0' : '1' );
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
	
	imagedestroy($img);
	exit;

}
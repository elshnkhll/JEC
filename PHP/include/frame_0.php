<?php

	$f_p = array(
		'0' => array('x' =>   2, 'w' => 61),
		'1' => array('x' =>  73, 'w' => 36),
		'2' => array('x' => 121, 'w' => 57), 
		'3' => array('x' => 187, 'w' => 58),
		'4' => array('x' => 253, 'w' => 73),
		'5' => array('x' => 332, 'w' => 58),
		'6' => array('x' => 404, 'w' => 61),
		'7' => array('x' => 472, 'w' => 58),
		'8' => array('x' => 538, 'w' => 61),
		'9' => array('x' => 613, 'w' => 61),
		':' => array('x' => 687, 'w' => 23)
	);
	$img = imagecreatetruecolor($scrn_wdth, $scrn_hght);
	$wht = imagecolorallocate($img, 255, 255, 255);
	$blk = imagecolorallocate($img, 0, 0, 0);
	imagefill($img, 0, 0, $wht);
	
	$src = imagecreatefrompng('channels/clock/include/teko_regular.png');

	$dt = date('g:i', strtotime("+1 minutes"));

	$array = str_split($dt);
	$wdth = 0;
	foreach($array as $char) {
		$wdth = $wdth + $f_p[$char]['w'];
	}
	
	$dstntn_x = (int)(($scrn_wdth - $wdth - 3*8) / 2);
	foreach($array as $char) {
		imagecopy($img, $src, $dstntn_x, 0, $f_p[$char]['x'], 0, $f_p[$char]['w'], $scrn_hght);
		$dstntn_x = $dstntn_x + $f_p[$char]['w'] + 8;
	}
	
	// $img = imagerotate($img, 180, 0 ); 
	// $img = imageflip($img, IMG_FLIP_VERTICAL);
	imagefilter($img, IMG_FILTER_GRAYSCALE);
	imagefilter($img, IMG_FILTER_CONTRAST, -100);

	
	$frm_bffrs = $frm_bffrs.'0';
	
	$images_rr[] = imagecreatetruecolor($scrn_wdth, $scrn_hght);	
	
	end( $images_rr );
	$last_id = key( $images_rr );

	imagecopy($images_rr[$last_id], $img, 0, 0, 0, 0, $scrn_wdth, $scrn_hght);

	// imagedestroy($img);
	
<?php

	
	$img = imagecreatetruecolor($scrn_wdth, $scrn_hght);
	$wht = imagecolorallocate($img, 255, 255, 255);
	$blk = imagecolorallocate($img, 0, 0, 0);
	imagefill($img, 0, 0, $wht);

	$bttr = imagecreatefrompng( 'channels/clock/include/battery-charging-icons.PNG' );

	$V = (int)$_REQUEST['vlt'];
	if( $V < 30 ){
		$rng = 0;
	}elseif( $V < 40 ){
		$rng = 1;
	}elseif( $V < 50 ){
		$rng = 2;
	}elseif( $V < 60 ){
		$rng = 3;
	}elseif( $V < 70 ){
		$rng = 4;
	}elseif( $V < 80 ){
		$rng = 5;
	}elseif( $V < 90 ){
		$rng = 6;
	}else{
		$rng = 7;
	}
	
	imagecopy($img, $bttr, 50, 3, 0, $rng*121, 200, 120);

	imagettftext($img, 14, 90, 16, 110, $blk, 'channels/clock/include/mplus-1m-light.ttf', $V);

	// $img = imagerotate($img, 180, 0 ); 
	imagefilter($img, IMG_FILTER_GRAYSCALE);
	imagefilter($img, IMG_FILTER_CONTRAST, -100);
	
	$frm_bffrs = $frm_bffrs.'3';
	
	$images_rr[] = imagecreatetruecolor($scrn_wdth, $scrn_hght);	
	
	end( $images_rr );
	$last_id = key( $images_rr );

	imagecopy($images_rr[$last_id], $img, 0, 0, 0, 0, $scrn_wdth, $scrn_hght);	
	
	imagedestroy($img);
	
	// $front_light_on = 5;
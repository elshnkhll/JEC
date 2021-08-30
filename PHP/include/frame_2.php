<?php
	
	$txt = strtoupper( date("D, M j Y, g:i a") )."\n"
			.'IP:   '.$_SERVER['REMOTE_ADDR']."\n"
			.'L.IP: '.@$_GET['lcip']."\n"
			.'MAC:  '.@$_GET['mac']."\n"
			.'BAT:  '.sprintf("%.2f", (float)@$_GET['vlt']/564.04).'v ('.@$_GET['vlt'].')'."\n"
			.'RSSI: '.@$_GET['rssi'].'dBm'."\n";

	$img = imagecreatetruecolor($scrn_wdth, $scrn_hght);
	$wht = imagecolorallocate($img, 255, 255, 255);
	$blk = imagecolorallocate($img, 0, 0, 0);
	imagefill($img, 0, 0, $wht);
	
	imagefttext($img, 15, 0, 10, 20, $blk, 'channels/clock/include/mplus-1m-medium.ttf', $txt, array());

	// $img = imagerotate($img, 180, 0 ); 
	imagefilter($img, IMG_FILTER_GRAYSCALE);
	imagefilter($img, IMG_FILTER_CONTRAST, -100);
	
	$frm_bffrs = $frm_bffrs.'2';

	$images_rr[] = imagecreatetruecolor($scrn_wdth, $scrn_hght);	
	
	end( $images_rr );
	$last_id = key( $images_rr );

	imagecopy($images_rr[$last_id], $img, 0, 0, 0, 0, $scrn_wdth, $scrn_hght);

	imagedestroy($img);

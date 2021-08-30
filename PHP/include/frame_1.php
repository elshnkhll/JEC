<?php

	global $img;

  //handle request
  $city = 'Toronto'; // @$_GET['city'];
  $api_key = '1234567890123456789012345678901234567890';
  $response = file_get_contents("http://api.openweathermap.org/data/2.5/forecast?q=$city&appid=$api_key&units=imperial");
  try {
	  
	  
	$payload = json_decode($response);
    $weather = $payload->list[0];
	$sunrise = $payload->city->sunrise;
	$sunset  = $payload->city->sunset;	

    $data = array(
      'temperature' => round($weather->main->temp, 1),
      'humidity' => $weather->main->humidity,
      'feels_like' => round($weather->main->feels_like, 1),
      'wind_speed' => round($weather->wind->speed),
      'pressure' => $weather->main->pressure,
      'icon' =>  get_icon_image($weather->weather[0]->icon),
      'direction' => $weather->wind->deg
    );

    if ($data['direction'] <= 90) $data['direction'] = 'N';
    else if ($data['direction'] <= 180) $data['direction'] = 'E';
    else if ($data['direction'] <= 270) $data['direction'] = 'S';
    else if ($data['direction'] >= 270) $data['direction'] = 'W';



	$img = imagecreatetruecolor($scrn_wdth, $scrn_hght);
	$wht = imagecolorallocate($img, 255, 255, 255);
	$blk = imagecolorallocate($img, 0, 0, 0);
	imagefill($img, 0, 0, $wht);

	create_image($data, $img);

	// $img = imagerotate($img, 180, 0 ); 
	imagefilter($img, IMG_FILTER_GRAYSCALE);
	imagefilter($img, IMG_FILTER_CONTRAST, -100);
	$frm_bffrs = $frm_bffrs.'1';
	$images_rr[] = imagecreatetruecolor($scrn_wdth, $scrn_hght);	
	end( $images_rr );
	$last_id = key( $images_rr );
	imagecopy($images_rr[$last_id], $img, 0, 0, 0, 0, $scrn_wdth, $scrn_hght);	
	imagedestroy($img);

    

  }catch( Exception $e) {
    echo 'Error';
  }









function create_image($data, $imgm) {
  #image that will see the user
  $font = __DIR__ . '/../include/fonts.ttf';

  
  
  
  $pressure = $data['pressure'];
  $temperature = (int)$data['temperature'];
  $feels_like =  (int)$data['feels_like'];
  $icon_link = $data['icon'];
  $direction = $data['direction']; //wind direction
  $wind_speed = $data['wind_speed'];
  $humidity = $data['humidity'];


  //here will be 6 images in main component
  //first image - temperature
  $img1 = imagecreatetruecolor(170, 60);
  make_white_background($img1);
  $black = 0-imagecolorallocate($img1, 0, 0, 0);

  $size_change = strlen($temperature) * 20; #if temperature > 10 then need to change position
  imagettftext($img1, 44, 0, 12, 50, $black, $font, $temperature);
  imagettftext($img1, 16, 0, 30 + $size_change, 25, $black, $font, '°F');
  imagettftext($img1, 10, 0, 30 + $size_change, 38, $black, $font, 'Feels');
  imagettftext($img1, 10, 0, 30 + $size_change, 50, $black, $font, 'like');
  imagettftext($img1, 18, 0, 66 + $size_change, 48, $black, $font, $feels_like );

  #icon
  $img2 = imagecreatefromjpeg( $icon_link );


  #wind
  $img3 = imagecreatetruecolor(80, 50);
  make_white_background($img3);
  $black = 0-imagecolorallocate($img3, 0, 0, 0);
  imagettftext($img3, 12,0,5,17,$black,$font,'WIND');
  imagettftext($img3, 18,0, 5,45, $black, $font, $direction.$wind_speed.'mph');
  // imagettftext($img3, 10,0,35,35, $black, $font, $direction);
  // imagettftext($img3, 10,0,35,50, $black, $font, 'km/h');


  #humidity
  $img4 = imagecreate(80,50);
  make_white_background($img4);
  $black = 0-imagecolorallocate($img4, 0, 0, 0);
  imagettftext($img4, 12,0,5,17,$black,$font,'HUMIDITY');
  imagettftext($img4, 18,0, 5,45, $black, $font, $humidity.'%');
  // imagettftext($img4, 10,0,35,35, $black, $font, '%');

  #pressure
  $img6 = imagecreatetruecolor(70, 50);
  make_white_background($img6);
  $black = 0-imagecolorallocate($img6, 0, 0, 0);
  imagettftext($img6, 12,0,0,17,$black,$font,'PRESSURE');
  imagettftext($img6, 18,0,0,45, $black, $font, $pressure.'mb');
  // imagettftext($img6, 10,0,35,35, $black, $font, 'kPa');


  //copy those 6 images to main image
  imagecopymerge($imgm, $img1,   0,  4, 0, 0, imagesx($img1), imagesy($img1), 100);
  imagecopymerge($imgm, $img2, 170,  0, 0, 0, imagesx($img2), imagesy($img2), 100);
  imagecopymerge($imgm, $img3,  10, 70, 0, 0, imagesx($img3), imagesy($img3), 100);
  imagecopymerge($imgm, $img4, 110, 70, 0, 0, imagesx($img4), imagesy($img4), 100);
  imagecopymerge($imgm, $img6, 210, 70, 0, 0, imagesx($img6), imagesy($img6), 100);






}


function make_white_background($image) {
  imagefill($image, 0, 0,
  imagecolorallocate($image, 255, 255, 255));
}

function get_icon_image($icon_str) {
  $directory = __DIR__ . '/images/image';

  if (strpos( $icon_str , 'd') !== false) { //if in the icon has day
    if ($icon_str == '01d') return $directory . '02.jpg';
    if ($icon_str == '02d') return $directory . '01.jpg';
    if ($icon_str == '03d') return $directory . '03.jpg';
    if ($icon_str == '04d') return $directory . '04.jpg';
    if ($icon_str == '10d') return $directory . '16.jpg';
    if ($icon_str == '11d') return $directory . '21.jpg';

    return $directory . '02.jpg';
  }

  if (strpos( $icon_str , 'n') !== false) { //if in the icon has night
    if ($icon_str == '01n') return $directory . '32.jpg';
    if ($icon_str == '02n') return $directory . '32.jpg';
    if ($icon_str == '03n') return $directory . '03.jpg';
    if ($icon_str == '04n') return $directory . '04.jpg';
    if ($icon_str == '10n') return $directory . '33.jpg';
    if ($icon_str == '11n') return $directory . '34.jpg';

    return $directory . '32.jpg';
  }

  return $directory . '03.jpg';

}



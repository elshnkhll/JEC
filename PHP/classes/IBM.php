<?php
class IBM{

	public static function say( $text, $apikey, $audio_file ){		
		$url = 'https://api.us-east.text-to-speech.watson.cloud.ibm.com/instances/a025a484-0539-4743-93b5-44f179c7d7cd';
		exec( 'curl -X POST -s -u "apikey:'.$apikey.'" --header "Content-Type: application/json" --header "Accept: audio/mp3" --data "{\"text\":\"'.$text.'\"}" "'.$url.'/v1/synthesize?voice=en-US_MichaelV3Voice" | ffmpeg -f mp3 -i pipe: -f wav -ar 16000 -ac 1 -acodec pcm_u8 -filter:a "volume=4" '.$audio_file);
		return false;
	}
	
	public static function transcribe( $audio_file, $apikey ){		
		$url = 'https://gateway-wdc.watsonplatform.net/speech-to-text/api/v1/models/en-US_NarrowbandModel/recognize';
		exec( "curl -X POST -u 'apikey:$apikey' --header 'Content-Type: audio/wav' --data-binary @$audio_file '$url'", $output , $return );
		if( is_array( $output ) ){

			// echo '<pre>'; print_r(  implode ('',$output)  );

			$res =  json_decode( implode ('',$output));
			$transcript = @$res->results[0]->alternatives[0]->transcript;
			if( strlen($transcript) > 1 ){
				return $transcript;
			}else{
				return false;
			}
		}
		return false;
	}
}



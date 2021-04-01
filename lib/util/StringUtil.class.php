<?php
/**
 * String operations.
 * 
 * @author Matthias Kittsteiner
 * @copyright 2021 Matthias Kittsteiner
 * @license <internal>
 */
class StringUtil {
	const PROTOCOL_PATTERN = '/([\w]+:\/\/)/i';
	
	/**
	 * Checks if URL protocol is available and adds one if it isn’t
	 * 
	 * @param	string		$url
	 * @return	string
	 */
	public static function checkUrl($url) {
		if (!preg_match(self::PROTOCOL_PATTERN, $url)) {
			$url = 'http://' . $url;
		}
		
		return $url;
	}
	
	/**
	 * Get the URL title.
	 * 
	 * @param	string		$url
	 * @return	string
	 */
	public static function getUrlTitle($url) {
		$options = [
			'http' => [
				'method' => 'GET',
				'header' => 'Accept-language: en' . PHP_EOL . 
							'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_6) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0.2 Safari/605.1.15'
			]
		];
		$context = stream_context_create($options);
		$str = file_get_contents($url, false, $context);
		
		if (strlen($str) > 0){
			//$str = trim(preg_replace('/\s+/', ' ', $str)); // supports line breaks inside <title>
			preg_match('/<title([^>]*)>([^>]*)<\/title>/mi', $str, $title); // ignore case
			
			if (!empty($title[2])) {
				return $title[2];
			}
			
			return 'Titel konnte nicht abgerufen werden';
		}
		
		return 'Titel konnte nicht abgerufen werden';
	}
}

<?php
/**
 * String operations.
 * 
 * @author Matthias Kittsteiner
 * @copyright 2020 Matthias Kittsteiner
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
		$str = file_get_contents($url);
		
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

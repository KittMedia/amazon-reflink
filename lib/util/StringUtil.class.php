<?php
/**
 * String operations.
 * 
 * @author Matthias Kittsteiner
 * @copyright 2016 Matthias Kittsteiner
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
		$curl = curl_init();
		
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		preg_match("/\<title.*\>(.*)\<\/title\>/isU", curl_exec($curl), $match);
		curl_close ($curl);
		
		$urlTitle = html_entity_decode($match[1], ENT_QUOTES);
		
		return $urlTitle;
	}
}

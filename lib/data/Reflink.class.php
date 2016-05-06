<?php
require_once(__DIR__ . '/../../config.inc.php');

/**
 * Reflink management related functions.
 * 
 * @author Matthias Kittsteiner
 * @copyright 2016 Matthias Kittsteiner
 * @license <internal>
 */
class Reflink {
	/**
	 * PDO object
	 * 
	 * @var		object
	 */
	private $db;
	
	/**
	 * Constructor
	 */
	public function __construct() {
		// initialize PDO object
		$this->db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';', DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
	}
	
	/**
	 * Add a new refcode.
	 * 
	 * @param	string		$refcode
	 * @param	string		$refcodeName
	 */
	public function addRefcode($refcode, $refcodeName) {
		// insert refcode
		$sql = $this->db->prepare(
			"INSERT INTO	refcode
			(refcodeName, displayName)
			VALUES		(?, ?)"
		);
		
		try {
			$sql->execute(array($refcode, $refcodeName));
		}
		catch (PDOException $e) {
			echo $e->getMessage();
		}
	}
	
	/**
	 * Add a new reflink.
	 * 
	 * @param	string		$link
	 * @param	integer		$refcode
	 * @param	string		$title
	 */
	public function addReflink($link, $refcode, $title) {
		// insert reflink
		$sql = $this->db->prepare(
			"INSERT INTO	reflink
			(reflink, inputTime, refcodeID, reflinkTitle)
			VALUES		(?, ?, ?, ?)"
		);
		
		try {
			$sql->execute(array($link, TIME_NOW, $refcode, $title));
		}
		catch (PDOException $e) {
			echo $e->getMessage();
		}
	}
	
	/**
	 * Disable a refcode.
	 * 
	 * @param	int		$refcode
	 */
	public function disableRefcode($refcode) {
		// disable refcode
		$sql = $this->db->prepare(
			"UPDATE		refcode
			SET		isDisabled = ?
			WHERE		refcodeID = ?"
		);
		
		try {
			$sql->execute(array(1, $refcode));
		}
		catch (PDOException $e) {
			echo $e->getMessage();
		}
	}
	
	/**
	 * Enable a refcode.
	 * 
	 * @param	int		$refcode
	 */
	public function enableRefcode($refcode) {
		// enable refcode
		$sql = $this->db->prepare(
			"UPDATE		refcode
			SET		isDisabled = ?
			WHERE		refcodeID = ?"
		);
		
		try {
			$sql->execute(array(0, $refcode));
		}
		catch (PDOException $e) {
			echo $e->getMessage();
		}
	}
	
	/**
	 * Get all enabled refcodes.
	 * 
	 * @return	array
	 */
	public function getEnabledRefcode() {
		$sql = $this->db->prepare(
			"SELECT		*
			FROM		refcode
			WHERE		isDisabled = ?"
		);
		
		try {
			$sql->execute(array(0));
		}
		catch (PDOException $e) {
			echo $e->getMessage();
		}
		
		$refcodeList = $sql->fetchAll(PDO::FETCH_ASSOC);
		
		return $refcodeList;
	}
	
	/**
	 * Generate a random refcode.
	 * 
	 * @return	string
	 */
	public function getRandomRefcode() {
		$enabledRefcodes = self::getEnabledRefcode();
		$i = rand(0, (count($enabledRefcodes) - 1));
		
		return $enabledRefcodes[$i];
	}
	
	/**
	 * Get a refcode by its display name.
	 * 
	 * @param	string		$refcodeName
	 * @return	array
	 */
	public function getRefcodeByName($refcodeName) {
		$sql = $this->db->prepare(
			"SELECT		*
			FROM		refcode
			WHERE		refcodeName = ?"
		);
		
		try {
			$sql->execute(array($refcodeName));
		}
		catch (PDOException $e) {
			echo $e->getMessage();
		}
		
		$refcode = $sql->fetch(PDO::FETCH_ASSOC);
		
		return $refcode;
	}
	
	public function getReflinks() {
		$sql = $this->db->prepare(
			"SELECT		*
			FROM		reflink
			LEFT JOIN	refcode
			ON		reflink.refcodeID = refcode.refcodeID
			ORDER BY	inputTime DESC"
		);
		
		try {
			$sql->execute(array(0));
		}
		catch (PDOException $e) {
			echo $e->getMessage();
		}
		
		$reflinkList = $sql->fetchAll(PDO::FETCH_ASSOC);
		
		return $reflinkList;
	}
	
	/**
	 * Check link if it contains the amazon domain name.
	 * 
	 * @param	string		$link
	 * @return	boolean
	 */
	public static function isAmazonLink($link) {
		if (preg_match('/(https?:\/\/)?(www\.)?amazon\.[^\/]{2,}(.*)/', $link)) {
			return true;
		}
		else {
			return false;
		}
	}
}
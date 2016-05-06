<?php
require_once(__DIR__ . '/../data/Reflink.class.php');
require_once(__DIR__ . '/../util/StringUtil.class.php');

$reflink = new Reflink();

// handle received data
$filterString = array('filter' => FILTER_SANITIZE_STRING, FILTER_SANITIZE_SPECIAL_CHARS, FILTER_SANITIZE_ENCODED);

$args = array(
	'links' => $filterString,
	'refcode' => $filterString
);

$data = filter_input_array(INPUT_POST, $args);
$finalLinks = array();
$links = explode(PHP_EOL, trim(str_replace("\r", '', $data['links'])));

if (empty($links[0]) || !$reflink->isAmazonLink($links[0])) {
	echo '{"link":""}';
	return;
}

foreach ($links as $link) {
	if (!$reflink->isAmazonLink($link)) continue;
	
	$link = StringUtil::checkUrl($link);
	
	// set correct parameter sign
	if (preg_match('/\?/', $link)) {
		$separator = '&';
	}
	else {
		$separator = '?';
	}
	
	if ($data['refcode'] === 'random') {
		$refcode = $reflink->getRandomRefcode();
	}
	else {
		$refcode = $reflink->getRefcodeByName($data['refcode']);
	}
	
	$title = StringUtil::getUrlTitle($link);
	$reflink->addReflink($link, $refcode['refcodeID'], $title);
	
	$finalLink = array(
		'link' => $link . $separator . 'tag=' . $refcode['refcodeName'],
		'refcode' => $refcode['displayName'],
		'title' => $title
	);
	
	array_push($finalLinks, $finalLink);
}

// generate JSON data
echo json_encode($finalLinks);
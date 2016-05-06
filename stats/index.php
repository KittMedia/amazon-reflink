<?php
require_once(__DIR__ . '/../config.inc.php');
require_once(__DIR__ . '/../lib/data/Reflink.class.php');

$reflink = new Reflink();
$reflinkList = $reflink->getReflinks();
$enabledRefcodeList = $reflink->getEnabledRefcode();
?>
<!DOCTYPE html>
<html lang="de">
<head>
<title>Amazon Reflink-Statistiken</title>

<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
<meta name="format-detection" content="telephone=no" />

<link rel="icon" href="../images/favicon.ico" type="image/x-icon" />
<link rel="apple-touch-icon" href="../images/apple-touch-icon.png" />
<link rel="apple-touch-startup-image" href="../images/apple-touch-icon.png" />
<link rel="mask-icon" href="../images/pinned_logo.svg" color="#186e9f" />

<link href="../css/style.css" rel="stylesheet" type="text/css" />
</head>
<body>

<div id="page">
	<div id="content">
		<h1>Amazon Reflink-Statistiken</h1>
		
		<dl>
			<dt>Links gesamt:</dt>
			<dd><?php echo count($reflinkList); ?></dd>
			<?php foreach ($enabledRefcodeList as $refcode): ?>
			<dt>Anzahl von <?php echo $refcode['displayName'] ?>:</dt>
			<dd><?php $count = 0; foreach ($reflinkList as $reflink): if ($reflink['refcodeID'] == $refcode['refcodeID']) $count++; endforeach; echo $count; ?></dd>
			<?php endforeach; ?>
		</dl>
		<?php
		$dateReflinkList = array();
		
		foreach ($reflinkList as $reflink) {
			$date = date('Y-m', $reflink['inputTime']);
			$dateReflinkList[$date][] = $reflink;
		}
		
		foreach ($dateReflinkList as $key => $date): ?>
		<div class="date">
			<header><h2><?php echo $key; ?> <span class="badge"><?php echo count($date); ?></span></h2></header>
			
			<ul style="display: none;">
				<?php foreach ($date as $link): ?>
				<li>
					<a href="<?php echo $link['reflink']; ?>"><?php echo $link['reflinkTitle']; ?></a><br />
					<span class="light">Referrer: <?php echo $link['displayName']; ?> &ndash; <?php echo date('d.m.Y, H:i', $link['inputTime']); ?> Uhr</span>
				</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php endforeach; ?>
	</div>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="../js/functions<?php if (!DEBUG_MODE): echo '.min'; endif; ?>.js"></script>

</body>
</html>
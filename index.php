<?php
require_once(__DIR__ . '/config.inc.php');
require_once(__DIR__ . '/lib/data/Reflink.class.php');

$reflink = new Reflink();
$enabledRefcodes = $reflink->getEnabledRefcode();
?>
<!DOCTYPE html>
<html lang="de">
<head>
<title>Amazon Reflink-Generator</title>

<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" />
<meta name="format-detection" content="telephone=no" />

<link rel="icon" href="images/favicon.ico" type="image/x-icon" />
<link rel="apple-touch-icon" href="/images/apple-touch-icon.png" />
<link rel="apple-touch-startup-image" href="/images/apple-touch-icon.png" />
<link rel="mask-icon" href="images/pinned_logo.svg" color="#186e9f" />

<link href="css/style.css" rel="stylesheet" type="text/css" />
</head>
<body>

<div id="page">
	<div id="content">
		<h1>Amazon Reflink-Generator</h1>
		
		<form id="form" method="post" action="lib/form/AddReflink.php">
			<textarea name="links" placeholder="Link(s) hier eingeben …" oninput="autoGrow(this);" required="required"></textarea>
			<small>Eine URL pro Zeile.</small>
			
			<div class="refcodeContainer">
				<h3>Referrer:</h3>
				<label><input type="radio" name="refcode" value="random" checked="checked" /> Zufällig</label>
				<?php foreach ($enabledRefcodes as $refcode): ?>
				<label><input type="radio" name="refcode" value="<?php echo $refcode['refcodeName'] ?>" /> <?php echo $refcode['displayName'] ?></label>
				<?php endforeach; ?>
			</div>
			
			<input type="submit" accesskey="s" />
			<input type="reset" accesskey="r" />
		</form>
		
		<div id="javaScriptContent" style="display: none;"></div>
	</div>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="js/functions<?php if (!DEBUG_MODE): echo '.min'; endif; ?>.js"></script>

</body>
</html>
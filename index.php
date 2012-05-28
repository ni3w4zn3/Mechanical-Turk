<?php

define('MAX_SELECTIONS', 3);
define('MAX_IMAGES', 2);

define('IMAGE_LIST', 'img/list.txt');
define('IMAGE_DIR', 'img/');
define('OUT_DIR', 'out/');

define('BASE_DIR', getcwd().'/');


function makeHash($string) {
	return md5('super' . md5($string.'hash'));
}

if ($_REQUEST['data']) {
	$_REQUEST['data'] = base64_decode($_REQUEST['data']);
}

?>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="css/style.css" />
		<script src="http://code.jquery.com/jquery-1.7.2.js"></script>
	<head>
	
	<body>
		<div class="content">
<?php

if (!$_REQUEST['step'] || $_REQUEST['hash'] != makeHash($_REQUEST['data'])) {
	include(BASE_DIR.'/src/welcome.php');
} else if ($_REQUEST['step'] != 'final'){
	include('src/experiment.php');
} else {
	include('src/thanks.php');
}


?>
		</div>
	</body>

</html>
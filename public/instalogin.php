<?php
$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]".dirname($_SERVER['PHP_SELF']);
if (isset($_GET['code'])) {
	try {
		header('Location: '.$actual_link.'/index.php?url=instagram-login/index&code='.$_GET['code']);
	} catch (\Error $e) {
		echo $e->getMessage();
	}
}
?>
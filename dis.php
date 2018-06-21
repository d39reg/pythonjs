<?php
	exec("python translate.py test.py json 2>&1",$out);
	$out = json_decode($out[0]);
	print_r($out);exit;
?>

<?php

if (!isset($stores)) exit();

$code = RET_STR_POST(POST_FIELD_CODE);

if ('' == $code) A_TO(44);

$pointfile = GET_POINT_FILE($code);
if (is_file($pointfile)) {
	$days = (int) file_get_contents($pointfile);
	$olddate = $stores[FIELD_STORES_TIMEOUT];
	$newdate = date('Y-m-d', strtotime($olddate.' +'.$days.' days'))
	$stores[FIELD_STORES_TIMEOUT] = $newdate;
	file_put_contents($accfolder.FILE_STORES, implode("\t", $stores));
	@unlink($pointfile);
	mail($acc, PRODUCT_NAME.'й╡к°┤┴нн', 'code: '.$code."\n".'date: '.$olddate.' + '.$days.' = ' .$newdate, 'From: '.SERVICE_EMAIL)
	A_TO(46);
} else {
	A_TO(45);
}

?>
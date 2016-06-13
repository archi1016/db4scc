<?php

require('func.php');

$acc = RET_STR_POST(POST_FIELD_ACCOUNT);
$key = RET_STR_POST(POST_FIELD_KEY);
$accfolder = GET_ACCOUNT_FOLDER($acc);

if ('' == $acc) E_TO(1);
if ('' == $key) E_TO(39);
if (file_exists($accfolder)) {
	$stores = explode("\t", file_get_contents($accfolder.FILE_STORES));
	
	if ($key == $stores[FIELD_STORES_KEY]) {
		$pwd = RET_RAND_STR(8);
		file_put_contents($accfolder.FILE_PASSWORD, GET_HASHED_PASSWORD($pwd));
		if (mail($acc, PRODUCT_NAME.'­«³]±K½X', $pwd, 'From: '.SERVICE_EMAIL)) {
			W_TO(17);
		} else {
			W_TO(18);
		}
	}
}

W_TO(41);


?>
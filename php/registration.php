<?php

require('func.php');

$acc = RET_STR_POST(POST_FIELD_ACCOUNT);
$acc2 = RET_STR_POST(POST_FIELD_ACCOUNT_2);
$pwd = RET_STR_POST(POST_FIELD_PASSWORD);
$pwd2 = RET_STR_POST(POST_FIELD_PASSWORD_2);
$key = RET_STR_POST(POST_FIELD_KEY);
$accfolder = GET_ACCOUNT_FOLDER($acc);
$keyfile = GET_KEY_FILE($key);


if ('' == $acc) E_TO(1);
if ('' == $pwd) E_TO(2);
if ($acc != $acc2) E_TO(3);
if ($pwd != $pwd2) E_TO(4);
if ('' == $key) E_TO(39);
if (is_dir($accfolder)) E_TO(5);

if (is_file($keyfile)) {
	if (mkdir($accfolder)) {
		$code = RET_RAND_STR(6);
		$days = (int) file_get_contents($keyfile);
		if (0 >= $days) {
			$days = FREE_DEMO_DAYS;
		}
		
		$stores[FIELD_STORES_NAME]		= '未命名';
		$stores[FIELD_STORES_TELEPHONE]		= '00-000-0000';
		$stores[FIELD_STORES_ADDRESS]		= 'Taiwan';
		$stores[FIELD_STORES_IP]		= '0.0.0.0';
		$stores[FIELD_STORES_MAX_MDBS]		= MDB_MAX_COUNT;
		$stores[FIELD_STORES_KEY]		= $key;
		$stores[FIELD_STORES_MDB_PASSWORD]	= DATABASE_PASSWORD;
		$stores[FIELD_STORES_TIMEOUT]		= date('Y-m-d', strtotime('now +'.$days.' days'));
		$stores[FIELD_STORES_USB_KEYPRO]	= '';
		$stores[FIELD_STORES_NU_3]		= '';
		$stores[FIELD_STORES_NU_2]		= '';
		$stores[FIELD_STORES_NU_1]		= '';
		
		file_put_contents($accfolder.FILE_PASSWORD, GET_HASHED_PASSWORD($pwd));
		file_put_contents($accfolder.FILE_NEED_VERIFICATION, $code);
		file_put_contents($accfolder.FILE_STORES, implode("\t", $stores));
		@unlink($keyfile);
		
		if (mail($acc, PRODUCT_NAME.'驗證序號', $code, 'From: '.SERVICE_EMAIL)) {
			W_TO(10);
		} else {
			W_TO(13);
		}
	} else {
		E_TO(6);
	}
} else {
	E_TO(40);
}

?>
<?php

require('func.php');

$acc = RET_STR_POST(POST_FIELD_ACCOUNT);
$accfolder = GET_ACCOUNT_FOLDER($acc);

if ('' == $acc) E_TO(1);
if (file_exists($accfolder)) {
	if (is_file($accfolder.FILE_NEED_VERIFICATION)) {
		$code = file_get_contents($accfolder.FILE_NEED_VERIFICATION);
		if (mail($acc, PRODUCT_NAME.'ล็รางวธน', $code, 'From: '.SERVICE_EMAIL)) {
			W_TO(15);
		} else {
			W_TO(16);
		}
	} else {
		W_TO(12);
	}
}

W_TO(16);


?>
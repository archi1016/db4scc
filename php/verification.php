<?php

require('func.php');

$acc = RET_STR_POST(POST_FIELD_ACCOUNT);
$code = RET_STR_POST(POST_FIELD_CODE);
$accfolder = GET_ACCOUNT_FOLDER($acc);


if ('' == $acc) E_TO(1);
if ('' == $code) E_TO(8);
if (file_exists($accfolder)) {
	if (is_file($accfolder.FILE_NEED_VERIFICATION)) {
		if ($code == file_get_contents($accfolder.FILE_NEED_VERIFICATION)) {
			@unlink($accfolder.FILE_NEED_VERIFICATION);
			W_TO(12);
		} else {
			E_TO(9);
		}
	}
}

E_TO(11);


?>
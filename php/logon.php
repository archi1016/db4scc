<?php

require('func.php');

$acc = RET_STR_POST(POST_FIELD_ACCOUNT);
$pwd = RET_STR_POST(POST_FIELD_PASSWORD);
$accfolder = GET_ACCOUNT_FOLDER($acc);


if ('' == $acc) E_TO(1);
if ('' == $pwd) E_TO(2);
if (is_dir($accfolder)) {
	if (GET_HASHED_PASSWORD($pwd) == file_get_contents($accfolder.FILE_PASSWORD)) {
		if (is_file($accfolder.FILE_NEED_VERIFICATION)) {
			W_TO(7);
		} else {
			session_start();
			$_SESSION[ARGUMENT_SESSION_ACCOUNT] = $acc;
			$_SESSION[ARGUMENT_SESSION_TIMEOUT] = strtotime('now');
			P_TO('db.php?'.ARGUMENT_SESSION_TOKEN.'='.session_id());
		}
	}
}

W_TO(11);


?>
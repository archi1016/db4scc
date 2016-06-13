<?php

if (!isset($stores)) exit();

$confirm = RET_STR_POST(POST_FIELD_CONFIRM);

if ('Y' == $confirm) {
	@unlink(GET_IP_FILE($stores[FIELD_STORES_IP]));
		
	$dh = opendir($accfolder);
	if ($dh) {
		$fn = readdir($dh);
		while (false !== $fn) {
			if ('.' != $fn) {
				if ('..' != $fn) {
					@unlink($accfolder.'/'.$fn);
				}
			}
			$fn = readdir($dh);
		}
		closedir($dh);
	}
		
	@rmdir($accfolder);
	
	session_unset();
	session_destroy();
	
	W_TO(33);
}

P_TO($THIS_WEB_URL);

?>
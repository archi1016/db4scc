<?php

if (!isset($stores)) exit();

$fn = RET_STR_GET(ARGUMENT_DB_FILE_NAME);

if ('' == $fn) A_TO(26);

$db_file = $accfolder.'/'.$fn;
if (is_file($db_file)) {
	$fs = filesize($db_file);
	
	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename="'.$fn.'"');
	header('Content-Length: '.$fs);
	header('Content-Transfer-Encoding: binary');
	header('Expires: 0');
	header('Cache-Control: must-revalidate, no-cache');
	header('Pragma: no-cache');
	readfile($db_file);
} else {
	A_TO(27);
}

?>
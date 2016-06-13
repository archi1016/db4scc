<?php

if (!isset($stores)) exit();

$fn = RET_STR_GET(ARGUMENT_DB_FILE_NAME);

if ('' == $fn) A_TO(26);

$db_file = $accfolder.'/'.$fn;
if (is_file($db_file)) {
	@unlink($db_file);
	A_TO(32);
} else {
	A_TO(27);
}

?>
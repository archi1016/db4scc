<?php

if (!isset($stores)) exit();

$ip = RET_STR_POST(POST_FIELD_IP);

if ('' == $ip) A_TO(19);

$newipfile = GET_IP_FILE($ip);
$oldipfile = GET_IP_FILE($stores[FIELD_STORES_IP]);
if (is_file($newipfile)) {
	A_TO(20);
} else {
	@unlink($oldipfile);
	file_put_contents($newipfile, $acc);
	$stores[FIELD_STORES_IP] = $ip;
	file_put_contents($accfolder.FILE_STORES, implode("\t", $stores));
	A_TO(21);
}

?>
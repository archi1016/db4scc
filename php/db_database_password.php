<?php

if (!isset($stores)) exit();

$pwd = RET_STR_POST(POST_FIELD_PASSWORD);

if ('' == $pwd) {
	$pwd = DATABASE_PASSWORD;
}

$stores[FIELD_STORES_MDB_PASSWORD] = $pwd;
file_put_contents($accfolder.FILE_STORES, implode("\t", $stores));

A_TO(43);

?>
<?php

if (!isset($stores)) exit();

$name = RET_STR_POST(POST_FIELD_NAME);
$telephone = RET_STR_POST(POST_FIELD_TELEPHONE);
$address = RET_STR_POST(POST_FIELD_ADDRESS);
$key = RET_STR_POST(POST_FIELD_KEY);

if ('' == $name) A_TO(22);
if ('' == $telephone) A_TO(23);
if ('' == $address) A_TO(24);

$stores[FIELD_STORES_NAME] = $name;
$stores[FIELD_STORES_TELEPHONE] = $telephone;
$stores[FIELD_STORES_ADDRESS] = $address;
$stores[FIELD_STORES_USB_KEYPRO] = $key;
file_put_contents($accfolder.FILE_STORES, implode("\t", $stores));

A_TO(25);

?>
<?php

if (!isset($stores)) exit();

$pwdold = RET_STR_POST(POST_FIELD_PASSWORD_OLD);
$pwd = RET_STR_POST(POST_FIELD_PASSWORD);
$pwd2 = RET_STR_POST(POST_FIELD_PASSWORD_2);

if ('' == $pwdold) A_TO(34);
if ('' == $pwd) A_TO(35);
if ($pwd != $pwd2) A_TO(36);

if (GET_HASHED_PASSWORD($pwdold) == file_get_contents($accfolder.FILE_PASSWORD)) {
	file_put_contents($accfolder.FILE_PASSWORD, GET_HASHED_PASSWORD($pwd));
	A_TO(38);
} else {
	A_TO(37);
}

?>
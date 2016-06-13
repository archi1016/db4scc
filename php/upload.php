<?php

require('func.php');

$db_time = RET_STR_POST(POST_FIELD_TIME);
$db_size = (int) RET_STR_POST(POST_FIELD_SIZE);

$ipfile = GET_IP_FILE($_SERVER['REMOTE_ADDR']);

$ret_code = RETURN_CODE_SUCCESS;

if ((isset($_SERVER['HTTP_USER_AGENT'])) && (UPLOAD_USER_AGENT == $_SERVER['HTTP_USER_AGENT'])) {
	if (is_file($ipfile)) {
		$accfolder = GET_ACCOUNT_FOLDER(file_get_contents($ipfile));
		if (is_dir($accfolder)) {
			if (!is_file($accfolder.FILE_NEED_VERIFICATION)) {
				$stores = explode("\t", file_get_contents($accfolder.FILE_STORES));
		
				if (strtotime(date('Y-m-d')) <= strtotime($stores[FIELD_STORES_TIMEOUT])) {
					if (isset($_FILES[POST_FIELD_MDB])) {
						$ufi = &$_FILES[POST_FIELD_MDB];
						if (UPLOAD_ERR_OK == $ufi['error']) {
							$usize = (int) $ufi['size'];
							if ($db_size == $usize) {
								$l = 0 - strlen(FILE_EXT_MDB);
								if (FILE_EXT_MDB == substr($ufi['name'], $l)) {
									if (14 != strlen($db_time)) {
										$db_time = date('YmdHis');
									}
									$file7z = $accfolder.'/temp.7z';
									if (move_uploaded_file($ufi['tmp_name'], $file7z)) {
										$cmd = dirname($_SERVER['SCRIPT_FILENAME']).'/tool/7z.exe e -y -o"'.$accfolder.'" -p'.PASSWORD_FOR_7Z.' "'.$file7z.'"';
										exec($cmd);
										@unlink($file7z);
										MDB_KEEP_FILES($accfolder, FILE_EXT_MDB, (int) $stores[FIELD_STORES_MAX_MDBS]);
									} else {
										$ret_code = RETURN_CODE_MOVE_FAILURE;
									}
								} else {
									$ret_code = RETURN_CODE_NOT_MDB;
								}
							} else {
								$ret_code = RETURN_CODE_UPLOAD_FAILURE;
							}
						} else {
							$ret_code = RETURN_CODE_UPLOAD_FAILURE;
						}
					} else {
						$ret_code = RETURN_CODE_UPLOAD_FAILURE;
					}
				} else {
					$ret_code = RETURN_CODE_TIMEOUT;
				}
			} else {
				$ret_code = RETURN_CODE_NEED_VERIFICATION;
			}
		} else {
			$ret_code = RETURN_CODE_UNKNOW_ACCOUNT;
		}
	} else {
		$ret_code = RETURN_CODE_UNKNOW_IP;
	}
}

header('Content-Length: '.strlen($ret_code));
echo $ret_code;
exit();

?>
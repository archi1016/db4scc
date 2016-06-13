<?php

require('config.php');
require('define.php');

date_default_timezone_set(TIMEZONE_SET);


$HTML = '';
$THIS_PHP_FILE = basename($_SERVER['SCRIPT_NAME']);
$THIS_WEB_URL = $THIS_PHP_FILE;
$SESSION_TOKEN = '';

function E_TO($c) {
	header('Location: error.php?'.ARGUMENT_ERROR_CODE.'='.$c);
	exit();
}

function W_TO($c) {
	P_TO('index.php?'.ARGUMENT_WARNING.'='.$c);
}

function A_TO($c) {
	global $THIS_WEB_URL;

	P_TO($THIS_WEB_URL.'&amp;'.ARGUMENT_WARNING.'='.$c);
}

function P_TO($u) {
	$h = '<html><head><meta http-equiv="refresh" content="0; url='.$u.'"></head></html>';
	header('Content-Length: '.strlen($h));
	echo $h;
	exit();
}

function P_ADD($l) {
	global $HTML;

	$HTML .= $l."\n";
}

function P_COMMON_HEADER($t) {
	P_ADD('<!DOCTYPE html>');
	P_ADD('<html lang="'.LOCAL_LANGUAGE.'"><head>');
	P_ADD('<meta charset="'.TEXT_CHARSET.'">');
	P_ADD('<meta http-equiv="Cache-Control" content="no-cache">');
	P_ADD('<meta http-equiv="Pragma" content="no-cache">');
	P_ADD('<meta http-equiv="Expires" content="0">');
	P_ADD('<meta name="apple-mobile-web-app-capable" content="yes">');
	P_ADD('<meta name="viewport" content="width=960">');
	P_ADD('<link rel="stylesheet" type="text/css" href="theme.css">');
	P_ADD('<link rel="icon" type="image/png" href="logo/logo_16x16.png">');
	P_ADD('<link rel="apple-touch-icon-precomposed" href="logo/logo_128x128.png">');
	P_ADD('<title>'.$t.'</title>');	
	P_ADD('</head><body>');
}

function P_COMMON_PRINT() {
	global $HTML;

	P_ADD('</body></html>');	
	header('Content-Length: '.strlen($HTML));
	echo $HTML;
	exit();
}

function P_HEADER($t, $s) {
	if ('' == $t) {
		$t = PRODUCT_NAME;
	}
	$war = RET_INT_GET(ARGUMENT_WARNING);

	P_COMMON_HEADER($t);
	P_ADD('<div class="header"><h1>'.$t.'</h1><div class="sub">'.$s.'</div></div>');
	if ($war > 0) {
		require('err_msg.php');
		P_ADD('<div class="warning">'.$ERR_MSG[$war].'</div>');
	}
	P_ADD('<div class="content">');
}

function P_PRINT() {
	P_ADD('</div>');
	P_ADD('<div class="footer">&copy;'.COMPANY_NAME.'<br>'.PRODUCT_NAME.' v'.PRODUCT_VERSION.'<br>by '.PROGRAM_MANAGER.'</div>');
	P_COMMON_PRINT();
}

function P_HEADER_QUERY($t) {
	global $stores;
	
	$t = $stores[FIELD_STORES_NAME].': '.$t;
	P_COMMON_HEADER($t);
	P_ADD('<h3>'.$t.'</h3>');
	P_ADD('<div class="page">');
}

function P_PRINT_QUERY() {
	global $stores;
	
	P_ADD('</div>');
	P_ADD('<div class="pagefooter">'.$stores[FIELD_STORES_NAME].'<br>'.$stores[FIELD_STORES_TELEPHONE].'<br>'.$stores[FIELD_STORES_ADDRESS].'</div>');
	P_COMMON_PRINT();
}

function CHECK_HAS_LOGON() {
	global $THIS_WEB_URL;
	global $SESSION_TOKEN;
	
	$session_token = RET_STR_GET(ARGUMENT_SESSION_TOKEN);
	if ('' !== $session_token) {
		session_id($session_token);
		session_start();
		if (isset($_SESSION[ARGUMENT_SESSION_TIMEOUT])) {
			$t = strtotime('now');
			if (($t - $_SESSION[ARGUMENT_SESSION_TIMEOUT]) < SESSION_TIMEOUT) {
				$_SESSION[ARGUMENT_SESSION_TIMEOUT] = $t;
				$SESSION_TOKEN = session_id();
				$THIS_WEB_URL .= '?'.ARGUMENT_SESSION_TOKEN.'='.$SESSION_TOKEN;
				return;
			} else {
				session_unset();
				session_destroy();
			}
		} else {
			session_unset();
			session_destroy();
		}
	}
	P_TO('index.php');
}

function RET_STR_POST($key) {
	if (isset($_POST[$key])) {
		if (get_magic_quotes_gpc()) {
			$s = stripcslashes($_POST[$key]);
		} else {
			$s = $_POST[$key];
		}
		return GET_SAFE_FILE_NAME($s);
	} else {
		return '';
	}
}

function RET_INT_POST($key) {
	if (isset($_POST[$key])) {
		return (int) $_POST[$key];
	} else {
		return 0;
	}
}

function RET_STR_GET($key) {
	if (isset($_GET[$key])) {
		if (get_magic_quotes_gpc()) {
			$s = stripcslashes($_GET[$key]);
		} else {
			$s = $_GET[$key];
		}
		return GET_SAFE_FILE_NAME($s);
	} else {
		return '';
	}
}

function RET_INT_GET($key) {
	if (isset($_GET[$key])) {
		return (int) $_GET[$key];
	} else {
		return 0;
	}
}

function GET_ACCOUNT_FOLDER($acc) {
	return DATABASE_FOLDER.FOLDER_DB_ACCOUNT.'/'.$acc;
}

function GET_IP_FILE($ip) {
	return DATABASE_FOLDER.FOLDER_DB_IP.'/'.$ip.FILE_EXT_TXT;
}

function GET_KEY_FILE($key) {
	return DATABASE_FOLDER.FOLDER_DB_KEY.'/'.$key.FILE_EXT_TXT;
}

function GET_POINT_FILE($code) {
	return DATABASE_FOLDER.FOLDER_DB_POINT.'/'.$code.FILE_EXT_TXT;
}

function GET_HASHED_PASSWORD($pwd) {
	return md5('D2SH_'.$pwd);
}

function RET_RAND_STR($c) {
	$s = '';
	$i = 0;
	while ($i < $c) {
		$s .= rand(1,9);
		++$i;
	}
	return $s;
}

function SEARCH_FILES_FORM_FOLDER($fp, $sk, &$fs) {
	$fs = null;
	$l = 0 - strlen($sk);
	$dh = opendir($fp);
	if ($dh) {
		$fn = readdir($dh);
		while (false !== $fn) {
			if ($sk == substr($fn, $l)) {
				$fs[] = $fn;
			}
			$fn = readdir($dh);
		}
		closedir($dh);
	}
	return is_array($fs);
}

function STR_TO_INPUT_VALUE($s) {
	$s = str_replace('"', '&quot;', $s);
	return $s;
}

function STR_TO_HTML($s) {
	$s = str_replace('&', '&amp;', $s);
	$s = str_replace('<', '&lt;', $s);
	$s = str_replace('>', '&gt;', $s);
	$s = str_replace("\r", '', $s);
	$s = str_replace("\n", '<br>', $s);
	return $s;
}

function GET_SAFE_FILE_NAME($fn) {
	$fn = str_replace('../', '', $fn);
	$fn = str_replace('..\\', '', $fn);
	return $fn;
}

function MDB_FILE_NAME_TO_DATE($fp) {
	return substr($fp, 0, 4).'-'.substr($fp, 4, 2).'-'.substr($fp, 6, 2);
}

function MDB_FILE_NAME_TO_TIME($fp) {
	return substr($fp, 8, 2).':'.substr($fp, 10, 2).':'.substr($fp, 12, 2);
}

function MDB_GET_FILE_SIZE($fp) {
	$s = filesize($fp);
	if ($s < 1024) {
		return $s. '<small>B</small>';
	} else {
		if ($s < 1048576) {
			return round($s/1024, 1).' <small>KiB</small>';
		} else {
			return round($s/1048576, 1).' <small>MiB</small>';
		}
	}
}

function MDB_KEEP_FILES($fp, $sk, $max) {
	if (SEARCH_FILES_FORM_FOLDER($fp, $sk, $fs)) {
		$c = count($fs) - $max;
		if ($c > 0) {
			$i = 0;
			while ($i < $c) {
				@unlink($fp.'/'.$fs[$i]);
				++$i;
			}
		}
	}
}

function RET_HHMM_FROM_MINS($m) {
	if (-1 == $m) {
		return '&nbsp;';
	}
	$h = 0;
	while ($m >= 60) {
		++$h;
		$m -= 60;
	}
	return CONV_INT_TO_STR_WITH_ZERO($h).':'.CONV_INT_TO_STR_WITH_ZERO($m);
}

function CONV_INT_TO_STR_WITH_ZERO($v) {
	$v = (string) $v;
	if (2 > strlen($v)) {
		return '0'.$v;
	}
	return $v;
}

function RET_SELECT_YEAR($n, $v) {
	$r = '<select name="'.$n.'" size="1">';
	$y = (int) date('Y');
	if (0 == $v) {
		$v = $y;
	}
	$i = 2005;
	while ($i <= $y) {
		$r .= '<option value="'.$i.'"';
		if ($v == $i) {
			$r .= ' selected="selected"';
		}
		$r .= '>'.$i.'</option>';
		++$i;
	}
	$r .= '</select>';
	return $r;
}

function RET_SELECT_MONTH($n, $v) {
	$r = '<select name="'.$n.'" size="1">';
	$m = (int) date('m');
	if (0 == $v) {
		$v = $m;
	}
	$i = 1;
	while ($i <= 12) {
		$r .= '<option value="'.$i.'"';
		if ($v == $i) {
			$r .= ' selected="selected"';
		}
		$r .= '>'.CONV_INT_TO_STR_WITH_ZERO($i).'</option>';
		++$i;
	}
	$r .= '</select>';
	return $r;
}

function RET_SELECT_DAY($n, $v) {
	$r = '<select name="'.$n.'" size="1">';
	$d = (int) date('d');
	if (0 == $v) {
		$v = $d;
	}
	$i = 1;
	while ($i <= 31) {
		$r .= '<option value="'.$i.'"';
		if ($v == $i) {
			$r .= ' selected="selected"';
		}
		$r .= '>'.CONV_INT_TO_STR_WITH_ZERO($i).'</option>';
		++$i;
	}
	$r .= '</select>';
	return $r;
}


?>
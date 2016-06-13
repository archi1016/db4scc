<?php

require('func.php');

function FIND_FLAGS(&$ti) {
	$f = '&nbsp;';
	switch ($ti[DAY_TRADE_FLAGS]) {
		case 3:
			$f = '一般';
			break;
			
		case 5:
			$f = '時數';
			break;
	}
	$ti[DAY_TRADE_FLAGS] = $f;
}

function RET_LOGIN_MODE($lm, $mid) {
	switch ($lm) {
		case 0:
			return  '一般';
		case 1:
			if ('&nbsp;' == $mid) {
				return	'包台';
			} else {
				return  '時數';
			}
	}
	return '未知';
}

function RET_CLASS_START_TIME($con) {
	$r = '2000-01-01 00:00:00';
	$rs = @odbc_exec($con, 'select top 1 * from ShiftTrade order by '.SHIFT_TRADE_UID.' desc');
	if ($rs) {
		if (odbc_fetch_row($rs)) {
			$r = odbc_result($rs, SHIFT_TRADE_END_TIME);
		}
		odbc_free_result($rs);
	}
	return $r;
}

function RET_CHECKBOX($v) {
	if (0 == $v) {
		return '&nbsp;';
	} else {
		return '是';
	}
}

CHECK_HAS_LOGON();

$acc = $_SESSION[ARGUMENT_SESSION_ACCOUNT];
$accfolder = GET_ACCOUNT_FOLDER($acc);
$stores = explode("\t", file_get_contents($accfolder.FILE_STORES));

$dac = RET_STR_GET(ARGUMENT_DB_ACTION);
switch ($dac) {
	case DB_ACTION_QUERY_SHIFT:
		$THIS_WEB_URL .= '&amp;'.ARGUMENT_DB_ACTION.'='.$dac;
		require('db_query_shift.php');
		break;
	
	case DB_ACTION_QUERY_EVENT:	
		$THIS_WEB_URL .= '&amp;'.ARGUMENT_DB_ACTION.'='.$dac;
		require('db_query_event.php');
		break;
	
	case DB_ACTION_QUERY_LOGIN:
		$THIS_WEB_URL .= '&amp;'.ARGUMENT_DB_ACTION.'='.$dac;
		require('db_query_login.php');
		break;
	
	case DB_ACTION_QUERY_CARD:
		$THIS_WEB_URL .= '&amp;'.ARGUMENT_DB_ACTION.'='.$dac;
		require('db_query_card.php');
		break;
	
	case DB_ACTION_QUERY_GOODS:
		$THIS_WEB_URL .= '&amp;'.ARGUMENT_DB_ACTION.'='.$dac;
		require('db_query_goods.php');
		break;
		
	case DB_ACTION_QUERY_QTY:
		require('db_query_qty.php');
		break;
	
	case DB_ACTION_QUERY_STOCK:
		$THIS_WEB_URL .= '&amp;'.ARGUMENT_DB_ACTION.'='.$dac;
		require('db_query_stock.php');
		break;
						
	case DB_ACTION_QUERY_TIMING:
		require('db_query_timing.php');
		break;
	
	case DB_ACTION_QUERY_MAC:
		require('db_query_mac.php');
		break;
		
	case DB_ACTION_DOWNLOAD_DB:
		require('db_download_db.php');
		break;
		
	case DB_ACTION_UPDATE_STORES:
		require('db_update_stores.php');
		break;
		
	case DB_ACTION_UPDATE_IP:
		require('db_update_ip.php');
		break;
	
	case DB_ACTION_UPDATE_PASSWORD:
		require('db_update_password.php');
		break;

	case DB_ACTION_DATABASE_PASSWORD:
		require('db_database_password.php');
		break;
		
	case DB_ACTION_DELETE_DB:
		require('db_delete_db.php');
		break;

	case DB_ACTION_EXTEND_TIMEOUT:
		require('db_extend_timeout.php');
		break;
		
	case DB_ACTION_KILL_SELF:
		require('db_kill_self.php');
		break;
					
	default:
		require('db_list.php');
		break;
}

?>
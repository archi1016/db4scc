<?php

if (!isset($stores)) exit();

function RET_SHIFT_TRADE_HAS_EVENT($v) {
	if ('' == $v) {
		return '';
	} else {
		return ' <small>事件</small>';
	}
}

$fn = RET_STR_GET(ARGUMENT_DB_FILE_NAME);
$syear = RET_INT_POST(POST_FIELD_START_YEAR);
$smonth = RET_INT_POST(POST_FIELD_START_MONTH);
$sday = RET_INT_POST(POST_FIELD_START_DAY);
$eyear = RET_INT_POST(POST_FIELD_END_YEAR);
$emonth = RET_INT_POST(POST_FIELD_END_MONTH);
$eday = RET_INT_POST(POST_FIELD_END_DAY);

if ('' == $fn) A_TO(26);

$db_file = $accfolder.'/'.$fn;
$db_time = MDB_FILE_NAME_TO_DATE($fn).' '.MDB_FILE_NAME_TO_TIME($fn);
if (is_file($db_file)) {
	$THIS_WEB_URL .= '&amp;'.ARGUMENT_DB_FILE_NAME.'='.$fn;
	$con = @odbc_connect('Driver={Microsoft Access Driver (*.mdb)};Dbq='.$db_file, '', $stores[FIELD_STORES_MDB_PASSWORD], SQL_CUR_USE_DRIVER);
	if ($con) {
		P_HEADER_QUERY('換班 ('.$db_time.')');
		
		P_ADD('<div class="querybar"><button onclick="self.close();">關閉</button><button onclick="location.href=\''.$THIS_WEB_URL.'\';">最新</button>');
		P_ADD('<form action="'.$THIS_WEB_URL.'" method="post">');
		P_ADD('從: '.RET_SELECT_YEAR(POST_FIELD_START_YEAR, $syear).'-'.RET_SELECT_MONTH(POST_FIELD_START_MONTH, $smonth).'-'.RET_SELECT_DAY(POST_FIELD_START_DAY, $sday));
		P_ADD('　至: '.RET_SELECT_YEAR(POST_FIELD_END_YEAR, $eyear).'-'.RET_SELECT_MONTH(POST_FIELD_END_MONTH, $emonth).'-'.RET_SELECT_DAY(POST_FIELD_END_DAY, $eday));
		P_ADD('　<input type="submit" value="查詢"></form>');
		P_ADD('</div>');
		
		$h4 = '最新 48 筆';
		$sql = 'select top 48 * from ShiftTrade order by '.SHIFT_TRADE_UID.' desc';
		if ($syear > 0) {
		if ($smonth > 0) {
		if ($sday > 0) {
		if ($eyear > 0) {
		if ($emonth > 0) {
		if ($eday > 0) {
			$sdate = $syear.'-'.CONV_INT_TO_STR_WITH_ZERO($smonth).'-'.CONV_INT_TO_STR_WITH_ZERO($sday);
			$edate = $eyear.'-'.CONV_INT_TO_STR_WITH_ZERO($emonth).'-'.CONV_INT_TO_STR_WITH_ZERO($eday);
			$h4 = '從 '.$sdate.' 至 '.$edate;
			$sql = 'select * from ShiftTrade where '.SHIFT_TRADE_START_TIME.' between #'.$sdate.' 00:00:00# and #'.$edate.' 23:59:59# order by '.SHIFT_TRADE_UID.' desc';
		}
		}
		}
		}
		}
		}
		
		$c = 0;
		$rs = @odbc_exec($con, $sql);
		if ($rs) {
			while (odbc_fetch_row($rs)) {
				$tables[$c][SHIFT_TRADE_UID] = (int) odbc_result($rs, SHIFT_TRADE_UID);
				$ti = &$tables[$c];
				$ti[SHIFT_TRADE_START_TIME]	= odbc_result($rs, SHIFT_TRADE_START_TIME);
				$ti[SHIFT_TRADE_END_TIME]	= odbc_result($rs, SHIFT_TRADE_END_TIME);
				$ti[SHIFT_TRADE_ACCOUNT]	= odbc_result($rs, SHIFT_TRADE_ACCOUNT);
				$ti[SHIFT_TRADE_NAME]		= STR_TO_HTML(odbc_result($rs, SHIFT_TRADE_NAME));
				$ti[SHIFT_TRADE_TOTAL_PLAY]	= (int) odbc_result($rs, SHIFT_TRADE_TOTAL_PLAY);
				$ti[SHIFT_TRADE_TOTAL_MEMBER]	= (int) odbc_result($rs, SHIFT_TRADE_TOTAL_MEMBER);
				$ti[SHIFT_TRADE_REFUND_MEMBER]	= 0 - (int) odbc_result($rs, SHIFT_TRADE_REFUND_MEMBER);
				$ti[SHIFT_TRADE_TOTAL_STOCK]	= 0 - (int) odbc_result($rs, SHIFT_TRADE_TOTAL_STOCK);
				$ti[SHIFT_TRADE_TOTAL_SALE]	= (int) odbc_result($rs, SHIFT_TRADE_TOTAL_SALE);
				$ti[SHIFT_TRADE_ACCOUNTS]	= (int) odbc_result($rs, SHIFT_TRADE_ACCOUNTS);
				$ti[SHIFT_TRADE_INFO]		= odbc_result($rs, SHIFT_TRADE_INFO);
				++$c;
			}
			odbc_free_result($rs);
		}
		odbc_close($con);
		
		if ($c > 0) {
			$i = 1;
			$th = '<tr><th>No.</th><th>經手人</th><th>起始時間</th><th>結束時間</th><th>時數</th><th>登入金額</th><th>會員收入</th><th>點數退款</th><th>進貨支出</th><th>商品收入</th><th>結帳金額</th></tr>';
			P_ADD('<h4>'.$h4.'</h4><table class="list">');
			P_ADD($th);
			foreach($tables as $tID => $tROW) {
				if (0 != ($i & 1)) {
					$h = '<tr class="odd">';
				} else {
					$h = '<tr>';
				}
				$m = floor((strtotime($tROW[SHIFT_TRADE_END_TIME]) - strtotime($tROW[SHIFT_TRADE_START_TIME])) / 60);
				$h .= '<td>#'.$tROW[SHIFT_TRADE_UID].RET_SHIFT_TRADE_HAS_EVENT($tROW[SHIFT_TRADE_INFO]).'</td>';
				$h .= '<td>'.$tROW[SHIFT_TRADE_NAME].'</td>';
				$h .= '<td>'.$tROW[SHIFT_TRADE_START_TIME].'</td>';
				$h .= '<td>'.$tROW[SHIFT_TRADE_END_TIME].'</td>';
				$h .= '<td align="right">'.RET_HHMM_FROM_MINS($m).'</td>';
				$h .= '<td align="right">'.number_format($tROW[SHIFT_TRADE_TOTAL_PLAY], 0).'</td>';
				$h .= '<td align="right">'.number_format($tROW[SHIFT_TRADE_TOTAL_MEMBER], 0).'</td>';
				$h .= '<td align="right">'.number_format($tROW[SHIFT_TRADE_REFUND_MEMBER], 0).'</td>';
				$h .= '<td align="right">'.number_format($tROW[SHIFT_TRADE_TOTAL_STOCK], 0).'</td>';
				$h .= '<td align="right">'.number_format($tROW[SHIFT_TRADE_TOTAL_SALE], 0).'</td>';
				$h .= '<td align="right">'.number_format($tROW[SHIFT_TRADE_ACCOUNTS], 0).'</td>';
				$h .= '</tr>';
				P_ADD($h);
				if (0 == ($i % 10)) {
					P_ADD($th);
					$i = 1;
				} else {
					++$i;
				}
			}
			P_ADD('</table><br>');
		}
		
		P_PRINT_QUERY();
	} else {
		A_TO(42);
	}
} else {
	A_TO(27);
}

?>
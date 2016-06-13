<?php

if (!isset($stores)) exit();

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
		P_HEADER_QUERY('登入 ('.$db_time.')');
		
		P_ADD('<div class="querybar"><button onclick="self.close();">關閉</button><button onclick="location.href=\''.$THIS_WEB_URL.'\';">本班</button>');
		P_ADD('<form action="'.$THIS_WEB_URL.'" method="post">');
		P_ADD('從: '.RET_SELECT_YEAR(POST_FIELD_START_YEAR, $syear).'-'.RET_SELECT_MONTH(POST_FIELD_START_MONTH, $smonth).'-'.RET_SELECT_DAY(POST_FIELD_START_DAY, $sday));
		P_ADD('　至: '.RET_SELECT_YEAR(POST_FIELD_END_YEAR, $eyear).'-'.RET_SELECT_MONTH(POST_FIELD_END_MONTH, $emonth).'-'.RET_SELECT_DAY(POST_FIELD_END_DAY, $eday));
		P_ADD('　<input type="submit" value="查詢"></form>');
		P_ADD('</div>');
		
		$h4 = '目前班別';
		$sql = 'select DayTrade.*,Members.'.MEMBERS_ID.',userinfo.'.USER_INFO_NAME.' from (DayTrade left join Members on DayTrade.'.DAY_TRADE_MEMBER_ID.' = Members.'.MEMBERS_MID.') left join userinfo on DayTrade.'.DAY_TRADE_HANDLE_ID.' = userinfo.'.USER_INFO_ID.' where DayTrade.'.DAY_TRADE_DATE.' > #'.RET_CLASS_START_TIME($con).'# order by DayTrade.'.DAY_TRADE_DATE;				
		if ($syear > 0) {
		if ($smonth > 0) {
		if ($sday > 0) {
		if ($eyear > 0) {
		if ($emonth > 0) {
		if ($eday > 0) {
			$sdate = $syear.'-'.CONV_INT_TO_STR_WITH_ZERO($smonth).'-'.CONV_INT_TO_STR_WITH_ZERO($sday);
			$edate = $eyear.'-'.CONV_INT_TO_STR_WITH_ZERO($emonth).'-'.CONV_INT_TO_STR_WITH_ZERO($eday);
			$h4 = '從 '.$sdate.' 至 '.$edate;
			$sql = 'select DayTrade.*,Members.'.MEMBERS_ID.',userinfo.'.USER_INFO_NAME.' from (DayTrade left join Members on DayTrade.'.DAY_TRADE_MEMBER_ID.' = Members.'.MEMBERS_MID.') left join userinfo on DayTrade.'.DAY_TRADE_HANDLE_ID.' = userinfo.'.USER_INFO_ID.' where DayTrade.'.DAY_TRADE_DATE.' between #'.$sdate.' 00:00:00# and #'.$edate.' 23:59:59# order by DayTrade.'.DAY_TRADE_DATE;
		}
		}
		}
		}
		}
		}
		
		$c = 0;
		$rs = @odbc_exec($con, $sql);
		if ($rs) {
			$n = 1;
			while (odbc_fetch_row($rs)) {
				$tables[$c][DAY_TRADE_ID] = odbc_result($rs, DAY_TRADE_ID);
				$ti = &$tables[$c];
				$ti[DAY_TRADE_NO]		= $n;
				$ti[DAY_TRADE_DATE]		= odbc_result($rs, DAY_TRADE_DATE);
				$ti[DAY_TRADE_HANDLE_ID]	= odbc_result($rs, USER_INFO_NAME);
				$ti[DAY_TRADE_LOGIN_MODE]	= (int) odbc_result($rs, DAY_TRADE_LOGIN_MODE);
				$ti[DAY_TRADE_LOGIN_TIME]	= odbc_result($rs, DAY_TRADE_LOGIN_TIME);
				$ti[DAY_TRADE_START_MINUTE]	= odbc_result($rs, DAY_TRADE_START_MINUTE);
				$ti[DAY_TRADE_LOGOUT_TIME]	= odbc_result($rs, DAY_TRADE_LOGOUT_TIME);
				$ti[DAY_TRADE_MEMBER_ID]	= odbc_result($rs, MEMBERS_ID);
				$ti[DAY_TRADE_CUT_PIECES]	= odbc_result($rs, DAY_TRADE_CUT_PIECES);
				$ti[DAY_TRADE_TOTAL_MINUTES]	= (int) odbc_result($rs, DAY_TRADE_TOTAL_MINUTES);
				$ti[DAY_TRADE_PAY_MINUTES]	= (int) odbc_result($rs, DAY_TRADE_PAY_MINUTES);
				$ti[DAY_TRADE_MONEY]		= (float) odbc_result($rs, DAY_TRADE_MONEY);
				if (empty($ti[DAY_TRADE_MEMBER_ID])) {
					$ti[DAY_TRADE_MEMBER_ID] = '&nbsp;';
				}
				if (empty($ti[DAY_TRADE_HANDLE_ID])) {
					$ti[DAY_TRADE_HANDLE_ID] = '&nbsp;';
				}
				++$n;
				++$c;
			}
			odbc_free_result($rs);
		}
		odbc_close($con);
		
		if (isset($tables)) {
			$i = 1;
			$th = '<tr><th>No.</th><th>名稱</th><th>方式</th><th>登入時間</th><th>延遲</th><th>登出時間</th><th>總分鐘數</th><th>計價分鐘數</th><th>結帳金額</th><th>扣點數</th><th>會員</th><th>經手人</th><th>交易日期</th></tr>';
			P_ADD('<h4>'.$h4.'</h4><table class="list">');
			P_ADD($th);
			foreach($tables as $tID => $tROW) {
				if (0 != ($i & 1)) {
					$h = '<tr class="odd">';
				} else {
					$h = '<tr>';
				}
				$h .= '<td>'.$tROW[DAY_TRADE_NO].'</td>';
				$h .= '<td>'.$tROW[DAY_TRADE_ID].'</td>';
				$h .= '<td>'.RET_LOGIN_MODE($tROW[DAY_TRADE_LOGIN_MODE], $tROW[DAY_TRADE_MEMBER_ID]).'</td>';
				$h .= '<td>'.$tROW[DAY_TRADE_LOGIN_TIME].'</td>';
				$h .= '<td align="center">'.$tROW[DAY_TRADE_START_MINUTE].'</td>';
				$h .= '<td>'.$tROW[DAY_TRADE_LOGOUT_TIME].'</td>';
				$h .= '<td align="right">'.$tROW[DAY_TRADE_TOTAL_MINUTES].'</td>';
				$h .= '<td align="right">'.$tROW[DAY_TRADE_PAY_MINUTES].'</td>';
				$h .= '<td align="right">'.$tROW[DAY_TRADE_MONEY].'</td>';
				$h .= '<td align="right">'.$tROW[DAY_TRADE_CUT_PIECES].'</td>';
				$h .= '<td>'.$tROW[DAY_TRADE_MEMBER_ID].'</td>';
				$h .= '<td>'.$tROW[DAY_TRADE_HANDLE_ID].'</td>';
				$h .= '<td>'.$tROW[DAY_TRADE_DATE].'</td>';
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
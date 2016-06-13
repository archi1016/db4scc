<?php

if (!isset($stores)) exit();

function RET_CARD_TRADE_TYPE($t) {
	switch ($t) {
		case 0:
			return '購買';
		case 1:
			return '退款';
	}
	return '未知';
}

$fn = RET_STR_GET(ARGUMENT_DB_FILE_NAME);
$syear = RET_INT_POST(POST_FIELD_START_YEAR);
$smonth = RET_INT_POST(POST_FIELD_START_MONTH);
$sday = RET_INT_POST(POST_FIELD_START_DAY);
$eyear = RET_INT_POST(POST_FIELD_END_YEAR);
$emonth = RET_INT_POST(POST_FIELD_END_MONTH);
$eday = RET_INT_POST(POST_FIELD_END_DAY);
$memberid = RET_STR_POST(POST_FIELD_ACCOUNT);

if ('' == $fn) A_TO(26);

$db_file = $accfolder.'/'.$fn;
$db_time = MDB_FILE_NAME_TO_DATE($fn).' '.MDB_FILE_NAME_TO_TIME($fn);
if (is_file($db_file)) {
	$THIS_WEB_URL .= '&amp;'.ARGUMENT_DB_FILE_NAME.'='.$fn;
	$con = @odbc_connect('Driver={Microsoft Access Driver (*.mdb)};Dbq='.$db_file, '', $stores[FIELD_STORES_MDB_PASSWORD], SQL_CUR_USE_DRIVER);
	if ($con) {
		P_HEADER_QUERY('點數 ('.$db_time.')');
		
		P_ADD('<div class="querybar"><button onclick="self.close();">關閉</button><button onclick="location.href=\''.$THIS_WEB_URL.'\';">本班</button>');
		P_ADD('<form action="'.$THIS_WEB_URL.'" method="post">');
		P_ADD('從: '.RET_SELECT_YEAR(POST_FIELD_START_YEAR, $syear).'-'.RET_SELECT_MONTH(POST_FIELD_START_MONTH, $smonth).'-'.RET_SELECT_DAY(POST_FIELD_START_DAY, $sday));
		P_ADD('　至: '.RET_SELECT_YEAR(POST_FIELD_END_YEAR, $eyear).'-'.RET_SELECT_MONTH(POST_FIELD_END_MONTH, $emonth).'-'.RET_SELECT_DAY(POST_FIELD_END_DAY, $eday));
		P_ADD('　帳號: <input type="text" name="'.POST_FIELD_ACCOUNT.'" size="20" value="'.STR_TO_INPUT_VALUE($memberid).'">');
		P_ADD('　<input type="submit" value="查詢"></form>');
		P_ADD('</div>');
		
		if ('' == $memberid) {
			$w2 = '';
		} else {
			$w2 = 'and Members.'.MEMBERS_ID.' = \''.$memberid.'\'';
		}
		
		$h4 = '目前班別';
		$sql = 'select CardTrade.*,Members.'.MEMBERS_ID.',userinfo.'.USER_INFO_NAME.' from (CardTrade left join Members on CardTrade.'.CARD_TRADE_MEMBER_ID.' = Members.'.MEMBERS_MID.') left join userinfo on CardTrade.'.CARD_TRADE_HANDLE_ID.' = userinfo.'.USER_INFO_ID.' where IsNull(CardTrade.'.CARD_TRADE_HANDLE_ID.') '.$w2.' order by CardTrade.'.CARD_TRADE_DATE;			
		if ($syear > 0) {
		if ($smonth > 0) {
		if ($sday > 0) {
		if ($eyear > 0) {
		if ($emonth > 0) {
		if ($eday > 0) {
			$sdate = $syear.'-'.CONV_INT_TO_STR_WITH_ZERO($smonth).'-'.CONV_INT_TO_STR_WITH_ZERO($sday);
			$edate = $eyear.'-'.CONV_INT_TO_STR_WITH_ZERO($emonth).'-'.CONV_INT_TO_STR_WITH_ZERO($eday);
			$h4 = '從 '.$sdate.' 至 '.$edate;
			$sql = 'select CardTrade.*,Members.'.MEMBERS_ID.',userinfo.'.USER_INFO_NAME.' from (CardTrade left join Members on CardTrade.'.CARD_TRADE_MEMBER_ID.' = Members.'.MEMBERS_MID.') left join userinfo on CardTrade.'.CARD_TRADE_HANDLE_ID.' = userinfo.'.USER_INFO_ID.' where CardTrade.'.CARD_TRADE_DATE.' between #'.$sdate.' 00:00:00# and #'.$edate.' 23:59:59# '.$w2.' order by CardTrade.'.CARD_TRADE_DATE;
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
				$tables[$c][CARD_TRADE_NO]	= $n;
				$ti = &$tables[$c];
				$ti[CARD_TRADE_MEMBER_ID]	= odbc_result($rs, MEMBERS_ID);
				$ti[CARD_TRADE_DATE]		= odbc_result($rs, CARD_TRADE_DATE);
				$ti[CARD_TRADE_KIND]		= STR_TO_HTML(odbc_result($rs, CARD_TRADE_KIND));
				$ti[CARD_TRADE_BUY_PIECES]	= (int) odbc_result($rs, CARD_TRADE_BUY_PIECES);
				$ti[CARD_TRADE_RPIECES]		= (int) odbc_result($rs, CARD_TRADE_RPIECES);
				$ti[CARD_TRADE_REBATEP]		= (int) odbc_result($rs, CARD_TRADE_REBATEP);
				$ti[CARD_TRADE_MONEY]		= (int) odbc_result($rs, CARD_TRADE_MONEY);
				$ti[CARD_TRADE_HANDLE_ID]	= STR_TO_HTML(odbc_result($rs, USER_INFO_NAME));
				$ti[CARD_TRADE_TYPE]		= (int) odbc_result($rs, CARD_TRADE_TYPE);
				if (empty($ti[CARD_TRADE_HANDLE_ID])) {
					$ti[CARD_TRADE_HANDLE_ID] = '&nbsp;';
				}
				++$c;
				++$n;
			}
			odbc_free_result($rs);
		}
		odbc_close($con);
		
		if ($c > 0) {
			$i = 1;
			$th = '<tr><th>No.</th><th>類別</th><th>帳號</th><th>交易日期</th><th>卡別</th><th>購買點數</th><th>回饋點數</th><th>剩餘點數</th><th>交易金額</th><th>經手人</th></tr>';
			P_ADD('<h4>'.$h4.'</h4><table class="list">');
			P_ADD($th);
			foreach($tables as $tID => $tROW) {
				if (0 != ($i & 1)) {
					$h = '<tr class="odd">';
				} else {
					$h = '<tr>';
				}
				$h .= '<td>'.$tROW[CARD_TRADE_NO].'</td>';
				$h .= '<td>'.RET_CARD_TRADE_TYPE($tROW[CARD_TRADE_TYPE]).'</td>';
				$h .= '<td>'.$tROW[CARD_TRADE_MEMBER_ID].'</td>';
				$h .= '<td>'.$tROW[CARD_TRADE_DATE].'</td>';
				$h .= '<td>'.$tROW[CARD_TRADE_KIND].'</td>';
				$h .= '<td align="right">'.number_format($tROW[CARD_TRADE_BUY_PIECES], 0).'</td>';
				$h .= '<td align="right">'.number_format($tROW[CARD_TRADE_REBATEP], 0).'</td>';
				$h .= '<td align="right">'.number_format($tROW[CARD_TRADE_RPIECES], 0).'</td>';
				$h .= '<td align="right">'.number_format($tROW[CARD_TRADE_MONEY], 0).'</td>';
				$h .= '<td>'.$tROW[CARD_TRADE_HANDLE_ID].'</td>';
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
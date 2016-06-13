<?php

if (!isset($stores)) exit();

function RET_GOODS_TRADE_IO($v) {
	if (0 == $v) {
		return '進貨';
	} else {
		return '出貨';
	}
}

function RET_GOODS_TRADE_STOCK_QTY(&$ti) {
	if (0 == $ti[GOODS_TRADE_RESERVES]) {
		return '----';
	} else {
		return number_format($ti[GOODS_TRADE_STOCK_QTY], 0);
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
		P_HEADER_QUERY('銷售 ('.$db_time.')');
		
		P_ADD('<div class="querybar"><button onclick="self.close();">關閉</button><button onclick="location.href=\''.$THIS_WEB_URL.'\';">本班</button>');
		P_ADD('<form action="'.$THIS_WEB_URL.'" method="post">');
		P_ADD('從: '.RET_SELECT_YEAR(POST_FIELD_START_YEAR, $syear).'-'.RET_SELECT_MONTH(POST_FIELD_START_MONTH, $smonth).'-'.RET_SELECT_DAY(POST_FIELD_START_DAY, $sday));
		P_ADD('　至: '.RET_SELECT_YEAR(POST_FIELD_END_YEAR, $eyear).'-'.RET_SELECT_MONTH(POST_FIELD_END_MONTH, $emonth).'-'.RET_SELECT_DAY(POST_FIELD_END_DAY, $eday));
		P_ADD('　<input type="submit" value="查詢"></form>');
		P_ADD('</div>');
		
		$h4 = '目前班別';
		$sql = 'select GoodsTrade.*,GoodsData.'.GOODS_NAME.',GoodsData.'.GOODS_RESERVES.',Members.'.MEMBERS_ID.',userinfo.'.USER_INFO_NAME.' from ((GoodsTrade left join GoodsData on GoodsTrade.'.GOODS_TRADE_ID.' = GoodsData.'.GOODS_ID.') left join Members on GoodsTrade.'.GOODS_TRADE_MEMBER_ID.' = Members.'.MEMBERS_MID.') left join userinfo on GoodsTrade.'.GOODS_TRADE_HANDLE_ID.' = userinfo.'.USER_INFO_ID.' where GoodsTrade.'.GOODS_TRADE_DATE.' > #'.RET_CLASS_START_TIME($con).'# order by GoodsTrade.'.GOODS_TRADE_ID.',GoodsTrade.'.GOODS_TRADE_DATE;
		if ($syear > 0) {
		if ($smonth > 0) {
		if ($sday > 0) {
		if ($eyear > 0) {
		if ($emonth > 0) {
		if ($eday > 0) {
			$sdate = $syear.'-'.CONV_INT_TO_STR_WITH_ZERO($smonth).'-'.CONV_INT_TO_STR_WITH_ZERO($sday);
			$edate = $eyear.'-'.CONV_INT_TO_STR_WITH_ZERO($emonth).'-'.CONV_INT_TO_STR_WITH_ZERO($eday);
			$h4 = '從 '.$sdate.' 至 '.$edate;
			$sql = 'select GoodsTrade.*,GoodsData.'.GOODS_NAME.',GoodsData.'.GOODS_RESERVES.',Members.'.MEMBERS_ID.',userinfo.'.USER_INFO_NAME.' from ((GoodsTrade left join GoodsData on GoodsTrade.'.GOODS_TRADE_ID.' = GoodsData.'.GOODS_ID.') left join Members on GoodsTrade.'.GOODS_TRADE_MEMBER_ID.' = Members.'.MEMBERS_MID.') left join userinfo on GoodsTrade.'.GOODS_TRADE_HANDLE_ID.' = userinfo.'.USER_INFO_ID.' where GoodsTrade.'.GOODS_TRADE_DATE.' between #'.$sdate.' 00:00:00# and #'.$edate.' 23:59:59# order by GoodsTrade.'.GOODS_TRADE_ID.',GoodsTrade.'.GOODS_TRADE_DATE;
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
				$tables[$c][GOODS_TRADE_NO] 	= $n;
				$ti = &$tables[$c];
				$ti[GOODS_TRADE_IO]		= (int) odbc_result($rs, GOODS_TRADE_IO);
				$ti[GOODS_TRADE_ID]		= odbc_result($rs, GOODS_TRADE_ID);
				$ti[GOODS_TRADE_NAME]		= STR_TO_HTML(odbc_result($rs, GOODS_NAME));
				$ti[GOODS_TRADE_PRICE]		= (int) odbc_result($rs, GOODS_TRADE_PRICE);
				$ti[GOODS_TRADE_QTY]		= (int) odbc_result($rs, GOODS_TRADE_QTY);
				$ti[GOODS_TRADE_TOTAL]		= (int) odbc_result($rs, GOODS_TRADE_TOTAL);
				$ti[GOODS_TRADE_DATE]		= odbc_result($rs, GOODS_TRADE_DATE);
				$ti[GOODS_TRADE_TARGET]		= odbc_result($rs, GOODS_TRADE_TARGET);
				$ti[GOODS_TRADE_CHECKED]	= (int) odbc_result($rs, GOODS_TRADE_CHECKED);
				$ti[GOODS_TRADE_HANDLE_ID]	= STR_TO_HTML(odbc_result($rs, USER_INFO_NAME));
				$ti[GOODS_TRADE_MEMBER_ID]	= (int) odbc_result($rs, MEMBERS_ID);
				$ti[GOODS_TRADE_TOTAL_POINTS]	= (int) odbc_result($rs, GOODS_TRADE_TOTAL_POINTS);
				$ti[GOODS_TRADE_TRANS_MODE]	= (int) odbc_result($rs, GOODS_TRADE_TRANS_MODE);
				$ti[GOODS_TRADE_STOCK_QTY]	= (int) odbc_result($rs, GOODS_TRADE_STOCK_QTY);
				$ti[GOODS_TRADE_RESERVES]	= (int) odbc_result($rs, GOODS_RESERVES);
				if ('' == $ti[GOODS_TRADE_TARGET]) {
					$ti[GOODS_TRADE_TARGET] = '&nbsp;';
				}
				if ('' == $ti[GOODS_TRADE_MEMBER_ID]) {
					$ti[GOODS_TRADE_MEMBER_ID] = '&nbsp;';
				}
				++$n;
				++$c;
			}
			odbc_free_result($rs);
		}
		odbc_close($con);
		
		if ($c > 0) {
			$i = 1;
			$th = '<tr><th>No.</th><th>進出</th><th>編號</th><th>名稱</th><th>單價</th><th>數量</th><th>小計</th><th>庫存數量</th><th>會員</th><th>抵扣點數</th><th>交易日期</th><th>對象</th><th>經手人</th><th>已結算</th></tr>';
			P_ADD('<h4>'.$h4.'</h4><table class="list">');
			P_ADD($th);
			foreach($tables as $tID => $tROW) {
				if (0 != ($i & 1)) {
					$h = '<tr class="odd">';
				} else {
					$h = '<tr>';
				}
				$h .= '<td>'.$tROW[GOODS_TRADE_NO].'</td>';
				$h .= '<td>'.RET_GOODS_TRADE_IO($tROW[GOODS_TRADE_IO]).'</td>';
				$h .= '<td>'.$tROW[GOODS_TRADE_ID].'</td>';
				$h .= '<td>'.$tROW[GOODS_TRADE_NAME].'</td>';
				$h .= '<td align="right">'.number_format($tROW[GOODS_TRADE_PRICE], 0).'</td>';
				$h .= '<td align="right">'.number_format($tROW[GOODS_TRADE_QTY], 0).'</td>';
				$h .= '<td align="right">'.number_format($tROW[GOODS_TRADE_TOTAL], 0).'</td>';
				$h .= '<td align="right">'.RET_GOODS_TRADE_STOCK_QTY($tROW).'</td>';
				$h .= '<td>'.$tROW[GOODS_TRADE_MEMBER_ID].'</td>';
				$h .= '<td align="right">'.number_format($tROW[GOODS_TRADE_TOTAL_POINTS], 0).'</td>';
				$h .= '<td>'.$tROW[GOODS_TRADE_DATE].'</td>';
				$h .= '<td>'.$tROW[GOODS_TRADE_TARGET].'</td>';
				$h .= '<td>'.$tROW[GOODS_TRADE_HANDLE_ID].'</td>';
				$h .= '<td align="center">'.RET_CHECKBOX($tROW[GOODS_TRADE_CHECKED]).'</td>';
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
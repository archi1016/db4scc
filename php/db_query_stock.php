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
		P_HEADER_QUERY('盤點 ('.$db_time.')');
		
		P_ADD('<div class="querybar"><button onclick="self.close();">關閉</button><button onclick="location.href=\''.$THIS_WEB_URL.'\';">最新</button>');
		P_ADD('<form action="'.$THIS_WEB_URL.'" method="post">');
		P_ADD('從: '.RET_SELECT_YEAR(POST_FIELD_START_YEAR, $syear).'-'.RET_SELECT_MONTH(POST_FIELD_START_MONTH, $smonth).'-'.RET_SELECT_DAY(POST_FIELD_START_DAY, $sday));
		P_ADD('　至: '.RET_SELECT_YEAR(POST_FIELD_END_YEAR, $eyear).'-'.RET_SELECT_MONTH(POST_FIELD_END_MONTH, $emonth).'-'.RET_SELECT_DAY(POST_FIELD_END_DAY, $eday));
		P_ADD('　<input type="submit" value="查詢"></form>');
		P_ADD('</div>');
		
		$h4 = '最新 24 筆';
		$sql = 'select top 24 GoodsStockTaking.*,GoodsData.'.GOODS_NAME.',userinfo.'.USER_INFO_NAME.' from (GoodsStockTaking left join GoodsData on GoodsStockTaking.'.STOCK_TAKING_ID.' = GoodsData.'.GOODS_ID.') left join userinfo on GoodsStockTaking.'.STOCK_TAKING_HANDLE_ID.' = userinfo.'.USER_INFO_ID.' order by GoodsStockTaking.'.STOCK_TAKING_DATE;		
		if ($syear > 0) {
		if ($smonth > 0) {
		if ($sday > 0) {
		if ($eyear > 0) {
		if ($emonth > 0) {
		if ($eday > 0) {
			$sdate = $syear.'-'.CONV_INT_TO_STR_WITH_ZERO($smonth).'-'.CONV_INT_TO_STR_WITH_ZERO($sday);
			$edate = $eyear.'-'.CONV_INT_TO_STR_WITH_ZERO($emonth).'-'.CONV_INT_TO_STR_WITH_ZERO($eday);
			$h4 = '從 '.$sdate.' 至 '.$edate;
			$sql = 'select GoodsStockTaking.*,GoodsData.'.GOODS_NAME.',userinfo.'.USER_INFO_NAME.' from (GoodsStockTaking left join GoodsData on GoodsStockTaking.'.STOCK_TAKING_ID.' = GoodsData.'.GOODS_ID.') left join userinfo on GoodsStockTaking.'.STOCK_TAKING_HANDLE_ID.' = userinfo.'.USER_INFO_ID.' where GoodsStockTaking.'.STOCK_TAKING_DATE.' between #'.$sdate.' 00:00:00# and #'.$edate.' 23:59:59# order by GoodsStockTaking.'.STOCK_TAKING_DATE;
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
				$tables[$c][STOCK_TAKING_NO] 	= $n;
				$ti = &$tables[$c];
				$ti[STOCK_TAKING_ID]		= odbc_result($rs, STOCK_TAKING_ID);
				$ti[STOCK_TAKING_NAME]		= STR_TO_HTML(odbc_result($rs, GOODS_NAME));
				$ti[STOCK_TAKING_OLD_QTY]	= (int) odbc_result($rs, STOCK_TAKING_OLD_QTY);
				$ti[STOCK_TAKING_NEW_QTY]	= (int) odbc_result($rs, STOCK_TAKING_NEW_QTY);
				$ti[STOCK_TAKING_DATE]		= odbc_result($rs, STOCK_TAKING_DATE);
				$ti[STOCK_TAKING_HANDLE_ID]	= STR_TO_HTML(odbc_result($rs, USER_INFO_NAME));
				++$n;
				++$c;
			}
			odbc_free_result($rs);
		}
		odbc_close($con);
		
		if ($c > 0) {
			$i = 1;
			$th = '<tr><th>No.</th><th>編號</th><th>名稱</th><th>原數量</th><th>新數量</th><th>盤點日期</th><th>經手人</th></tr>';
			P_ADD('<h4>'.$h4.'</h4><table class="list">');
			P_ADD($th);
			foreach($tables as $tID => $tROW) {
				if (0 != ($i & 1)) {
					$h = '<tr class="odd">';
				} else {
					$h = '<tr>';
				}
				$h .= '<td>'.$tROW[STOCK_TAKING_NO].'</td>';
				$h .= '<td>'.$tROW[STOCK_TAKING_ID].'</td>';
				$h .= '<td>'.$tROW[STOCK_TAKING_NAME].'</td>';
				$h .= '<td align="right">'.number_format($tROW[STOCK_TAKING_OLD_QTY], 0).'</td>';
				$h .= '<td align="right">'.number_format($tROW[STOCK_TAKING_NEW_QTY], 0).'</td>';
				$h .= '<td>'.$tROW[STOCK_TAKING_DATE].'</td>';
				$h .= '<td>'.$tROW[STOCK_TAKING_HANDLE_ID].'</td>';
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
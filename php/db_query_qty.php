<?php

if (!isset($stores)) exit();

function RET_GOODS_QTY(&$ti) {
	if (0 == $ti[GOODS_RESERVES]) {
		return '----';
	} else {
		return number_format($ti[GOODS_QTY], 0);
	}
}

function RET_GOODS_STATUS($v) {
	switch ($v) {
		case 0:
			return '公開';
			
		case 1:
			return '內部';
			
		case 2:
			return '停賣';
			
	}
	return '未知';
}

$fn = RET_STR_GET(ARGUMENT_DB_FILE_NAME);

if ('' == $fn) A_TO(26);

$db_file = $accfolder.'/'.$fn;
$db_time = MDB_FILE_NAME_TO_DATE($fn).' '.MDB_FILE_NAME_TO_TIME($fn);
if (is_file($db_file)) {
	$con = @odbc_connect('Driver={Microsoft Access Driver (*.mdb)};Dbq='.$db_file, '', $stores[FIELD_STORES_MDB_PASSWORD], SQL_CUR_USE_DRIVER);
	if ($con) {
		P_HEADER_QUERY('商品 ('.$db_time.')');
		
		P_ADD('<div class="querybar"><button onclick="self.close();">關閉</button></div>');
		
		$c = 0;
		$rs = @odbc_exec($con, 'select * from GoodsData order by '.GOODS_ID);
		if ($rs) {
			$n = 1;
			while (odbc_fetch_row($rs)) {
				$tables[$c][GOODS_NO]		= $n;
				$ti = &$tables[$c];
				$ti[GOODS_ID]			= odbc_result($rs, GOODS_ID);
				$ti[GOODS_NAME]			= STR_TO_HTML(odbc_result($rs, GOODS_NAME));
				$ti[GOODS_KIND]			= STR_TO_HTML(odbc_result($rs, GOODS_KIND));
				$ti[GOODS_PRICE]		= (int) odbc_result($rs, GOODS_PRICE);
				$ti[GOODS_QTY]			= (int) odbc_result($rs, GOODS_QTY);
				$ti[GOODS_POINTS]		= (int) odbc_result($rs, GOODS_POINTS);
				$ti[GOODS_RESERVES]		= (int) odbc_result($rs, GOODS_RESERVES);
				$ti[GOODS_NO_DISCOUNT]		= (int) odbc_result($rs, GOODS_NO_DISCOUNT);
				$ti[GOODS_SALE_STATUS]		= (int) odbc_result($rs, GOODS_SALE_STATUS);
				$ti[GOODS_PURCHASE_PRICE]	= (int) odbc_result($rs, GOODS_PURCHASE_PRICE);
				++$c;
				++$n;
			}
			odbc_free_result($rs);
		}
		odbc_close($con);
		
		if ($c > 0) {	
			$i = 1;
			$th = '<tr><th>No.</th><th>類別</th><th>編號</th><th>名稱</th><th>進貨價格</th><th>銷售價格</th><th>庫存數量</th><th>兌換點數</th><th>不折扣</th><th>狀態</th></tr>';
			P_ADD('<h4>庫存</h4><table class="list">');
			P_ADD($th);
			foreach($tables as $tID => $tROW) {	
				if (0 != ($i & 1)) {
					$h = '<tr class="odd">';
				} else {
					$h = '<tr>';
				}
				$h .= '<td>'.$tROW[GOODS_NO].'</td>';
				$h .= '<td>'.$tROW[GOODS_KIND].'</td>';
				$h .= '<td>'.$tROW[GOODS_ID].'</td>';
				$h .= '<td>'.$tROW[GOODS_NAME].'</td>';
				$h .= '<td align="right">'.number_format($tROW[GOODS_PURCHASE_PRICE], 0).'</td>';
				$h .= '<td align="right">'.number_format($tROW[GOODS_PRICE], 0).'</td>';
				$h .= '<td align="right">'.RET_GOODS_QTY($tROW).'</td>';
				$h .= '<td align="right">'.number_format($tROW[GOODS_POINTS], 0).'</td>';
				$h .= '<td align="center">'.RET_CHECKBOX($tROW[GOODS_NO_DISCOUNT]).'</td>';
				$h .= '<td>'.RET_GOODS_STATUS($tROW[GOODS_SALE_STATUS]).'</td>';
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
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
		P_HEADER_QUERY('�ƥ� ('.$db_time.')');
		
		P_ADD('<div class="querybar"><button onclick="self.close();">����</button><button onclick="location.href=\''.$THIS_WEB_URL.'\';">�̷s</button>');
		P_ADD('<form action="'.$THIS_WEB_URL.'" method="post">');
		P_ADD('�q: '.RET_SELECT_YEAR(POST_FIELD_START_YEAR, $syear).'-'.RET_SELECT_MONTH(POST_FIELD_START_MONTH, $smonth).'-'.RET_SELECT_DAY(POST_FIELD_START_DAY, $sday));
		P_ADD('�@��: '.RET_SELECT_YEAR(POST_FIELD_END_YEAR, $eyear).'-'.RET_SELECT_MONTH(POST_FIELD_END_MONTH, $emonth).'-'.RET_SELECT_DAY(POST_FIELD_END_DAY, $eday));
		P_ADD('�@<input type="submit" value="�d��"></form>');
		P_ADD('</div>');
		
		$h4 = '�̷s 12 ��';
		$sql = 'select top 12 * from ShiftTrade where '.SHIFT_TRADE_INFO.' <> \'\' order by '.SHIFT_TRADE_UID.' desc';
		if ($syear > 0) {
		if ($smonth > 0) {
		if ($sday > 0) {
		if ($eyear > 0) {
		if ($emonth > 0) {
		if ($eday > 0) {
			$sdate = $syear.'-'.CONV_INT_TO_STR_WITH_ZERO($smonth).'-'.CONV_INT_TO_STR_WITH_ZERO($sday);
			$edate = $eyear.'-'.CONV_INT_TO_STR_WITH_ZERO($emonth).'-'.CONV_INT_TO_STR_WITH_ZERO($eday);
			$h4 = '�q '.$sdate.' �� '.$edate;
			$sql = 'select * from ShiftTrade where ('.SHIFT_TRADE_INFO.' <> \'\') and ('.SHIFT_TRADE_START_TIME.' between #'.$sdate.' 00:00:00# and #'.$edate.' 23:59:59#) order by '.SHIFT_TRADE_UID.' desc';
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
				$ti[SHIFT_TRADE_INFO]		= STR_TO_HTML(odbc_result($rs, SHIFT_TRADE_INFO));
				++$c;
			}
			odbc_free_result($rs);
		}
		odbc_close($con);
		
		if ($c > 0) {
			$i = 1;
			$th = '<tr><th>No.</th><th>�g��H</th><th>�_�l�ɶ�</th><th>�����ɶ�</th><th>�ɼ�</th></tr>';
			P_ADD('<h4>'.$h4.'</h4><table class="list">');
			P_ADD($th);
			foreach($tables as $tID => $tROW) {
				$m = floor((strtotime($tROW[SHIFT_TRADE_END_TIME]) - strtotime($tROW[SHIFT_TRADE_START_TIME])) / 60);
				$h = '<tr class="odd" valign="top">';
				$h .= '<td rowspan="2">#'.$tROW[SHIFT_TRADE_UID].'</td>';
				$h .= '<td>'.$tROW[SHIFT_TRADE_NAME].'</td>';
				$h .= '<td>'.$tROW[SHIFT_TRADE_START_TIME].'</td>';
				$h .= '<td>'.$tROW[SHIFT_TRADE_END_TIME].'</td>';
				$h .= '<td align="right">'.RET_HHMM_FROM_MINS($m).'</td>';
				$h .= '</tr>';
				P_ADD($h);
				P_ADD('<tr><td colspan="4" class="event">'.$tROW[SHIFT_TRADE_INFO].'</td></tr>');
				
				++$i;
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
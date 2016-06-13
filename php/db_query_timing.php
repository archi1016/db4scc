<?php

if (!isset($stores)) exit();

function FIND_FIRST_LOGIN_TIME($con, $table_id, &$ti) {
	if (0 == $ti[DAY_TRADE_LOGIN_MODE]) {
		$ti[DAY_TRADE_LOGOUT_TIME] = '&nbsp;';
		$ti[DAY_TRADE_TOTAL_MINUTES] = -1;
		return;
	}
	
	while (false !== strpos($ti[DAY_TRADE_PERSON_ID], '#')) {
		$rs = @odbc_exec($con, 'select * from DayTrade where '.DAY_TRADE_LOGOUT_TIME.' = #'.$ti[DAY_TRADE_LOGIN_TIME].'# and '.DAY_TRADE_ID.' = \''.$table_id.'\'');
		if ($rs) {
			if (odbc_fetch_row($rs)) {
				$ti[DAY_TRADE_LOGIN_TIME]	= odbc_result($rs, DAY_TRADE_LOGIN_TIME);
				$ti[DAY_TRADE_PERSON_ID]	= odbc_result($rs, DAY_TRADE_PERSON_ID);
				$ti[DAY_TRADE_TOTAL_MINUTES]	+= (int) odbc_result($rs, DAY_TRADE_TOTAL_MINUTES);
				$ti[DAY_TRADE_MONEY]		+= (float) odbc_result($rs, DAY_TRADE_MONEY);
			}
			odbc_free_result($rs);
		} else {
			break;
		}
	}
}

function RET_TOTAL_TABLES($con) {
	$c = 0;
	$rs = @odbc_exec($con, 'select * from Params where ParamName = \'COMPTOTAL\'');
	if ($rs) {
		if (odbc_fetch_row($rs)) {
			$c = odbc_result($rs, 'ParamValue');
		}
		odbc_free_result($rs);
	}
	return $c;
}

function RET_USED_MINS(&$ti, $curt) {
	if (0 == $ti[DAY_TRADE_LOGIN_MODE]) {
		return '+'.floor((strtotime($curt) - strtotime($ti[DAY_TRADE_LOGIN_TIME])) / 60);
	} else {
		return '-'.floor((strtotime($ti[DAY_TRADE_LOGOUT_TIME]) - strtotime($curt)) / 60);
	}
}

$fn = RET_STR_GET(ARGUMENT_DB_FILE_NAME);

if ('' == $fn) A_TO(26);

$db_file = $accfolder.'/'.$fn;
$db_time = MDB_FILE_NAME_TO_DATE($fn).' '.MDB_FILE_NAME_TO_TIME($fn);
if (is_file($db_file)) {
	$con = @odbc_connect('Driver={Microsoft Access Driver (*.mdb)};Dbq='.$db_file, '', $stores[FIELD_STORES_MDB_PASSWORD], SQL_CUR_USE_DRIVER);
	if ($con) {
		P_HEADER_QUERY('桌況 ('.$db_time.')');
		
		P_ADD('<div class="querybar"><button onclick="self.close();">關閉</button></div>');
		
		$sql = 'select DayTrade.*,Members.'.MEMBERS_ID.' from DayTrade left join Members on DayTrade.'.DAY_TRADE_MEMBER_ID.' = Members.'.MEMBERS_MID.' where DayTrade.'.DAY_TRADE_LOGOUT_TIME.' > #'.$db_time.'# or IsNull(DayTrade.'.DAY_TRADE_LOGOUT_TIME.') order by DayTrade.'.DAY_TRADE_ID.',DayTrade.'.DAY_TRADE_LOGOUT_TIME.' desc';
		$rs = @odbc_exec($con, $sql);
		if ($rs) {
			$n = 1;
			while (odbc_fetch_row($rs)) {
				$table_id = odbc_result($rs, DAY_TRADE_ID);			
				if (!isset($tables[$table_id])) {
					$tables[$table_id][DAY_TRADE_NO] = $n;
					$ti = &$tables[$table_id];
					$ti[DAY_TRADE_LOGIN_MODE]	= (int) odbc_result($rs, DAY_TRADE_LOGIN_MODE);
					$ti[DAY_TRADE_LOGIN_TIME]	= odbc_result($rs, DAY_TRADE_LOGIN_TIME);
					$ti[DAY_TRADE_LOGOUT_TIME]	= odbc_result($rs, DAY_TRADE_LOGOUT_TIME);
					$ti[DAY_TRADE_MEMBER_ID]	= odbc_result($rs, MEMBERS_ID);
					$ti[DAY_TRADE_PERSON_ID]	= odbc_result($rs, DAY_TRADE_PERSON_ID);
					$ti[DAY_TRADE_TOTAL_MINUTES]	= (int) odbc_result($rs, DAY_TRADE_TOTAL_MINUTES);
					$ti[DAY_TRADE_MONEY]		= (float) odbc_result($rs, DAY_TRADE_MONEY);
					$ti[DAY_TRADE_FLAGS]		= (int) odbc_result($rs, DAY_TRADE_FLAGS);
					if (empty($ti[DAY_TRADE_MEMBER_ID])) {
						$ti[DAY_TRADE_MEMBER_ID] = '&nbsp;';
					}
					FIND_FIRST_LOGIN_TIME($con, $table_id, $ti);
					FIND_FLAGS($ti);
					++$n;
				}
			}
			odbc_free_result($rs);
		}
		odbc_close($con);
		
		if (isset($tables)) {		
			$i = 1;
			$th = '<tr><th>No.</th><th>名稱</th><th>狀態</th><th>登入</th><th>登出</th><th>長度</th><th>剩餘</th><th>金額</th><th>會員</th><th>包台轉</th></tr>';
			P_ADD('<h4>開台: '.count($tables).' / '.RET_TOTAL_TABLES($con).'</h4><table class="list">');
			P_ADD($th);
			foreach($tables as $tID => $tROW) {
				if (0 != ($i & 1)) {
					$h = '<tr class="odd">';
				} else {
					$h = '<tr>';
				}
				$h .= '<td>'.$tROW[DAY_TRADE_NO].'</td>';
				$h .= '<td>'.$tID.'</td>';
				$h .= '<td>'.RET_LOGIN_MODE($tROW[DAY_TRADE_LOGIN_MODE], $tROW[DAY_TRADE_MEMBER_ID]).'</td>';
				$h .= '<td>'.substr($tROW[DAY_TRADE_LOGIN_TIME], -8).'</td>';
				$h .= '<td>'.substr($tROW[DAY_TRADE_LOGOUT_TIME], -8).'</td>';
				$h .= '<td align="right">'.RET_HHMM_FROM_MINS($tROW[DAY_TRADE_TOTAL_MINUTES]).'</td>';
				$h .= '<td align="right">'.RET_USED_MINS($tROW, $db_time).' <small>Mins</small></td>';
				$h .= '<td align="right">'.$tROW[DAY_TRADE_MONEY].'</td>';
				$h .= '<td>'.$tROW[DAY_TRADE_MEMBER_ID].'</td>';
				$h .= '<td>'.$tROW[DAY_TRADE_FLAGS].'</td>';
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
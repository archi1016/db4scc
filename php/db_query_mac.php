<?php

if (!isset($stores)) exit();

$fn = RET_STR_GET(ARGUMENT_DB_FILE_NAME);

if ('' == $fn) A_TO(26);

$db_file = $accfolder.'/'.$fn;
$db_time = MDB_FILE_NAME_TO_DATE($fn).' '.MDB_FILE_NAME_TO_TIME($fn);
if (is_file($db_file)) {
	$con = @odbc_connect('Driver={Microsoft Access Driver (*.mdb)};Dbq='.$db_file, '', $stores[FIELD_STORES_MDB_PASSWORD], SQL_CUR_USE_DRIVER);
	if ($con) {
		P_HEADER_QUERY('位址 ('.$db_time.')');
		
		P_ADD('<div class="querybar"><button onclick="self.close();">關閉</button></div>');
		
		$rs = @odbc_exec($con, 'select * from Connectstatus order by '.CONNECT_STATUS_ID);
		if ($rs) {
			$n = 1;
			while (odbc_fetch_row($rs)) {
				$ip = odbc_result($rs, CONNECT_STATUS_IP_ADDRESS);
				if (!empty($ip)) {
					$table_id = odbc_result($rs, CONNECT_STATUS_ID);
					$tables[$table_id][CONNECT_STATUS_NO] = $n;
					$ti = &$tables[$table_id];
					$ti[CONNECT_STATUS_IP_ADDRESS]	= $ip;
					$ti[CONNECT_STATUS_MAC_ADDRESS]	= odbc_result($rs, CONNECT_STATUS_MAC_ADDRESS);
					++$n;
				}
			}
			odbc_free_result($rs);
		}
		odbc_close($con);
		
		if (isset($tables)) {
			$arp = "@echo off\n\nC:\ncd \windows\system\n";
				
			$i = 1;
			$th = '<tr><th>No.</th><th>名稱</th><th>區網位址</th><th>實體位址</th></tr>';
			P_ADD('<h4>清單</h4><table class="list">');
			P_ADD($th);
			foreach($tables as $tID => $tROW) {
				$arp .= "arp -s ".$tROW[CONNECT_STATUS_IP_ADDRESS]." ".str_replace('.', '-', $tROW[CONNECT_STATUS_MAC_ADDRESS])."\n";
					
				if (0 != ($i & 1)) {
					$h = '<tr class="odd">';
				} else {
					$h = '<tr>';
				}
				$h .= '<td>'.$tROW[CONNECT_STATUS_NO].'</td>';
				$h .= '<td>'.$tID.'</td>';
				$h .= '<td>'.$tROW[CONNECT_STATUS_IP_ADDRESS].'</td>';
				$h .= '<td>'.$tROW[CONNECT_STATUS_MAC_ADDRESS].'</td>';
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
				
			P_ADD('<h4>arp綁定 (arp.cmd)</h4>');
			P_ADD('<textarea rows="16" class="arp">'.$arp.'</textarea><br>');
		}
		
		P_PRINT_QUERY();
	} else {
		A_TO(42);
	}
} else {
	A_TO(27);
}

?>
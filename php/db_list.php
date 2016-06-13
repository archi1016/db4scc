<?php

if (!isset($stores)) exit();

P_HEADER(STR_TO_HTML($stores[FIELD_STORES_NAME]), STR_TO_HTML($stores[FIELD_STORES_TELEPHONE]).', '.STR_TO_HTML($stores[FIELD_STORES_ADDRESS]));

$db_action = $THIS_WEB_URL.'&amp;'.ARGUMENT_DB_ACTION.'=';
$db_action_query_shift = $db_action.DB_ACTION_QUERY_SHIFT.'&amp;'.ARGUMENT_DB_FILE_NAME.'=';
$db_action_query_event = $db_action.DB_ACTION_QUERY_EVENT.'&amp;'.ARGUMENT_DB_FILE_NAME.'=';
$db_action_query_login = $db_action.DB_ACTION_QUERY_LOGIN.'&amp;'.ARGUMENT_DB_FILE_NAME.'=';
$db_action_query_card = $db_action.DB_ACTION_QUERY_CARD.'&amp;'.ARGUMENT_DB_FILE_NAME.'=';
$db_action_query_goods = $db_action.DB_ACTION_QUERY_GOODS.'&amp;'.ARGUMENT_DB_FILE_NAME.'=';
$db_action_query_qty = $db_action.DB_ACTION_QUERY_QTY.'&amp;'.ARGUMENT_DB_FILE_NAME.'=';
$db_action_query_stock = $db_action.DB_ACTION_QUERY_STOCK.'&amp;'.ARGUMENT_DB_FILE_NAME.'=';
$db_action_query_timing = $db_action.DB_ACTION_QUERY_TIMING.'&amp;'.ARGUMENT_DB_FILE_NAME.'=';
$db_action_query_mac = $db_action.DB_ACTION_QUERY_MAC.'&amp;'.ARGUMENT_DB_FILE_NAME.'=';
$db_action_download = $db_action.DB_ACTION_DOWNLOAD_DB.'&amp;'.ARGUMENT_DB_FILE_NAME.'=';
$db_action_delete = $db_action.DB_ACTION_DELETE_DB.'&amp;'.ARGUMENT_DB_FILE_NAME.'=';

P_ADD('<script>');
P_ADD('function MDB_CHECK_DELETE(t) {');
P_ADD('	return confirm("確定要刪除 "+t+" 的備份資料庫嗎？");');
P_ADD('}');
P_ADD('</script>');

P_ADD('<h2>資料庫清單</h2>');
P_ADD('<table class="db">');
P_ADD('<tr><th width="40">No.</th><th width="110">日期</th><th width="85">時間</th><th width="80">容量</th><th>查詢</th><th width="85">操作</th></tr>');
if (SEARCH_FILES_FORM_FOLDER($accfolder, FILE_EXT_MDB, $rfiles)) {
	sort($rfiles, SORT_STRING);
	$files = array_reverse($rfiles);
	$c = count($files);
	$i = 0;
	while ($i < $c) {
		$fdate = MDB_FILE_NAME_TO_DATE($files[$i]);
		$ftime = MDB_FILE_NAME_TO_TIME($files[$i]);
		$fsize = MDB_GET_FILE_SIZE($accfolder.'/'.$files[$i]);
		if (0 == ($i & 1)) {
			$h = '<tr class="odd">';
		} else {
			$h = '<tr>';
		}
		$h .= '<td>'.($i+1).'</td><td>'.$fdate.'</td><td>'.$ftime.'</td><td align="right">'.$fsize.'</td><td>';
		$h .= '<a href="'.$db_action_query_shift.$files[$i].'" target="_blank">換班</a>';
		$h .= '<a href="'.$db_action_query_event.$files[$i].'" target="_blank">事件</a>';
		$h .= '<a href="'.$db_action_query_login.$files[$i].'" target="_blank">登入</a>';
		$h .= '<a href="'.$db_action_query_card.$files[$i].'" target="_blank">點數</a>';
		$h .= '<a href="'.$db_action_query_goods.$files[$i].'" target="_blank">銷售</a>';
		$h .= '<a href="'.$db_action_query_qty.$files[$i].'" target="_blank">商品</a>';
		$h .= '<a href="'.$db_action_query_stock.$files[$i].'" target="_blank">盤點</a>';
		$h .= '<a href="'.$db_action_query_timing.$files[$i].'" target="_blank">桌況</a>';
		$h .= '<a href="'.$db_action_query_mac.$files[$i].'" target="_blank">位址</a>';
		$h .= '</td><td><small>';
		$h .= '<a href="'.$db_action_download.$files[$i].'">下載</a>';
		$h .= '<a href="'.$db_action_delete.$files[$i].'" onclick="return MDB_CHECK_DELETE(\''.$fdate.' '.$ftime.'\');">刪除</a>';
		$h .= '</small></td></tr>';
		P_ADD($h);
		++$i;
	}
}
P_ADD('</table><div class="logoff"><a href="'.$THIS_WEB_URL.'">刷新</a>&nbsp;|&nbsp;<a href="logoff.php?'.ARGUMENT_SESSION_TOKEN.'='.$SESSION_TOKEN.'">登出</a></div>');

P_ADD('<h2>綁定IP</h2>');
P_ADD('<form action="'.$db_action.DB_ACTION_UPDATE_IP.'" method="post">');
P_ADD('櫃台位址: <input type="text" name="'.POST_FIELD_IP.'" size="20" value="'.STR_TO_INPUT_VALUE($stores[FIELD_STORES_IP]).'"> (目前網路位址: '.$_SERVER['REMOTE_ADDR'].')<br>');
P_ADD('<div class="submit"><input type="submit" value="更新"></div>');
P_ADD('</form>');

P_ADD('<h2>店家資料</h2>');
P_ADD('<form action="'.$db_action.DB_ACTION_UPDATE_STORES.'" method="post">');
P_ADD('招牌名稱: <input type="text" name="'.POST_FIELD_NAME.'" size="32" value="'.STR_TO_INPUT_VALUE($stores[FIELD_STORES_NAME]).'"><br>');
P_ADD('連絡電話: <input type="text" name="'.POST_FIELD_TELEPHONE.'" size="24" value="'.STR_TO_INPUT_VALUE($stores[FIELD_STORES_TELEPHONE]).'"><br>');
P_ADD('營業地址: <input type="text" name="'.POST_FIELD_ADDRESS.'" size="52" value="'.STR_TO_INPUT_VALUE($stores[FIELD_STORES_ADDRESS]).'"><br>');
P_ADD('授權序號: <input type="text" name="'.POST_FIELD_KEY.'" size="32" value="'.STR_TO_INPUT_VALUE($stores[FIELD_STORES_USB_KEYPRO]).'"> (USB鎖的序號)<br>');
P_ADD('<div class="submit"><input type="submit" value="更新"></div>');
P_ADD('</form>');

P_ADD('<h2>修改密碼</h2>');
P_ADD('<form action="'.$db_action.DB_ACTION_UPDATE_PASSWORD.'" method="post">');
P_ADD('舊的密碼: <input type="password" name="'.POST_FIELD_PASSWORD_OLD.'" size="20" value=""><br>');
P_ADD('新的密碼: <input type="password" name="'.POST_FIELD_PASSWORD.'" size="20" value=""><br>');
P_ADD('確認密碼: <input type="password" name="'.POST_FIELD_PASSWORD_2.'" size="20" value=""><br>');
P_ADD('<div class="submit"><input type="submit" value="修改"></div>');
P_ADD('</form>');

P_ADD('<h2>查詢資料庫用連線密碼</h2>');
P_ADD('<form action="'.$db_action.DB_ACTION_DATABASE_PASSWORD.'" method="post">');
P_ADD('連線密碼: <input type="password" name="'.POST_FIELD_PASSWORD.'" size="20" value=""> (留白則使用預設的連線密碼)<br>');
P_ADD('<div class="submit"><input type="submit" value="確定"></div>');
P_ADD('</form>');

P_ADD('<h2>帳號資料</h2>');
P_ADD('<div class="sinfo">註冊序號: <mark>'.$stores[FIELD_STORES_KEY].'</mark><br>有效期限: <mark>'.$stores[FIELD_STORES_TIMEOUT].'</mark><br><br></div>');

P_ADD('<h2>延長期限</h2>');
P_ADD('<form action="'.$db_action.DB_ACTION_EXTEND_TIMEOUT.'" method="post">');
P_ADD('儲值卡號: <input type="text" name="'.POST_FIELD_CODE.'" size="48" value=""><br>');
P_ADD('<div class="submit"><input type="submit" value="確定"></div>');
P_ADD('</form>');

P_ADD('<h2>自爆</h2>');
P_ADD('<form action="'.$db_action.DB_ACTION_KILL_SELF.'" method="post">');
P_ADD('確認開關: <input type="checkbox" name="'.POST_FIELD_CONFIRM.'" value="Y"><br>');
P_ADD('<div class="submit"><input type="submit" value="確定"></div>');
P_ADD('</form>');

P_PRINT();

?>
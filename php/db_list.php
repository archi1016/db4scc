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
P_ADD('	return confirm("�T�w�n�R�� "+t+" ���ƥ���Ʈw�ܡH");');
P_ADD('}');
P_ADD('</script>');

P_ADD('<h2>��Ʈw�M��</h2>');
P_ADD('<table class="db">');
P_ADD('<tr><th width="40">No.</th><th width="110">���</th><th width="85">�ɶ�</th><th width="80">�e�q</th><th>�d��</th><th width="85">�ާ@</th></tr>');
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
		$h .= '<a href="'.$db_action_query_shift.$files[$i].'" target="_blank">���Z</a>';
		$h .= '<a href="'.$db_action_query_event.$files[$i].'" target="_blank">�ƥ�</a>';
		$h .= '<a href="'.$db_action_query_login.$files[$i].'" target="_blank">�n�J</a>';
		$h .= '<a href="'.$db_action_query_card.$files[$i].'" target="_blank">�I��</a>';
		$h .= '<a href="'.$db_action_query_goods.$files[$i].'" target="_blank">�P��</a>';
		$h .= '<a href="'.$db_action_query_qty.$files[$i].'" target="_blank">�ӫ~</a>';
		$h .= '<a href="'.$db_action_query_stock.$files[$i].'" target="_blank">�L�I</a>';
		$h .= '<a href="'.$db_action_query_timing.$files[$i].'" target="_blank">��p</a>';
		$h .= '<a href="'.$db_action_query_mac.$files[$i].'" target="_blank">��}</a>';
		$h .= '</td><td><small>';
		$h .= '<a href="'.$db_action_download.$files[$i].'">�U��</a>';
		$h .= '<a href="'.$db_action_delete.$files[$i].'" onclick="return MDB_CHECK_DELETE(\''.$fdate.' '.$ftime.'\');">�R��</a>';
		$h .= '</small></td></tr>';
		P_ADD($h);
		++$i;
	}
}
P_ADD('</table><div class="logoff"><a href="'.$THIS_WEB_URL.'">��s</a>&nbsp;|&nbsp;<a href="logoff.php?'.ARGUMENT_SESSION_TOKEN.'='.$SESSION_TOKEN.'">�n�X</a></div>');

P_ADD('<h2>�j�wIP</h2>');
P_ADD('<form action="'.$db_action.DB_ACTION_UPDATE_IP.'" method="post">');
P_ADD('�d�x��}: <input type="text" name="'.POST_FIELD_IP.'" size="20" value="'.STR_TO_INPUT_VALUE($stores[FIELD_STORES_IP]).'"> (�ثe������}: '.$_SERVER['REMOTE_ADDR'].')<br>');
P_ADD('<div class="submit"><input type="submit" value="��s"></div>');
P_ADD('</form>');

P_ADD('<h2>���a���</h2>');
P_ADD('<form action="'.$db_action.DB_ACTION_UPDATE_STORES.'" method="post">');
P_ADD('�۵P�W��: <input type="text" name="'.POST_FIELD_NAME.'" size="32" value="'.STR_TO_INPUT_VALUE($stores[FIELD_STORES_NAME]).'"><br>');
P_ADD('�s���q��: <input type="text" name="'.POST_FIELD_TELEPHONE.'" size="24" value="'.STR_TO_INPUT_VALUE($stores[FIELD_STORES_TELEPHONE]).'"><br>');
P_ADD('��~�a�}: <input type="text" name="'.POST_FIELD_ADDRESS.'" size="52" value="'.STR_TO_INPUT_VALUE($stores[FIELD_STORES_ADDRESS]).'"><br>');
P_ADD('���v�Ǹ�: <input type="text" name="'.POST_FIELD_KEY.'" size="32" value="'.STR_TO_INPUT_VALUE($stores[FIELD_STORES_USB_KEYPRO]).'"> (USB�ꪺ�Ǹ�)<br>');
P_ADD('<div class="submit"><input type="submit" value="��s"></div>');
P_ADD('</form>');

P_ADD('<h2>�ק�K�X</h2>');
P_ADD('<form action="'.$db_action.DB_ACTION_UPDATE_PASSWORD.'" method="post">');
P_ADD('�ª��K�X: <input type="password" name="'.POST_FIELD_PASSWORD_OLD.'" size="20" value=""><br>');
P_ADD('�s���K�X: <input type="password" name="'.POST_FIELD_PASSWORD.'" size="20" value=""><br>');
P_ADD('�T�{�K�X: <input type="password" name="'.POST_FIELD_PASSWORD_2.'" size="20" value=""><br>');
P_ADD('<div class="submit"><input type="submit" value="�ק�"></div>');
P_ADD('</form>');

P_ADD('<h2>�d�߸�Ʈw�γs�u�K�X</h2>');
P_ADD('<form action="'.$db_action.DB_ACTION_DATABASE_PASSWORD.'" method="post">');
P_ADD('�s�u�K�X: <input type="password" name="'.POST_FIELD_PASSWORD.'" size="20" value=""> (�d�իh�ϥιw�]���s�u�K�X)<br>');
P_ADD('<div class="submit"><input type="submit" value="�T�w"></div>');
P_ADD('</form>');

P_ADD('<h2>�b�����</h2>');
P_ADD('<div class="sinfo">���U�Ǹ�: <mark>'.$stores[FIELD_STORES_KEY].'</mark><br>���Ĵ���: <mark>'.$stores[FIELD_STORES_TIMEOUT].'</mark><br><br></div>');

P_ADD('<h2>��������</h2>');
P_ADD('<form action="'.$db_action.DB_ACTION_EXTEND_TIMEOUT.'" method="post">');
P_ADD('�x�ȥd��: <input type="text" name="'.POST_FIELD_CODE.'" size="48" value=""><br>');
P_ADD('<div class="submit"><input type="submit" value="�T�w"></div>');
P_ADD('</form>');

P_ADD('<h2>���z</h2>');
P_ADD('<form action="'.$db_action.DB_ACTION_KILL_SELF.'" method="post">');
P_ADD('�T�{�}��: <input type="checkbox" name="'.POST_FIELD_CONFIRM.'" value="Y"><br>');
P_ADD('<div class="submit"><input type="submit" value="�T�w"></div>');
P_ADD('</form>');

P_PRINT();

?>
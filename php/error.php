<?php

require('func.php');
require('err_msg.php');

P_HEADER('���~', '�����p�U');

P_ADD('<div class="error">'.$ERR_MSG[RET_INT_GET(ARGUMENT_ERROR_CODE)].'<br><br><button class="error" onclick="history.back();">�^�W�@��</button></div>');

P_PRINT();



?>
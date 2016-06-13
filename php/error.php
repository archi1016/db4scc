<?php

require('func.php');
require('err_msg.php');

P_HEADER('錯誤', '說明如下');

P_ADD('<div class="error">'.$ERR_MSG[RET_INT_GET(ARGUMENT_ERROR_CODE)].'<br><br><button class="error" onclick="history.back();">回上一頁</button></div>');

P_PRINT();



?>
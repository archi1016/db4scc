<?php

require('func.php');

CHECK_HAS_LOGON();

session_unset();
session_destroy();

P_TO('index.php');

?>
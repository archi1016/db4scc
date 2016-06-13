<?php

define('ARGUMENT_ERROR_CODE'		,'erc');
define('ARGUMENT_WARNING'		,'wag');
define('ARGUMENT_SESSION_TOKEN'		,'stk');
define('ARGUMENT_SESSION_ACCOUNT'	,'acc');
define('ARGUMENT_SESSION_TIMEOUT'	,'sto');
define('ARGUMENT_DB_ACTION'		,'dac');
define('ARGUMENT_DB_FILE_NAME'		,'dfn');

define('DB_ACTION_UPDATE_STORES'	,'usi');
define('DB_ACTION_UPDATE_IP'		,'uip');
define('DB_ACTION_UPDATE_PASSWORD'	,'upw');
define('DB_ACTION_DATABASE_PASSWORD'	,'dbp');
define('DB_ACTION_DOWNLOAD_DB'		,'ddb');
define('DB_ACTION_DELETE_DB'		,'kdb');
define('DB_ACTION_KILL_SELF'		,'ksf');
define('DB_ACTION_EXTEND_TIMEOUT'	,'ent');
define('DB_ACTION_QUERY_TIMING'		,'tmg');
define('DB_ACTION_QUERY_MAC'		,'mac');
define('DB_ACTION_QUERY_SHIFT'		,'sft');
define('DB_ACTION_QUERY_EVENT'		,'evn');
define('DB_ACTION_QUERY_LOGIN'		,'lgi');
define('DB_ACTION_QUERY_CARD'		,'crd');
define('DB_ACTION_QUERY_QTY'		,'qty');
define('DB_ACTION_QUERY_GOODS'		,'gds');
define('DB_ACTION_QUERY_STOCK'		,'stk');


define('POST_FIELD_ACCOUNT'		,'facc');
define('POST_FIELD_PASSWORD'		,'fpwd');
define('POST_FIELD_ACCOUNT_2'		,'facc2');
define('POST_FIELD_PASSWORD_2'		,'fpwd2');
define('POST_FIELD_CODE'		,'fcode');
define('POST_FIELD_NAME'		,'fname');
define('POST_FIELD_TELEPHONE'		,'ftelephone');
define('POST_FIELD_ADDRESS'		,'faddress');
define('POST_FIELD_IP'			,'fip');
define('POST_FIELD_MDB'			,'fmdb');
define('POST_FIELD_PASSWORD_OLD'	,'fpwdo');
define('POST_FIELD_KEY'			,'fkey');
define('POST_FIELD_SIZE'		,'fsize');
define('POST_FIELD_TIME'		,'ftime');
define('POST_FIELD_START_YEAR'		,'fsyear');
define('POST_FIELD_START_MONTH'		,'fsmonth');
define('POST_FIELD_START_DAY'		,'fsday');
define('POST_FIELD_END_YEAR'		,'feyear');
define('POST_FIELD_END_MONTH'		,'femonth');
define('POST_FIELD_END_DAY'		,'feday');
define('POST_FIELD_CONFIRM'		,'fconfirm');

define('FOLDER_DB_ACCOUNT'		,'/account');
define('FOLDER_DB_IP'			,'/ip');
define('FOLDER_DB_KEY'			,'/key');
define('FOLDER_DB_POINT'		,'/point');

define('FILE_PASSWORD'			,'/password.txt');
define('FILE_NEED_VERIFICATION'		,'/need_verification.txt');
define('FILE_STORES'			,'/stores.tsv');

define('FILE_EXT_MDB'			,'.mdb');
define('FILE_EXT_TXT'			,'.txt');

define('FIELD_STORES_NAME'		,0);
define('FIELD_STORES_TELEPHONE'		,1);
define('FIELD_STORES_ADDRESS'		,2);
define('FIELD_STORES_IP'		,3);
define('FIELD_STORES_MAX_MDBS'		,4);
define('FIELD_STORES_KEY'		,5);
define('FIELD_STORES_MDB_PASSWORD'	,6);
define('FIELD_STORES_TIMEOUT'		,7);
define('FIELD_STORES_USB_KEYPRO'	,8);
define('FIELD_STORES_NU_3'		,9);
define('FIELD_STORES_NU_2'		,10);
define('FIELD_STORES_NU_1'		,11);

define('DAY_TRADE_NO'			,'No');
define('DAY_TRADE_ID'			,'CID');
define('DAY_TRADE_LOGIN_MODE'		,'LoginMode');
define('DAY_TRADE_LOGIN_TIME'		,'Login');
define('DAY_TRADE_LOGOUT_TIME'		,'LogOut');
define('DAY_TRADE_START_MINUTE'		,'StartMin');
define('DAY_TRADE_TOTAL_MINUTES'	,'TotalMinutes');
define('DAY_TRADE_PAY_MINUTES'		,'PayMinutes');
define('DAY_TRADE_MEMBER_ID'		,'MID');
define('DAY_TRADE_PERSON_ID'		,'PersonID');
define('DAY_TRADE_MONEY'		,'Money');
define('DAY_TRADE_CUT_PIECES'		,'CutPieces');
define('DAY_TRADE_FLAGS'		,'Flags');
define('DAY_TRADE_DATE'			,'TradeDate');
define('DAY_TRADE_HANDLE_ID'		,'HandleID');

define('MEMBERS_ID'			,'ID');
define('MEMBERS_MID'			,'MID');

define('CONNECT_STATUS_NO'		,'No');
define('CONNECT_STATUS_ID'		,'CID');
define('CONNECT_STATUS_IP_ADDRESS'	,'IP_address');
define('CONNECT_STATUS_MAC_ADDRESS'	,'Mac_address');

define('SHIFT_TRADE_UID'		,'SLID');
define('SHIFT_TRADE_ACCOUNT'		,'HandleID');
define('SHIFT_TRADE_NAME'		,'Handle');
define('SHIFT_TRADE_START_TIME'		,'ST');
define('SHIFT_TRADE_END_TIME'		,'ED');
define('SHIFT_TRADE_TOTAL_PLAY'		,'PlayTotal');
define('SHIFT_TRADE_TOTAL_MEMBER'	,'MemTotal');
define('SHIFT_TRADE_REFUND_MEMBER'	,'MemRefund');
define('SHIFT_TRADE_TOTAL_STOCK'	,'StockTotal');
define('SHIFT_TRADE_TOTAL_SALE'		,'SaleTotal');
define('SHIFT_TRADE_ACCOUNTS'		,'Accounts');
define('SHIFT_TRADE_INFO'		,'Info');

define('USER_INFO_ID'			,'UserID');
define('USER_INFO_NAME'			,'Name');

define('CARD_TRADE_NO'			,'NO');
define('CARD_TRADE_MEMBER_ID'		,'MID');
define('CARD_TRADE_DATE'		,'ApplyDate');
define('CARD_TRADE_KIND'		,'CardKind');
define('CARD_TRADE_BUY_PIECES'		,'BuyPieces');
define('CARD_TRADE_RPIECES'		,'RPieces');
define('CARD_TRADE_REBATEP'		,'RebateP');
define('CARD_TRADE_MONEY'		,'Money');
define('CARD_TRADE_HANDLE_ID'		,'HandleID');
define('CARD_TRADE_TYPE'		,'Type');

define('GOODS_NO'			,'NO');
define('GOODS_ID'			,'GID');
define('GOODS_NAME'			,'GoodsName');
define('GOODS_INFO'			,'Goodsinfo');
define('GOODS_KIND'			,'Kind');
define('GOODS_PRICE'			,'Price');
define('GOODS_QTY'			,'Qty');
define('GOODS_POINTS'			,'FoodPts');
define('GOODS_RESERVES'			,'Reserves');
define('GOODS_NO_DISCOUNT'		,'NoFoodDisc');
define('GOODS_SALE_STATUS'		,'SaleSts');
define('GOODS_PURCHASE_PRICE'		,'PuPrice');

define('GOODS_TRADE_NO'			,'NO');
define('GOODS_TRADE_IO'			,'IO');
define('GOODS_TRADE_ID'			,'GID');
define('GOODS_TRADE_NAME'		,'Name');
define('GOODS_TRADE_PRICE'		,'Price');
define('GOODS_TRADE_QTY'		,'Qty');
define('GOODS_TRADE_TOTAL'		,'Total');
define('GOODS_TRADE_DATE'		,'VDate');
define('GOODS_TRADE_TARGET'		,'CID');
define('GOODS_TRADE_CHECKED'		,'Checked');
define('GOODS_TRADE_HANDLE_ID'		,'HandleID');
define('GOODS_TRADE_MEMBER_ID'		,'MID');
define('GOODS_TRADE_TOTAL_POINTS'	,'PtsTotal');
define('GOODS_TRADE_TRANS_MODE'		,'TransMode');
define('GOODS_TRADE_STOCK_QTY'		,'StockQty');
define('GOODS_TRADE_RESERVES'		,'Reserves');

define('STOCK_TAKING_NO'		,'NO');
define('STOCK_TAKING_ID'		,'GID');
define('STOCK_TAKING_NAME'		,'Name');
define('STOCK_TAKING_OLD_QTY'		,'OldQty');
define('STOCK_TAKING_NEW_QTY'		,'NewQty');
define('STOCK_TAKING_DATE'		,'SDate');
define('STOCK_TAKING_HANDLE_ID'		,'HandleID');


define('UPLOAD_USER_AGENT'		,'HUA YU CO. DB4SCC');

define('RETURN_CODE_SUCCESS'		,'SUCCESS');
define('RETURN_CODE_UNKNOW_IP'		,'UNKNOW_IP');
define('RETURN_CODE_UNKNOW_ACCOUNT'	,'UNKNOW_ACCOUNT');
define('RETURN_CODE_UPLOAD_FAILURE'	,'UPLOAD_FAILURE');
define('RETURN_CODE_NOT_MDB'		,'NOT_MDB');
define('RETURN_CODE_MOVE_FAILURE'	,'MOVE_FAILURE');
define('RETURN_CODE_ERROR_KEY'		,'ERROR_KEY');
define('RETURN_CODE_TIMEOUT'		,'TIMEOUT');
define('RETURN_CODE_NEED_VERIFICATION'	,'NEED_VERIFICATION');

?>
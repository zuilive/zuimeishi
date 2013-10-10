<?php
$arr1 = array(

    //'SHOW_PAGE_TRACE'=>1,//显示调试信息

    'DB_TYPE' => 'mysql',	//数据库类型
	'DB_HOST' => '127.0.0.1',	//服务器地址
	'DB_NAME' => 'meishimeike',	//数据库名
	'DB_USER' => 'root',	//数据库用户名
	'DB_PWD' => '',		//数据库密码
	'DB_PORT' => 3306,		//端口
	'DB_PREFIX' => 'zui_', //数据库表前缀
	'TMPL_STRIP_SPACE' => 'true',
	'URL_CASE_INSENSITIVE' =>true,	//忽略大小写
	//更改模板引擎的左右标签
	'TMPL_L_DELIM'=>'<{',
	'TMPL_R_DELIM'=>'}>',

	'ERROR_PAGE'=>'/404.html',	//错误跳转页面

	'DEFAULT_FILTER' => 'strip_tags,htmlspecialchars',
	'VAR_FILTERS'=>'filter_default,filter_exp',
	);
?>
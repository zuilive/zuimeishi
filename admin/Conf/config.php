<?php
include 'config.inc.php';
$arr2 =  array(
	'USER_AUTH_ON'=>true,
	'USER_AUTH_TYPE'			=>1,		// 默认认证类型 1 登录认证 2 实时认证
	'USER_AUTH_KEY'			=>'zuilivecms',	// 用户认证SESSION标记
	'ADMIN_AUTH_KEY'			=>'administrator',
	'USER_AUTH_MODEL'		=>'User',	// 默认验证数据表模型
	'AUTH_PWD_ENCODER'		=>'md5',	// 用户认证密码加密方式
	'USER_AUTH_GATEWAY'	=>'/Public/login',	// 默认认证网关
	'NOT_AUTH_MODULE'		=>'Public',		// 默认无需认证模块
	'REQUIRE_AUTH_MODULE'=>'',		// 默认需要认证模块
	'NOT_AUTH_ACTION'		=>'',		// 默认无需认证操作
	'REQUIRE_AUTH_ACTION'=>'',		// 默认需要认证操作
	'GUEST_AUTH_ON'          => false,    // 是否开启游客授权访问
	'GUEST_AUTH_ID'           =>    0,     // 游客的用户ID
	'RBAC_ROLE_TABLE'=>'zui_role',
	'RBAC_USER_TABLE'	=>	'zui_role_user',
	'RBAC_ACCESS_TABLE' =>	'zui_access',
	'RBAC_NODE_TABLE'	=> 'zui_node',
	'USER_AUTH_KEY'=>'zui_uid',	//用户session标识设置
	'USER_NAME'=>'zui_uname',	//用户名session标识设置
	//'TOKEN_ON'=>true,  // 是否开启令牌验证
	//'TOKEN_NAME'=>'__zuihash__',    // 令牌验证的表单隐藏字段名称
	//'TOKEN_TYPE'=>'md5',  //令牌哈希验证规则 默认为MD5
	//'TOKEN_RESET'=>true,  //令牌验证出错后是否重置令牌 默认为true
	
	'DB_BACKUP'=>'Dbdata/',	//数据库配置信息
	'DB_BACKUP_SIZE'=>'3000000',	

	'FILE_UPLOAD'=>'Upload',	//图片上传位置

	'TMPL_PARSE_STRING'	=>	array(
		'__TEMPLATE__' => __ROOT__.'/admin/Tpl',
		),
);
return array_merge($arr2,$arr1);

?>
<?php
/*
 * Created by ZuiLive
 * E-mail: zuihuanxiang@gmail.com
 * Blog: http://zuilive.org
 * CreateTime: 2013-7-9
 * 后台入口
 */
  	//在相关目录下生成空的htm文件
	//define('BUILD_DIR_SECURE',true);

	//开启debug模式
    define('APP_DEBUG', true);

    //定义项目名称
    define('APP_NAME', 'Admin');
    //定义项目路径
    define('APP_PATH', './admin/');
    //设置便于缓存目录
    define('RUNTIME_PATH','./cache/admin/');
    //加载框架入文件
    require './Core/core.php';
?>
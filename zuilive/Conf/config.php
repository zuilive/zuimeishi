<?php
include 'config.inc.php';
$arr2 =  array(

	'FILE_UPLOAD'=>'Upload',	//图片上传位置
	'URL_MODEL'=>2,
	'COOKIE_PREFIX'=>'meishimeike_',	//cookie 前缀

	'TMPL_PARSE_STRING'	=>	array(
		'__TEMPLATE__' => __ROOT__.'/zuilive/Tpl',
	),
);
return array_merge($arr2,$arr1);
?>
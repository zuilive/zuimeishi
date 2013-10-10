<?php 
/*
 * 用户模型 
 * @create	2013-07-15
 * @author	zuihuanxiang@gamil.com
 * @last edit	2013-07-15
 */
class UserModel extends Model{
	protected $_validate = array(	//自动验证
		array('username','require','用户名必须填写！'),	//验证用户名
		array('username','','用户名已经存在！',0,'unique',1),	//新增时验证username字段是否唯一
		array('repassword','password','确认密码不正确',0,'confirm'),	//验证密码和密码是否一致
		array('password','require','密码必须填写！'),	//密码必须
		array('email','require','邮箱必须填写！'),	//邮箱必须填写
		array('email','email','邮箱格式不正确！'),
		);
	
	protected $_auto = array(	//自动完成
			array('create_time','time',1,'function'),	//添加用户时写入添加时间
			array('update_time','time',3,'function'),	//更新、添加文章时写入时间
			array('password','md5',3,'function'),	//对password字段使用MD5函数
		);
	
	
}

?>
<?php 
/*
 * 会员模型 
 * @create	2013-08-07
 * @author	zuihuanxiang@gamil.com
 * 
 */
class MemberModel extends Model{
	protected $_validate = array(	//自动验证
		array('username','require','用户名必须填写！'),	//验证用户名
		array('phone','require','电话必须填写！'),	//电话必须填写
		array('phone','number','电话格式不正确！'),	//邮箱必须填写
		array('username','','用户名已经存在！',0,'unique',1),	//新增时验证username字段是否唯一
		array('repassword','password','确认密码不正确',0,'confirm'),	//验证密码和密码是否一致
		array('password','require','密码必须填写！'),	//密码必须
		array('email','require','邮箱必须填写！'),	//邮箱必须填写
		array('email','email','邮箱格式不正确！'),
		);
	
	protected $_auto = array(	//自动完成
			array('regdate','time',1,'function'),	//添加用户时写入注册时间
			array('update_time','time',3,'function'),	//更新、添加文章时写入时间
			array('password','membermd5',3,'callback'),	//对password字段使用memberMD5函数
		);

	protected function membermd5(){	//使用随机代码和密码生成密钥
		$pass = I('post.password');
		$encrypt = I('post.encrypt');

		return md5(md5($pass).md5($encrypt));
	}
	
	
}

?>
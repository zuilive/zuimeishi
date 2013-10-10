<?php
/**
* 会员中心、会员登录
* @create 2013-10-08
* @author zuihuanxiang@gmail.com
*/
class UserAction extends CommonAction{
	function _initialize(){	//初始化操作
		parent::_initialize();

	}

	//会员中心欢迎界面动作
	public function index(){
		$this->checkLogin();
		$this->display();
	}

	//会员登录
	public function login(){
		if (IS_POST) {
			$uname = I('post.uname');
			$pass = I('post.pass');

			$map['username'] = array('eq',$uname);
			$uinfo = M('Member')->where($map)->find();	//获取表中的用户信息

			if(md5(md5($pass).md5($uinfo['encrypt'])) == $uinfo['password']){
				cookie('uname',$uname,array('expire'=>'604800'));
				cookie('pass',$uinfo['password'],array('expire'=>'604800'));
				$this->success('登录成功！','http://www.zuilive.org');
			}else{
				$this->error('账号或密码错误！');
			}
		}else{
			$this->display();
		}
	}

	//判断是否登录
	public function checkLogin(){
		if (!$this->isLogin) {
			$this->error('你还没有登陆！请先登录！');
		}
	}

	//会员退出
	public function logout(){
		cookie(null);
		$this->success('退出成功',__APP__);
	}


	//用户注册
	public function register(){
		if (IS_POST) {
			$Member = D('Member');
			if ($Member->create()) {
				if ($Member->add()) {
					$this->success('注册成功！');
				}else{
					$this->error('注册失败！');
				}
			}else{
				$this->error($Member->getError());
			}
		}else{
			$encrypt = genRandomString(10);	//获取随机码
			$this->assign('encrypt',$encrypt)->display();
		}
	}
}
?>
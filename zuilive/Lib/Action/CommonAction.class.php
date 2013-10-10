<?php
//初始接口
class CommonAction extends Action{
	function _initialize() {
		//获取导航
		$menu = M('Nav')->order('id')->select();
		$this->assign('menu',$menu);

		//获取cookie,检测是否登陆
		$uname = cookie('uname');
		$pass = cookie('pass');

		$map['username'] = array('eq',$uname);
		$uinfo = M('Member')->where($map)->find();	//获取表中的用户信息
		if ($uname && $pass) {
			if($pass == $uinfo['password']){
				$this->assign('uinfo',$uinfo)->assign('isLogin',1);
			}
		}
		else{
			$this->assign('isLogin',0);
			$isLogin = 0;
		}
	}

}
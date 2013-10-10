<?php
//初使接口
class CommonAction extends Action{
		function _initialize() {
		// 用户权限检查
		if (C ( 'USER_AUTH_ON' ) && !in_array(MODULE_NAME,explode(',',C('NOT_AUTH_MODULE')))) {
			import ( 'ORG.Util.RBAC' );
			if (! RBAC::AccessDecision ()) {
				//检查认证识别号
				if (! $_SESSION [C ( 'USER_AUTH_KEY' )]) {
					//跳转到认证网关
					redirect ( PHP_FILE . C ( 'USER_AUTH_GATEWAY' ) );
				}
				// 没有权限 抛出错误
				if (C ( 'RBAC_ERROR_PAGE' )) {
					// 定义权限错误页面
					redirect ( C ( 'RBAC_ERROR_PAGE' ) );
				} else {
					if (C ( 'GUEST_AUTH_ON' )) {
						$this->assign ( 'jumpUrl', PHP_FILE . C ( 'USER_AUTH_GATEWAY' ) );
					}
					// 提示错误信息
					$this->error ( L ( '_VALID_ACCESS_' ) );
				}
			}
		}
		//获取网站信息
		$Site = M('Config');
		$siteinfo = $Site->where('groupid = 1')->select();
		$this->assign('siteinfo',$siteinfo);
		//获取所有节点
		$Node = M('Node')->where('status = 1')->select();
		$map['status'] = array('eq',1);
		$map['type'] = array('eq',1);
		//获取菜单信息
		$Menu = M('Menu')->where($map)->order('id')->select();

		$this->assign('mainMenu',$Menu);	
	}
}

?>
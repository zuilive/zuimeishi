<?php
/**
* 店铺详细页面
* @create 2013-10-09
* @author http://www.zuilive.org
*/
class ShopAction extends CommonAction {
	function _initialize(){	//初始化操作
		parent::_initialize();
	}

	//店铺显示
	public function index(){
		$id = I('get.id');
		$shopInfo = M('Shop')->where('id = '.$id)->find();
		if ($shopInfo['status']) {
			$this->assign('shopInfo',$shopInfo)->display();
		}else{
			$this->error('非法输入');
		}
		
	}
}
?>
<?php
/**
* 美食每刻首页
* @create 2013-10-09
* @author zuihuanxiang@gmail.com
*
*/

class IndexAction extends CommonAction {
	function _initialize(){	//初始化操作
		parent::_initialize();
	}
	
	public function index(){

		//店铺列表
		$map['status'] = array('eq',1);
		$shopList = M('Shop')->where($map)->order('display desc')->select();
		$this->assign('shopList',$shopList);

		$this->display();
    	}
}
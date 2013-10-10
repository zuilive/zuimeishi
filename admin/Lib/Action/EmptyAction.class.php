<?php
/**
*	404页面控制器
*/
class EmptyAction extends Action{
	function _initialize(){
		//获取网站信息
		$Site = M('Config');
		$siteinfo = $Site->select();
		$this->assign('siteinfo',$siteinfo);
	}

    function _empty(){
        header("HTTP/1.0 404 Not Found");//使HTTP返回404状态码
        $this->display("Public:404");
    }
    
    function index() {
        header("HTTP/1.0 404 Not Found");
        $this->display('Public:404');
    }
}
?>
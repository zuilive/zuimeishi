<?php
/*
 * 会员组控制器
 * @create	2013-08-07
 * @author	zuihuanxiang@gmail.com
 * 
 */
class MembergroupAction extends CommonAction{

	//会员组显示动作
	public function index(){
		$this->display();
	}

	//空操作
	public function _empty(){
        header("HTTP/1.0 404 Not Found");//使HTTP返回404状态码
        $this->display("Public:404");
    }
}

?>
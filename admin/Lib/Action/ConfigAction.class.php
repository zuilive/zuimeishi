<?php
/*
 * 设置控制器
 * @create	2013-07-12
 * @author	zuihuanxiang@gmail.com
 * @last edit	2013-07-12
 */
class ConfigAction extends CommonAction{

	protected $Config;
	
	function _initialize(){
        parent::_initialize();
		$this->Config = D('Config');
	}

    //站点设置
    public function index(){
        if (IS_POST) {
             $this->dosite();
        }else{
            $list = $this->Config->where('groupid = 1')->select();
          //  dump($list);
            $this->assign('list',$list)->display();
        }
    }

    //邮箱设置
    public function email(){
        if (IS_POST) {
            $this->dosite();
        }else{
            $list = $this->Config->where('groupid = 2')->select();//dump($list);
            $this->assign('list',$list)->display();
        }
    }

	//更新配置
    protected function dosite() {
        foreach ($_POST as $key => $value) {
            $data["value"] = trim($value);
            $this->Config->where(array("varname" => $key))->save($data);
        }
        $status = array('statusCode'=>200,'message'=>'更新成功！','navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
        echo json_encode($status);
    }

    function _empty(){
        header("HTTP/1.0 404 Not Found");//使HTTP返回404状态码
        $this->display("Public:404");
    }


}




?>
<?php
/*
 * 前台导航控制器
 * @create	2013-07-23
 * @author	zuihuanxiang@gmail.com
 * 
 */
class NavAction extends CommonAction{
	protected $Nav;
	
	function _initialize(){
		parent::_initialize();
		$this->Nav = D('Nav');
	}

	//导航显示动作
	public function index(){
		$list = $this->Nav->select();

		$this->assign('list',$list)->display();
	}

	//添加导航动作
	public function add(){
		if (IS_POST) {
			if ($this->Nav->create()) {
				if ($this->Nav->add()) {
					$status = array('statusCode'=>200,'message'=>'添加成功！','navTabId'=>'','rel'=>'Nav_add','callbackType'=>'closeCurrent','forwardUrl'=>'');
					echo json_encode($status);
				}else{
					$status = array('statusCode'=>300,'message'=>'添加失败！','navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
					echo json_encode($status);
				}
			}else{
				$status = array('statusCode'=>300,'message'=>$this->Nav->getError(),'navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
				echo json_encode($status);
			}
		}else{
			$this->display();
		}
	}

	//编辑导航动作
	public function edit(){
		if (IS_POST) {
			$id = I('post.id',0);
			if ($this->Nav->create()) {
				if (false !== $this->Nav->where('id='.$id)->save()) {
					$status = array('statusCode'=>200,'message'=>'编辑成功！','navTabId'=>'','rel'=>'Nav_edit','callbackType'=>'closeCurrent','forwardUrl'=>'');
					echo json_encode($status);
				}else{
					$status = array('statusCode'=>300,'message'=>'编辑失败！','navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
					echo json_encode($status);
				}
			}else{
				$status = array('statusCode'=>300,'message'=>$this->Nav->getError(),'navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
				echo json_encode($status);
			}
		}else{
			$id = I('get.id');
			$info = $this->Nav->where('id='.$id)->find();
			$this->assign('info',$info)->display();
		}
	}

	//删除导航动作
	public function del(){

		if (IS_POST) {
			$id = I('post.id');
			if (is_array($id)) {
				foreach ($id as $key => $value) {
					$this->Nav->delete($value);
				}
			}else{
				$id = I('get.id');
				$this->Nav->delete($id);
			}

			$status = array('statusCode'=>200,'message'=>'删除成功！','navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
			echo json_encode($status);
		}
	}

	function _empty(){
        header("HTTP/1.0 404 Not Found");//使HTTP返回404状态码
        $this->display("Public:404");
    }

}
?>
<?php
/*
 * 友情链接控制器
 * @create	2013-07-22
 * @author	zuihuanxiang@gmail.com
 * @last edit	2013-07-22
 */
class LinksAction extends CommonAction{
	protected $Links;
	
	function _initialize(){
		parent::_initialize();
		$this->Links = D('Links');
	}

	public function index(){
		$links = $this->Links->select();
		$this->assign('links',$links)->display();
	}

	public function add(){
		if (IS_POST) {
			if ($this->Links->create()) {
				if ($this->Links->add()) {
					$status = array('statusCode'=>200,'message'=>'添加成功！','navTabId'=>'','rel'=>'Links_add','callbackType'=>'closeCurrent','forwardUrl'=>'');
					echo json_encode($status);
				}else{
					$status = array('statusCode'=>300,'message'=>'编辑失败！','navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
					echo json_encode($status);
				}
			}else{
				$status = array('statusCode'=>300,'message'=>$this->Links->getError(),'navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
				echo json_encode($status);
			}
		}else{
			$this->display();
		}
	}

	public function edit(){
		if (IS_POST) {
			$id = I('post.id');
			if ($this->Links->create()) {
				if (false !== $this->Links->where('id='.$id)->save()) {
					$status = array('statusCode'=>200,'message'=>'编辑成功！','navTabId'=>'','rel'=>'Links_edit','callbackType'=>'closeCurrent','forwardUrl'=>'');
					echo json_encode($status);
				}else{
					$status = array('statusCode'=>300,'message'=>'编辑失败！','navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
					echo json_encode($status);
				}
			}else{
				
				$status = array('statusCode'=>300,'message'=>$this->Links->getError(),'navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
				echo json_encode($status);
			}
		}else{
			$id = I('get.id',0);
			$info = $this->Links->where('id='.$id)->find();
			$this->assign('info',$info)->display();
		}
	}

	public function del(){
		if (IS_POST) {
			$id = I('post.id');
			if (is_array($id)) {
				foreach ($id as $key => $value) {
					$this->Links->delete($value);
				}
			}else{
				$id = I('get.id');
				$this->Links->delete($id);
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
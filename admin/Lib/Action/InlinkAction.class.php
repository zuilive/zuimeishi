<?php
/*
*	内链控制器
*	@author zuihuanxiang@gmail.com
*	@create 2013-9-9
*/
class InlinkAction extends CommonAction{
	protected $Inlink;
	
	function _initialize(){
		$this->Inlink = M('Inlink');
	}

	public function index(){
		//获取分页信息
		$p = I('post.pageNum',1);	//页码
		$numPerPage = I('post.numPerPage',15);	//每页显示条数
		$keywords = I('post.keywords','');	//获取关键词

		$map['name'] = array('like','%'.$keywords.'%');
		$count = $this->Inlink->where($map)->count();	//查询满足条件的数量

		$inlinklist = $this->Inlink->where($map)->order('id desc')->limit(($p-1)*$numPerPage,$numPerPage)->select();

		$this->assign('keywords',$keywords)->assign('p',$p)->assign('numPerPage',$numPerPage)->assign('count',$count)->assign('inlinklist',$inlinklist)->display();

	}
	//内链添加动作
	public function add(){
		if (IS_POST){
			if ($this->Inlink->create()) {	//验证信息
				if ($id = $this->Inlink->add()) {
					$status = array('statusCode'=>200,'message'=>'内链添加成功！','navTabId'=>'','rel'=>'Inlink_add','callbackType'=>'closeCurrent','forwardUrl'=>'');
					echo json_encode($status);
				}else{
					$status = array('statusCode'=>300,'message'=>'内链添加失败！','navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
					echo json_encode($status);
				}
			}else{
				$status = array('statusCode'=>300,'message'=>$this->Inlink->getError(),'navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
				echo json_encode($status);
			}
			
		}else{
			$this->display();
		}
	}
	//内链编辑动作
	public function edit(){
		if (IS_POST) {
			$id = I('post.id');
			if ($this->Inlink->create()) {
				if (false !== $this->Inlik->where('id = '.$id)->save()) {
					$status = array('statusCode'=>200,'message'=>'内链编辑成功！','navTabId'=>'','rel'=>'Article_edit','callbackType'=>'closeCurrent','forwardUrl'=>'');
					echo json_encode($status);
				}else{
					$status = array('statusCode'=>300,'message'=>'内链编辑失败！','navTabId'=>'','rel'=>'Article_edit','callbackType'=>'closeCurrent','forwardUrl'=>'');
					echo json_encode($status);
				}
			}else{
				$status = array('statusCode'=>300,'message'=>$this->Inlink->getError(),'navTabId'=>'','rel'=>'Article_edit','callbackType'=>'closeCurrent','forwardUrl'=>'');
				echo json_encode($status);
			}
		}else{
			$id = I('get.id');
			$inlinkinfo = $this->Inlink->where('id = '.$id)->find();
			$this->assign('inlinkinfo',$inlinkinfo);
		}
	}

	//内链删除动作
	public function del(){
		if (IS_POST) {
			$id = I('post.id');
			if (is_array($id)) {
				foreach ($id as $key => $value) {
					$this->Inlink->delete($value);
				}
			}else{
				$id = I('get.id');
				$this->Inlink->delete($id);
			}

			$status = array('statusCode'=>200,'message'=>'删除成功！','navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
			echo json_encode($status);
		}
	}
}
<?php
/*
 * 管理员设置控制器
 */
class UserAction extends CommonAction{
	protected $User;
	
	function _initialize(){
		parent::_initialize();
		$this->User = D('User');
		//import('ORG.Util.Page');	//导入分页类
	}
	//管理员列表显示动作
	function index() {
		//获取分页信息
		$p = I('post.pageNum',1);	//页码
		$numPerPage = I('post.numPerPage',15);	//每页显示条数
		$keywords = I('post.keywords','');	//获取关键词

		$map['username'] = array('like','%'.$keywords.'%');
		
		$count = $this->User->where($map)->count();
		$list = $this->User->where($map)->order('id desc')->limit(($p-1)*$numPerPage,$numPerPage)->select();

		$this->assign('keywords',$keywords)->assign('p',$p)->assign('numPerPage',$numPerPage)->assign('count',$count)->assign('list',$list)->display();
	}
	
	//添加管理员动作
	function add() {
		if (IS_POST) {	//添加数据处理
			
			if ($this->User->create()) {
				
				if ($this->User->add()) {
					//向role_user中插入记录
					$uid=$this->User->getLastInsID();
					$ru['role_id']=$_POST['role_id'];
					$ru['user_id']=$uid;
					$roleuser=M('RoleUser');

					if ($roleuser->add($ru)){
						$status = array('statusCode'=>200,'message'=>'添加成功！','navTabId'=>'','rel'=>'User_add','callbackType'=>'closeCurrent','forwardUrl'=>'');
						echo json_encode($status);
					}
				}else {
					$status = array('statusCode'=>300,'message'=>'添加失败！','navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
					echo json_encode($status);
				}
			}else {
				
				$status = array('statusCode'=>300,'message'=>$this->User->getError(),'navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
				echo json_encode($status);
			}
		}else {	//添加页面显示
			$Role = M('Role');
			$list = $Role->select();

			$this->assign('list',$list);
			$this->display();
		}
	}
	
	//编辑管理员动作
	function edit() {
		
		if (IS_POST) {
			$id = I('post.id');
			if ($this->User->create()) {
				if (false !== $this->User->where("id=$id")->save()) {
					//更新role_user表
					$ru['role_id']=$_POST['role_id'];
					$roleuser=M('RoleUser');

					$roleuser->where('user_id='.$id)->save($ru);
					
					$status = array('statusCode'=>200,'message'=>'编辑成功！','navTabId'=>'','rel'=>'User_edit','callbackType'=>'closeCurrent','forwardUrl'=>'');
					echo json_encode($status);
				}else{
					$status = array('statusCode'=>300,'message'=>'编辑失败！','navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
					echo json_encode($status);
				}
			}else {
				
				$status = array('statusCode'=>300,'message'=>$this->User->getError(),'navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
				echo json_encode($status);
			}
		}else {
			$id = I('get.id');
			$Role = M('Role');
			$rlist = $Role->select();
			
			$uinfo = $this->User->where("id=$id")->find();
			
			$this->assign('rlist',$rlist);
			$this->assign('uinfo',$uinfo);
			$this->display();
		}
	}
	
	//删除管理员动作
	public function del() {
		$Roleuser = M('RoleUser');
		if (IS_POST) {
			$id = I('post.id');
			if (is_array($id)) {
				foreach ($id as $key => $value) {
					$this->User->delete($value);
					$Roleuser->where('user_id = '.$value)->delete();
				}
			}else{
				$id = I('get.id');
				$this->User->delete($id);
				$Roleuser->where('user_id = '.$id)->delete();
			}
			$status = array('statusCode'=>200,'message'=>'用户删除成功！','navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
			echo json_encode($status);
		}

	}

	function _empty(){
        header("HTTP/1.0 404 Not Found");//使HTTP返回404状态码
        $this->display("Public:404");
    }
	
}
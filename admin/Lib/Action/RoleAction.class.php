<?php
/*
 * 管理员角色控制器
 */
class RoleAction extends CommonAction{
	protected $Role;
	
	function _initialize(){
		parent::_initialize();
		$this->Role = D('Role');
	}

	//角色显示动作
	public function index() {
		//获取分页信息
		$p = I('post.pageNum',1);	//页码
		$numPerPage = I('post.numPerPage',15);	//每页显示条数
		$keywords = I('post.keywords','');	//获取关键词

		$map['name'] = array('like','%'.$keywords.'%');
		
		$count = $this->Role->where($map)->count();
		$list = $this->Role->where($map)->order('id desc')->limit(($p-1)*$numPerPage,$numPerPage)->select();

		$this->assign('keywords',$keywords)->assign('p',$p)->assign('numPerPage',$numPerPage)->assign('count',$count)->assign('list',$list)->display();
	}
	
	//增加角色动作
	public function add() {
		if(IS_POST){	//添加纪录
			if ($this->Role->create()) {
				if ($this->Role->add()) {
					
					$status = array('statusCode'=>200,'message'=>'添加角色成功！','navTabId'=>'','rel'=>'Role_add','callbackType'=>'closeCurrent','forwardUrl'=>'');
					echo json_encode($status);
				}else {
					
					$status = array('statusCode'=>200,'message'=>'添加角色失败！','navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
					echo json_encode($status);
				}
			}else {
				
				$status = array('statusCode'=>200,'message'=>$this->Role->getError(),'navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
				echo json_encode($status);
			}
		}else{	//显示增加页面
			$list = $this->Role->select();

			$this->assign('list',$list)->display();
		}
	}
	
	//编辑角色动作
	public function edit(){
	
		if(IS_POST){
			$id = I('post.id');
			if ($this->Role->create()) {
				if (false !== $this->Role->where("id=".$id)->save()) {
					$status = array('statusCode'=>200,'message'=>'编辑成功！','navTabId'=>'','rel'=>'Role_edit','callbackType'=>'closeCurrent','forwardUrl'=>'');
					echo json_encode($status);
				}else{
					$status = array('statusCode'=>300,'message'=>'编辑失败！','navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
					echo json_encode($status);
				}
			}else {
				$status = array('statusCode'=>300,'message'=>$this->Role->getError(),'navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
				echo json_encode($status);
			}
			
		}else {
			$id = I('get.id');
			$rinfo = $this->Role->where("id=".$id)->find();
			
			$this->assign('rinfo',$rinfo);
			$list = $this->Role->where('status=1')->select();
			$this->assign('list',$list);
			$this->display();
		}
	}
	
	//删除角色动作
	public function del(){
		if (IS_POST) {
			$id = I('post.id');
			if (is_array($id)) {
				foreach ($id as $key => $value) {
					$this->Role->delete($value);
				}
			}else{
				$id = I('get.id');
				$this->Role->delete($id);
			}
			$status = array('statusCode'=>200,'message'=>'删除成功！','navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
			echo json_encode($status);
		}

	}

	//添加操作节点
	public function addnode(){
		$Node = M('Node');
		if (IS_POST) {
			$Node->create();
			if ($Node->add()){
				$status = array('statusCode'=>200,'message'=>'添加成功！','navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
				echo json_encode($status);
			}else{
				$status = array('statusCode'=>300,'message'=>'添加失败！','navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
				echo json_encode($status);
			}
		}else{
			
			$list = $Node->where('level != 3')->select();
			$this->assign('list',$list);
			$this->display();
		}
	}

	//授权
	public function shouquan(){
		$Access = M('Access');
		$Node = M('Node');
		if (IS_POST) {
			$id = I('post.id',0);
			if ($id < 1) {
				$status = array('statusCode'=>300,'message'=>'非法操作！','navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
				echo json_encode($status);
			}
			$accessid = I('post.accessid',0);
			$accessid_array = explode(',', $accessid);	//截取字符串成数组
			//dump($accessid_array);
			//删除所有的权限
			$Access->where('role_id='.$id)->delete();
			$data['role_id'] = $id;
			//添加权限
			for ($i=0; $i < count($accessid_array)-1; $i++) { 
				$acc = $Node->where('id='.$accessid_array["$i"])->find();
				$data['node_id'] = $accessid_array["$i"];
				$data['level'] = $acc['level'];
				$data['pid'] = $acc['pid'];
				$Access->add($data);
			}
			$status = array('statusCode'=>200,'message'=>'授权成功！','navTabId'=>'','rel'=>'Role_shouquan','callbackType'=>'closeCurrent','forwardUrl'=>'');
			echo json_encode($status);
			
		}else{
			$id = I('get.id',0);
			if ($id < 1) {
				
				$status = array('statusCode'=>200,'message'=>'非法操作！','navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
				echo json_encode($status);
			}
			//已有权限
			$list = $Access->where('role_id ='.$id)->select();

			//节点
			$nodelist = $Node->where('status=1')->select();
			//dump($nodelist);
			$this->assign('nodelist',$nodelist)->assign('list',$list)->display();
		}
	}
	

	function _empty(){
        header("HTTP/1.0 404 Not Found");//使HTTP返回404状态码
        $this->display("Public:404");
    }

}
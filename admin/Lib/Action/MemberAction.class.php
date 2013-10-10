<?php
/*
 * 会员控制器
 * @create	2013-08-07
 * @author	zuihuanxiang@gmail.com
 * 
 */
class MemberAction extends CommonAction{
	protected $Member;
	
	function _initialize(){
		parent::_initialize();
		$this->Member = D('Member');
	}

	//会员显示动作
	public function index(){
		//获取分页信息
		$p = I('post.pageNum',1);	//页码
		$numPerPage = I('post.numPerPage',15);	//每页显示条数
		$keywords = I('post.keywords','');	//获取关键词

		$map['islock'] = array('eq',0);
		$map['checked'] = array('eq',1);
		$map['username'] = array('like','%'.$keywords.'%');
		$count = $this->Member->where($map)->count();	//查询满足条件的数量
		$memberlist = $this->Member->where($map)->limit(($p-1)*$numPerPage,$numPerPage)->select();

		$this->assign('keywords',$keywords)->assign('p',$p)->assign('numPerPage',$numPerPage)->assign('count',$count)->assign('list',$memberlist)->display();

	}

	//会员添加动作
	public function add(){
		if (IS_POST) {
			if ($this->Member->create()) {
				if ($this->Member->add()) {
					$status = array('statusCode'=>200,'message'=>'会员添加成功！','navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
					echo json_encode($status);
				}else{
					$status = array('statusCode'=>300,'message'=>'会员添加失败！','navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
					echo json_encode($status);
				}
			}else{
				$status = array('statusCode'=>300,'message'=>$this->Member->getError(),'navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
				echo json_encode($status);
			}
		}else{
			$mgroup = M('MemberGroup')->where('disabled = 0')->select();	//获取未禁用的用户组
			$encrypt = genRandomString(10);	//获取随机码
			$this->assign('encrypt',$encrypt)->assign('mgroup',$mgroup)->display();
		}
	}

	//会员修改
	public function edit(){
		if (IS_POST) {
			
		}else{
			$mgroup = M('MemberGroup')->where('disabled = 0')->select();	//获取未禁用的用户组
			$encrypt = genRandomString(10);	//获取随机码
			$this->assign('encrypt',$encrypt)->assign('mgroup',$mgroup)->display();
		}
	}

	//审核会员列表
	public function checklist(){
		//获取分页信息
		$p = I('post.pageNum',1);	//页码
		$numPerPage = I('post.numPerPage',15);	//每页显示条数
		$keywords = I('post.keywords','');	//获取关键词

		$map['username'] = array('like','%'.$keywords.'%');
		$map['checked'] = array('eq','0');

		$count = $this->Member->where($map)->count();	//查询满足条件的数量
		$list = $this->Member->where($map)->limit(($p-1)*$numPerPage,$numPerPage)->select();

		$this->assign('keywords',$keywords)->assign('p',$p)->assign('numPerPage',$numPerPage)->assign('count',$count)->assign('list',$list)->display();
	}

	//会员审核动作
	public function checked(){
		
		if (IS_POST) {
			$id = I('post.id');
			$data['checked'] = 1;
			if (is_array($id)) {
				foreach ($id as $key => $value) {
					$this->Member->where('userid = '.$value)->save($data);
				}
			}else{
				$id = I('get.id');
				$this->Member->where('userid = '.$id)->save($data);
			}
			$status = array('statusCode'=>200,'message'=>'审核成功！','navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
			echo json_encode($status);
		}
	}

	//会员锁定列表
	public function locklist(){
		//获取分页信息
		$p = I('post.pageNum',1);	//页码
		$numPerPage = I('post.numPerPage',15);	//每页显示条数
		$keywords = I('post.keywords','');	//获取关键词

		$map['username'] = array('like','%'.$keywords.'%');
		$map['islock'] = array('eq','1');

		$count = $this->Member->where($map)->count();	//查询满足条件的数量
		$list = $this->Member->where($map)->limit(($p-1)*$numPerPage,$numPerPage)->select();

		$this->assign('keywords',$keywords)->assign('p',$p)->assign('numPerPage',$numPerPage)->assign('count',$count)->assign('list',$list)->display();	
	}


	//会员锁定动作
	public function lock(){
		if (IS_POST) {
			$id = I('post.id');
			$data['islock'] = 1;
			if (is_array($id)) {
				foreach ($id as $key => $value) {
					$this->Member->where('userid = '.$value)->save($data);
				}
			}else{
				$id = I('get.id');
				$this->Member->where('userid = '.$id)->save($data);
			}
			$status = array('statusCode'=>200,'message'=>'锁定成功！','navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
			echo json_encode($status);
		}
	}

	//会员解锁动作
	public function unlock(){
		if (IS_POST) {
			$id = I('post.id');
			$data['islock'] = 0;
			if (is_array($id)) {
				foreach ($id as $key => $value) {
					$this->Member->where('userid = '.$value)->save($data);
				}
			}else{
				$id = I('get.id');
				$this->Member->where('userid = '.$id)->save($data);
			}
			$status = array('statusCode'=>200,'message'=>'解锁成功！','navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
			echo json_encode($status);
		}
	}

	//空操作
	public function _empty(){
        header("HTTP/1.0 404 Not Found");//使HTTP返回404状态码
        $this->display("Public:404");
    }
}

?>
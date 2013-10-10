<?php
/*
 * 页面控制器
 * @create	2013-07-01
 * @author	zuihuanxiang@gmail.com
 * @last edit	2013-07-11
 */
class PageAction extends CommonAction{
	protected $Article;
	
	function _initialize(){
		parent::_initialize();
		import('ORG.Util.Page');	//导入分页类
		$this->Article = D('Article');
	}
	
	//页面管理动作
	public function index(){
		//获取分页信息
		$p = I('post.pageNum',1);	//页码
		$numPerPage = I('post.numPerPage',15);	//每页显示条数
		$keywords = I('post.keywords','');	//获取关键词

		$map['title'] = array('like','%'.$keywords.'%');
		$map['status'] = array('eq','1');
		$map['type'] = array('like','%page%');
		$count = $this->Article->where($map)->count();	//查询满足条件的数量
		$articlelist = $this->Article->where($map)->order('id desc')->limit(($p-1)*$numPerPage,$numPerPage)->select();

		$this->assign('keywords',$keywords)->assign('p',$p)->assign('numPerPage',$numPerPage)->assign('count',$count)->assign('list',$articlelist)->display();
	}

	//审核页面列表
	public function verifylist(){
		//获取分页信息
		$p = I('post.pageNum',1);	//页码
		$numPerPage = I('post.numPerPage',15);	//每页显示条数
		$keywords = I('post.keywords','');	//获取关键词

		//获取审核文章信息
		$map['status'] = array('eq','2');
		$map['type'] = array('like','%page%');
		$map['title'] = array('like','%'.$keywords.'%');
		$count = $this->Article->where($map)->count();

		$verifylist = $this->Article->where($map)->order('id desc')->limit(($p-1)*$numPerPage,$numPerPage)->select();
		$this->assign('keywords',$keywords)->assign('p',$p)->assign('numPerPage',$numPerPage)->assign('count',$count)->assign('verifylist',$verifylist)->display();
	}

	//回收站页面列表
	public function recyclelist(){
		//获取分页信息
		$p = I('post.pageNum',1);	//页码
		$numPerPage = I('post.numPerPage',15);	//每页显示条数
		$keywords = I('post.keywords','');	//获取关键词

		//获取审核文章信息
		$map['status'] = array('eq',3);
		$map['type'] = array('like','%page%');
		$map['title'] = array('like','%'.$keywords.'%');
		$count = $this->Article->where($map)->count();

		$verifylist = $this->Article->where($map)->order('id desc')->limit(($p-1)*$numPerPage,$numPerPage)->select();
		$this->assign('keywords',$keywords)->assign('p',$p)->assign('numPerPage',$numPerPage)->assign('count',$count)->assign('verifylist',$verifylist)->display();
	}

	//页面添加动作
	public function add(){
		if (IS_POST){
			if ($this->Article->create()) {	//验证信息
				if ($this->Article->add()) {
					$status = array('statusCode'=>200,'message'=>'页面添加成功！','navTabId'=>'','rel'=>'Page_add','callbackType'=>'closeCurrent','forwardUrl'=>'');
					echo json_encode($status);
				}else{
					$status = array('statusCode'=>300,'message'=>'页面添加失败！','navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
					echo json_encode($status);
				}
			}else{
				$status = array('statusCode'=>300,'message'=>$this->Article->getError(),'navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
				echo json_encode($status);
			}
		}else{
			$this->display();
		}
	}

	//页面编辑动作
	public function edit(){
		
		if (IS_POST) {
			$id  = I('post.id');
			if ($this->Article->create()) {
				if (false !== $this->Article->where("id=".$id)->save()) {
					$status = array('statusCode'=>200,'message'=>'页面编辑成功！','navTabId'=>'','rel'=>'Page_edit','callbackType'=>'closeCurrent','forwardUrl'=>'');
					echo json_encode($status);
				}else{
					$status = array('statusCode'=>300,'message'=>'页面编辑失败！','navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
					echo json_encode($status);
				}
			}else{
				$status = array('statusCode'=>300,'message'=>$this->Article->getError(),'navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
				echo json_encode($status);
			}
		}else{
			$id = I('get.id');
			$pageinfo = $this->Article->find($id);

			$this->assign('pageinfo',$pageinfo)->display();
		}
	}

	//页面放入回收站动作
	public function recycle(){
		$data['status'] = 3;
		if (IS_POST) {
			$id = I('post.id');
			if (is_array($id)) {
				foreach ($id as $key => $value) {
					$this->Article->where('id='.$value)->save($data);
				}
			}else{
				$id = I('get.id');
				$this->Article->where('id='.$id)->save($data);
			}
			
			//$this->success('删除成功！');
			$status = array('statusCode'=>200,'message'=>'删除成功','navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
			echo json_encode($status);
		}
	}

	//页面还原操作
	public function recovery(){
		$data['status'] = 1;
		if (IS_POST) {
			$id = I('post.id');
			if (is_array($id)) {
				foreach ($id as $key => $value) {
					$this->Article->where('id='.$value)->save($data);
				}
			}else{
				$id = I('get.id');
				$this->Article->where('id='.$id)->save($data);
			}
			$status = array('statusCode'=>200,'message'=>'还原成功！','navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
			echo json_encode($status);
		}
	}

	//页面彻底删除动作
	public function del(){
		if (IS_POST) {
			$id = I('post.id');
			if (is_array($id)) {
				foreach ($id as $key => $value) {
					$this->Article->delete($value);
				}
			}else{
				$id = I('get.id');
				$this->Article->delete($id);
			}
			//$this->rss();
			//$this->sitemap();
			$status = array('statusCode'=>200,'message'=>'彻底删除成功！','navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
			echo json_encode($status);
		}
	}

	//页面审核动作
	public function verify(){
		$data['status'] = 1;
		if (IS_POST) {
			$id = I('post.id');
			if (is_array($id)) {
				foreach ($id as $key => $value) {
					$this->Article->where('id='.$value)->save($data);
				}
			}else{
				$id = I('get.id');
				$this->Article->where('id='.$id)->save($data);
			}
			$status = array('statusCode'=>200,'message'=>'审核成功！','navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
			echo json_encode($status);
		}
	}

	function _empty(){
        header("HTTP/1.0 404 Not Found");//使HTTP返回404状态码
        $this->display("Public:404");
    }
}
 ?>
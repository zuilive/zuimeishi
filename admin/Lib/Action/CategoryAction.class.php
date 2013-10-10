<?php
/*
 * 分类控制器
 * @create	2013-07-10
 * @author	zuihuanxiang@gmail.com
 * @last edit	2013-07-11
 */
class CategoryAction extends CommonAction{
	protected $Category;

	function _initialize(){	//初始化操作
		parent::_initialize();
		$this->Category = D('Category');
	}

	//分类管理动作
	public function index(){
		//$clist = $this->Category->select();
		$clist = $this->Category->field("cid,name,pid,path,keywords,status,description,type,url,concat(type,'-',concat(path,'-',cid))as bpath")->order('bpath')->select();
		foreach($clist as $key=>$value){
			$clist[$key]['count']=count(explode('-',$value['bpath']));
		}
		$this->assign('clist',$clist)->display();
	}

	//分类添加动作
	public function add(){
		if (IS_POST) {
			if ($this->Category->create()) {
				if ($this->Category->add()) {
					$status = array('statusCode'=>200,'message'=>'添加成功！','navTabId'=>'','rel'=>'Category_add','callbackType'=>'closeCurrent','forwardUrl'=>'');
					echo json_encode($status);
				}else{
					$status = array('statusCode'=>300,'message'=>'添加失败！','navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
					echo json_encode($status);
				}
			}else{
				
				$status = array('statusCode'=>300,'message'=>$this->Category->getError(),'navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
				echo json_encode($status);
			}
		}else{
			$clist = $this->Category->select();

			$this->assign('clist',$clist)->display();
		}
	}

	//分类编辑动作
	public function edit(){
		
		
		if (IS_POST) {
			$cid = I('post.cid');
			if ($this->Category->create()) {
				if (false !== $this->Category->where('cid='.$cid)->save()) {
					$status = array('statusCode'=>200,'message'=>'编辑成功！','navTabId'=>'','rel'=>'Category_edit','callbackType'=>'closeCurrent','forwardUrl'=>'');
					echo json_encode($status);
				}else{
					$status = array('statusCode'=>300,'message'=>'编辑失败！','navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
					echo json_encode($status);
				}
			}else{
				$status = array('statusCode'=>300,'message'=>$this->Category->getError(),'navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
				echo json_encode($status);
			}
		}else{
			$cid = I('get.cid');
			$map['cid'] = array('neq',$cid);
			$info = $this->Category->where('cid='.$cid)->find();
			$map['type'] = array('eq',$info['type']);
			$cat = $this->Category->where($map)->select();

			$this->assign('info',$info)->assign('cat',$cat)->display();
		}
	}

	//分类删除动作
	public function del(){
		if (IS_POST) {
			$id = I('post.id');
			if (is_array($id)) {
				foreach ($id as $key => $value) {
					$this->Category->delete($value);
				}
			}else{
				$id = I('get.id');
				$this->Category->delete($id);
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
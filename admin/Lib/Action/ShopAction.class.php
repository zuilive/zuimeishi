<?php
/**
* 店铺控制器
* @create 2013-10-5
* @author zuihuanxiang@gmail.com
**/
class ShopAction extends CommonAction {
	protected $Shop;

	function _initialize(){
		parent::_initialize();
		$this->Shop = D('Shop');
		import('ORG.Util.Page');	//导入分页类
	}

	//店铺列表显示动作
	public function index(){
		//获取分页信息
		$p = I('post.pageNum',1);	//页码
		$numPerPage = I('post.numPerPage',15);	//每页显示条数
		$keywords = I('post.keywords','');	//获取关键词
		
		$map['status'] = array('eq','1');
		$map['type'] = array('like','%article%');
		$map['title'] = array('like','%'.$keywords.'%');
		$count = $this->Shop->where($map)->count();	//查询满足条件的数量

		$shoplist = $this->Shop->where($map)->order('id desc')->limit(($p-1)*$numPerPage,$numPerPage)->select();

		$this->assign('keywords',$keywords)->assign('p',$p)->assign('numPerPage',$numPerPage)->assign('count',$count)->assign('list',$shoplist)->display();
	}

	//店铺添加动作
	public function add(){
		if (IS_POST) {
			if ($this->Shop->create()) {
				if ($this->Shop->add()) {
					$status = array('statusCode'=>200,'message'=>'添加成功！','navTabId'=>'','rel'=>'Shop_add','callbackType'=>'closeCurrent','forwardUrl'=>'');
					echo json_encode($status);
				}else{
					$status = array('statusCode'=>300,'message'=>'编辑失败！','navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
					echo json_encode($status);
				}
			}else{
				$status = array('statusCode'=>300,'message'=>$this->Shop->getError(),'navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
				echo json_encode($status);
			}
		}else{
			$this->display();
		}
	}

	//店铺编辑动作
	public function edit(){
		if (IS_POST) {
			$id = I('post.id');
			if ($this->Shop->create()) {
				if (false !== $this->Shop->where('id='.$id)->save()) {
					$status = array('statusCode'=>200,'message'=>'编辑成功！','navTabId'=>'','rel'=>'Shop_edit','callbackType'=>'closeCurrent','forwardUrl'=>'');
					echo json_encode($status);
				}else{
					$status = array('statusCode'=>300,'message'=>'编辑失败！','navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
					echo json_encode($status);
				}
			}else{
				
				$status = array('statusCode'=>300,'message'=>$this->Shop->getError(),'navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
				echo json_encode($status);
			}
		}else{
			
			$id = I('get.id');	//获取菜品分类id
			$info = $this->Shop->where('id = '.$id)->find();

			$this->assign('info',$info)->display();
		}
	}

	//删除店铺动作
	public function del(){
		$Food = M('Food');
		$FoodCategory = M('FoodCategory');
		if (IS_POST) {
			$id = I('post.id');
			if (is_array($id)) {
				foreach ($id as $key => $value) {
					$this->Shop->delete($value);
				}
			}else{
				$id = I('get.id');
				$this->Shop->delete($id);
			}
			$Food->where('shop_id = '.$id)->delete();
			$FoodCategory->where('shop_id = '.$id)->delete();

			$status = array('statusCode'=>200,'message'=>'删除成功！','navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
			echo json_encode($status);
		}
	}

}


?>
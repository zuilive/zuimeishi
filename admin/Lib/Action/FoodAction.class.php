<?php
/**
* 菜品控制器
* @create 2013-10-5
* @author zuihuanxiang@gmail.com
**/
class FoodAction extends CommonAction {
	protected $Food,$FoodCategory;

	function _initialize(){
		parent::_initialize();
		$this->Food = D('Food');
		$this->FoodCategory = D('FoodCategory');
		import('ORG.Util.Page');	//导入分页类
	}

	//菜品管理动作
	public function index(){
		//获取分页信息
		$p = I('post.pageNum',1);	//页码
		$numPerPage = I('post.numPerPage',15);	//每页显示条数

		$shop_id = I('shop_id');	//获取店铺id

		$map['status'] = array('eq','1');	//菜品状态
		$map['shop_id'] = array('eq',$shop_id);	//店铺id

		$count = $this->Food->where($map)->count();	//查询满足条件的数量

		$foodlist = $this->Food->where($map)->order('id desc')->limit(($p-1)*$numPerPage,$numPerPage)->select();

		$this->assign('shop_id',$shop_id)->assign('keywords',$keywords)->assign('p',$p)->assign('numPerPage',$numPerPage)->assign('count',$count)->assign('list',$foodlist)->display();

	}

	//菜品添加动作
	public function add(){
		if (IS_POST) {
			if ($this->Food->create()) {
				if ($this->Food->add()) {
					$status = array('statusCode'=>200,'message'=>'添加成功！','navTabId'=>'','rel'=>'Food_add','callbackType'=>'closeCurrent','forwardUrl'=>'');
					echo json_encode($status);
				}else{
					$status = array('statusCode'=>300,'message'=>'编辑失败！','navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
					echo json_encode($status);
				}
			}else{
				$status = array('statusCode'=>300,'message'=>$this->Food->getError(),'navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
				echo json_encode($status);
			}
		}else{
			$shop_id = I('shop_id');	//获取店铺id
			//获取菜品分类
			$food_category = $this->FoodCategory->where('shop_id = '.$shop_id)->select();

			$this->assign('food_category',$food_category)->assign('shop_id',$shop_id)->display();
		}
	}

	//编辑菜品动作
	public function edit(){
		if (IS_POST) {
			$id = I('post.id');
			if ($this->Food->create()) {
				if (false !== $this->Food->where('id='.$id)->save()) {
					$status = array('statusCode'=>200,'message'=>'编辑成功！','navTabId'=>'','rel'=>'Food_edit','callbackType'=>'closeCurrent','forwardUrl'=>'');
					echo json_encode($status);
				}else{
					$status = array('statusCode'=>300,'message'=>'编辑失败！','navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
					echo json_encode($status);
				}
			}else{
				
				$status = array('statusCode'=>300,'message'=>$this->Food->getError(),'navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
				echo json_encode($status);
			}
		}else{
			
			$id = I('get.id');	//获取菜品分类id
			$info = $this->Food->where('id = '.$id)->find();

			//获取菜品分类
			$food_category = $this->FoodCategory->where('shop_id = '.$info['shop_id'])->select();

			$this->assign('food_category',$food_category)->assign('info',$info)->display();
		}
	}

	//删除菜品动作
	public function del(){
		if (IS_POST) {
			$id = I('post.id');
			if (is_array($id)) {
				foreach ($id as $key => $value) {
					$this->Food->delete($value);
				}
			}else{
				$id = I('get.id');
				$this->Food->delete($id);
			}

			$status = array('statusCode'=>200,'message'=>'删除成功！','navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
			echo json_encode($status);
		}
	}


	//菜品分类管理动作
	public function index_category(){
		//获取分页信息
		$p = I('post.pageNum',1);	//页码
		$numPerPage = I('post.numPerPage',15);	//每页显示条数
		$keywords = I('post.keywords','');	//获取关键词

		$shop_id = I('shop_id');	//获取店铺id
		
		$map['status'] = array('eq','1');
		$map['shop_id'] = array('eq',$shop_id);	//店铺id
		$count = $this->FoodCategory->where($map)->count();	//查询满足条件的数量

		$FoodCategoryList = $this->FoodCategory->where($map)->order('id desc')->limit(($p-1)*$numPerPage,$numPerPage)->select();

		$this->assign('shop_id',$shop_id)->assign('keywords',$keywords)->assign('p',$p)->assign('numPerPage',$numPerPage)->assign('count',$count)->assign('list',$FoodCategoryList)->display();
	}

	//添加菜品分类
	public function add_category(){
		if (IS_POST) {
			if ($this->FoodCategory->create()) {
				if ($this->FoodCategory->add()) {
					$status = array('statusCode'=>200,'message'=>'添加成功！','navTabId'=>'','rel'=>'Food_category_add','callbackType'=>'closeCurrent','forwardUrl'=>'');
					echo json_encode($status);
				}else{
					$status = array('statusCode'=>300,'message'=>'编辑失败！','navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
					echo json_encode($status);
				}
			}else{
				$status = array('statusCode'=>300,'message'=>$this->FoodCategory->getError(),'navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
				echo json_encode($status);
			}
		}else{
			$shop_id = I('shop_id');	//获取店铺shop_id
			$this->assign('shop_id',$shop_id)->display();
		}
	}

	//编辑菜品分类动作
	public function edit_category(){
		if (IS_POST) {
			$id = I('post.id');
			if ($this->FoodCategory->create()) {
				if (false !== $this->FoodCategory->where('id='.$id)->save()) {
					$status = array('statusCode'=>200,'message'=>'编辑成功！','navTabId'=>'','rel'=>'FoodCategory_edit','callbackType'=>'closeCurrent','forwardUrl'=>'');
					echo json_encode($status);
				}else{
					$status = array('statusCode'=>300,'message'=>'编辑失败！','navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
					echo json_encode($status);
				}
			}else{
				
				$status = array('statusCode'=>300,'message'=>$this->FoodCategory->getError(),'navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
				echo json_encode($status);
			}
		}else{
			
			$cate_id = I('get.cate_id');	//获取菜品分类id
			$cate_info = $this->FoodCategory->where('id = '.$cate_id)->find();
			$this->assign('cate_info',$cate_info)->display();
		}
	}

	//删除菜品分类动作
	public function del_category(){
		if (IS_POST) {
			$id = I('post.cate_id');
			if (is_array($id)) {
				foreach ($id as $key => $value) {
					$this->FoodCategory->delete($value);
				}
			}else{
				$id = I('get.cate_id');
				$this->FoodCategory->delete($id);
			}

			$status = array('statusCode'=>200,'message'=>'删除成功！','navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
			echo json_encode($status);
		}
	}


}
?>
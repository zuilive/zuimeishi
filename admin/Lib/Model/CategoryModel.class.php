<?php 
/*
 * 分类模型 
 * @create	2013-07-11
 * @author	zuihuanxiang@gamil.com
 * @last edit	2013-07-11
 */
class CategoryModel extends Model{
	protected $_validate = array(	//自动验证
		array('name','require','分类名称必须填写！'),	//验证标题
		array('url','require','URL名称必须填写！'),	//验证标题
		array('url','','URL名称已经存在！',0,'unique',3),
		);
	
	protected $_auto = array(	//自动完成
			array('path','getPath',3,'callback'),	//写入分类等级
			//array('url','gerUlr',3,'callback'),	//获取url
		);

	//获取分类等级
	protected function getPath(){
		//查询方法要记住不用  $this->where
		$pid=isset($_POST['pid'])?(int)$_POST['pid']:0;
			
		if($pid==0){	
			return 0;
		}
		$list=$this->where("cid=$pid")->find();
		$data=$list['path'].'-'.$list['cid'];	

		return $data;
	}
	
	
}

?>
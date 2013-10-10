<?php 
/*
 * 文章模型 
 * @create	2013-07-09
 * @author	zuihuanxiang@gamil.com
 * @last edit	2013-07-09
 */
class ArticleModel extends Model{
	protected $_validate = array(	//自动验证
		array('title','require','文章名称必须填写！'),	//验证标题
		array('url','require','URL名称必须填写！'),
		array('url','','URL名称已经存在！',0,'unique',3),
		);
	
	protected $_auto = array(	//自动完成
			array('inputtime','time',1,'function'),	//添加文章时写入添加时间
			array('updatetime','time',3,'function'),	//更新、添加文章时写入时间
		);
	
	
}

?>
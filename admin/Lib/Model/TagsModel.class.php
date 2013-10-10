<?php 
/*
 * 标签模型 
 * @create	2013-07-16
 * @author	zuihuanxiang@gamil.com
 * @last edit	2013-07-16
 */
class TagsModel extends Model{
	protected $_validate = array(	//自动验证
		array('name','require','标签名称必须填写！'),
		array('name','','标签已经存在！',0,'unique',3),
		array('urlname','require','URL名称必须填写！'),
		array('urlname','','URL已经存在！',0,'unique',3),
		);
	
}

?>
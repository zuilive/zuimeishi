<?php
class ArticleViewModel extends ViewModel{
	public $viewFields = array(
		'Article'=>array('id','cateid','title','description','updatetime','url','status','type','hits'),
		'Category'=>array('cid','name','url'=>'curl','_on'=>'Article.cateid=Category.cid'),
		);
}

?>
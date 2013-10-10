<?php
/*
 * 文章控制器
 * @create	2013-07-09
 * @author	zuihuanxiang@gmail.com
 * @last edit	2013-07-10
 */
class ArticleAction extends CommonAction{
	protected $Article;
	
	function _initialize(){
		parent::_initialize();
		$this->Article = D('Article');
		import('ORG.Util.Page');	//导入分页类
	}
	public function image(){
		$this->display();
	}

	//文章管理动作
	public function index(){

		//获取分页信息
		$p = I('post.pageNum',1);	//页码
		$numPerPage = I('post.numPerPage',15);	//每页显示条数
		$keywords = I('post.keywords','');	//获取关键词
		
		$map['status'] = array('eq','1');
		$map['type'] = array('like','%article%');
		$map['title'] = array('like','%'.$keywords.'%');
		$count = $this->Article->where($map)->count();	//查询满足条件的数量

		$articlelist = $this->Article->where($map)->order('id desc')->limit(($p-1)*$numPerPage,$numPerPage)->select();

		$this->assign('keywords',$keywords)->assign('p',$p)->assign('numPerPage',$numPerPage)->assign('count',$count)->assign('list',$articlelist)->display();
	}

	//审核文章列表
	public function verifylist(){
		//获取分页信息
		$p = I('post.pageNum',1);	//页码
		$numPerPage = I('post.numPerPage',15);	//每页显示条数
		$keywords = I('post.keywords','');	//获取关键词

		//获取审核文章信息
		$map['status'] = array('eq','2');
		$map['type'] = array('like','%article%');
		$map['title'] = array('like','%'.$keywords.'%');
		$count = $this->Article->where($map)->count();

		$verifylist = $this->Article->where($map)->order('id desc')->limit(($p-1)*$numPerPage,$numPerPage)->select();
		$this->assign('keywords',$keywords)->assign('p',$p)->assign('numPerPage',$numPerPage)->assign('count',$count)->assign('verifylist',$verifylist)->display();
	}

	//回收站文章列表
	public function recyclelist(){
		//获取分页信息
		$p = I('post.pageNum',1);	//页码
		$numPerPage = I('post.numPerPage',15);	//每页显示条数
		$keywords = I('post.keywords','');	//获取关键词

		//获取回收站文章信息
		$map['status'] = array('eq',3);
		$map['type'] = array('like','%article%');
		$map['title'] = array('like','%'.$keywords.'%');
		$count = $this->Article->where($map)->count();
		
		$recyclelist = $this->Article->where($map)->order('id desc')->limit(($p-1)*$numPerPage,$numPerPage)->select();

		$this->assign('keywords',$keywords)->assign('p',$p)->assign('numPerPage',$numPerPage)->assign('count',$count)->assign('recyclelist',$recyclelist)->display();
	}
	
	//文章添加动作
	public function add(){
		if (IS_POST){
			if ($this->Article->create()) {	//验证信息
				if ($id = $this->Article->add()) {
					$this->tags($id,I('tags',''));	//添加标签

					$status = array('statusCode'=>200,'message'=>'文章添加成功！','navTabId'=>'','rel'=>'Article_add','callbackType'=>'closeCurrent','forwardUrl'=>'');
					echo json_encode($status);
				}else{
					$status = array('statusCode'=>300,'message'=>'文章添加失败！','navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
					echo json_encode($status);
				}
			}else{
				$status = array('statusCode'=>300,'message'=>$this->Article->getError(),'navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
				echo json_encode($status);
			}
			
		}else{
			$Category = M('Category');
			$map['type'] = array('eq','article');
			$catlist = $Category->where($map)->select();
			$this->assign('catlist',$catlist)->display();
		}	
	}

	//标签操作函数
	public function tags($id,$tags){
		//标签处理
		$tags =preg_replace("/\s|　/","",$tags);	//除去空格
		$tag_array = explode(',', $tags);	//截取字符串成数组
		$Tags = M('Tags');
		$Tags_relationships = M('TagsRelationships');
		foreach ($tag_array as $key => $value) {
			if ($value && ($value != '')) {	//没有空格，将数据写入
				$map['name'] = array('eq',$value);
				if ($result = $Tags->where($map)->find()) {	//已有此标签
					unset($map);
					$map['tagid'] = array('eq',$result['id']);
					$map['postid'] = array('eq',$id);
					$map['type'] = array('eq','article');
					if (!$Tags_relationships->where($map)->find()) {	//关系表中没有内容，则新增
						$data['tagid'] = $result['id'];
						$data['postid'] = $id;
						$data['type'] = 'article';
						$Tags_relationships->add($data);
						unset($data);
						unset($map);
					}
										
				}else{
					//没有标签，将表zui_tags和zui_tags_relationships添加数据
					$data['name'] = $value;
					$data['urlname'] = $value;
					$tid = $Tags->add($data);
					unset($data);
					//在表zui_tags_relationships中添加数据
					$data['tagid'] = $tid;
					$data['postid'] = $id;
					$data['type'] = 'article';
					$Tags_relationships->add($data);
					unset($data);
				}
				
			}
		}

	}

	//文章编辑动作
	public function edit(){
		
		if (IS_POST) {
			$id = I('post.id');
			if ($this->Article->create()) {
				if (false !== $this->Article->where("id=".$id)->save()) {
					$this->tags($id,I('tags',''));	//添加标签
					$status = array('statusCode'=>200,'message'=>'文章编辑成功！','navTabId'=>'','rel'=>'Article_edit','callbackType'=>'closeCurrent','forwardUrl'=>'');
					echo json_encode($status);
				}else{
					$status = array('statusCode'=>300,'message'=>'文章编辑失败！','navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
					echo json_encode($status);
				}
			}else{
				
				$status = array('statusCode'=>300,'message'=>$this->Article->getError(),'navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
				echo json_encode($status);
			}
		}else{
			$id = I('get.id');
			$arcinfo = $this->Article->find($id);
			$Category = M('Category');
			$map['type'] = array('eq','article');
			$catlist = $Category->where($map)->select();
			//获取标签信息
			$tagname = '';
			$Tags = M('Tags');
			$Tags_relationships = M('TagsRelationships');
			$tlist = $Tags_relationships->where('postid='.$id)->select();
			foreach ($tlist as $key => $value) {
				$tag = $Tags->where('id='.$value['tagid'])->find();
				if ($key) {
					$tagname = $tag['name'].','.$tagname;
				}else{
					$tagname = $tag['name'];
				}
			}
			$this->assign('tags',$tagname)->assign('catlist',$catlist)->assign('arcinfo',$arcinfo)->display();
		}
	}

	//文章放入回收站动作
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

	//文章还原操作
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

	//文章彻底删除动作
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

			$status = array('statusCode'=>200,'message'=>'彻底删除成功！','navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
			echo json_encode($status);
		}
		
	}

	//文章审核动作
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

	//图片上传
	public function upload(){
		import('ORG.Net.UploadFile');
		$upload = new UploadFile();// 实例化上传类
		$upload->maxSize  = 3145728 ;// 设置附件上传大小
		$upload->allowExts  = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
		$upload->savePath =  './Upload/'.date("Ymd").'/';// 设置附件上传目录
		/*$upload->thumb = true;
		$upload->thumbPrefix = 'm_';
		$upload->thumbMaxWidth = '350';
		$upload->thumbMaxHeight = '250';
		$upload->thumbRemoveOrigin = true;*/

		if(!$upload->upload()) {// 上传错误提示错误信息
			$info = $upload->getErrorMsg();
		}else{// 上传成功 获取上传文件信息
			$info =  $upload->getUploadFileInfo();
		}
		$savename = date("Ymd").'/'.$info['0']['savename'];
		$savepath = __ROOT__.'/Upload/'.$savename;
		$thumb = array('thumb'=>$savename,'savepath'=>$savepath);
		echo json_encode($thumb);
	}

	public function _empty(){
        header("HTTP/1.0 404 Not Found");//使HTTP返回404状态码
        $this->display("Public:404");
    }
}
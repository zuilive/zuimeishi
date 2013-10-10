<?php
class IndexAction extends CommonAction{
	public function index(){
		//dump($_SESSION);
		$info = array(
            '操作系统' => PHP_OS,
            '运行环境' => $_SERVER["SERVER_SOFTWARE"],
            'PHP运行方式' => php_sapi_name(),
            'MYSQL版本' => mysql_get_server_info(),
            '上传附件限制' => ini_get('upload_max_filesize'),
            '执行时间限制' => ini_get('max_execution_time') . "秒",
            '剩余空间' => round((@disk_free_space(".") / (1024 * 1024)), 2) . 'M',
        );
        //dump($_SESSION['_ACCESS_LIST']);

        $acount['1'] = M('Article')->where('status=1')->count();    //已发布文章数
        $acount['2'] = M('Article')->where('status=2')->count();    //待审核文章数
        $acount['3'] = M('Article')->where('stauts=3')->count();    //回收站文章数

        $ucount = M('User')->where('status=1')->count();    //用户数量
        
		$this->assign('acount',$acount)->assign('ucount',$ucount)->assign('info',$info)->display();
	}

    //修改个人信息
    public function editinfo(){
        $User = D('User');
        if (IS_POST) {
            if ($User->create()) {
                if (false !== $User->where('id = '.$_SESSION['zui_uid'])->save()) {
                    $this->success('修改成功！');
                }else{
                    $this->error('修改失败！');
                }
            }else{
                $this->error($User->getError());
            }
        }else{
            $info = $User->where('id = '.$_SESSION['zui_uid'])->find();
            $this->assign('uinfo',$info)->display();
        }
    }

    public function sidebar(){
        $content = '<div class="accordion dwz-accordion" fillspace="sideBar"><ul><li><a href="'.__APP__.'/Index/index" target="navTab" rel="Index_index">首页</a></li></ul></div>';
        $this->show($content);
    }

    public function delcache(){
        $abs_dir=dirname(dirname(dirname(dirname(__FILE__))));
        //这里主要是为了得到项目的根目录，绝对路径
        //website absolute directory [app root directory]
        $text=$this->deleteFile($abs_dir.'/Cache/home');
        $text=$text.$this->deleteFile($abs_dir.'/Cache/admin');
      
        $this->success('缓存已经清除！');
    }

    protected function deleteFile($path){
        global $text;
        if (is_dir($path)){
            $handle=opendir($path);         
            while($list=readdir($handle)){
                if ($list=='.' || $list=='..'){
                    //do nothing
                }else{
                    $list=$path.'/'.$list;
                }
                switch ($list){
                    case $list=='.' || $list=='..':
                        //echo $list.' this is  special directory ;
                        continue;
                    case is_file($list):
                        if (unlink($list)){
                            $text=$text.'<span>delete file</span> '.$list.'';
                        }else {
                            $text=$text. 'delete file failure';
                        }
                        break;
                    case is_dir($list):
                        $text=$text. 'open directory '.$list.'';
                        $this->deleteFile($list);
                        break;
                    default:
                        //$text=$text.'default action '.$list.'';
                        continue;
                }
            }
        }else{
            $text=$text. $path.' sorry the path is not directory';
        }
        return  $text;
    }

    function _empty(){
        header("HTTP/1.0 404 Not Found");//使HTTP返回404状态码
        $this->display("Public:404");
    }
}
?>

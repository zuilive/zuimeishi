<?php
/*
*	网站地图控制器
*	@create 2013-9-7
*	@author zuihuanxiang@gmail.com
*/
class SitemapAction extends CommonAction{
        public function index(){
                $this->display();
        }

        //生成动作
        public function start(){
                $this->sitemap();
                $this->rss();
                $status = array('statusCode'=>200,'message'=>'网站地图生成成功！','navTabId'=>'','rel'=>'','callbackType'=>'','forwardUrl'=>'');
                echo json_encode($status);
        }

        //生成地图
        public function sitemap(){
                import('ORG.Net.SitemapGenerator');
                // create object
                $sitemap = new SitemapGenerator($siteinfo['1']['value']);

                //首页链接
                $siteinfo = M('Config')->select();
                $sitemap->addUrl($siteinfo['1']['value'],date('c'),'daily','1');
                $i = 0;
                $html_sitemap[$i]['title'] = $siteinfo['0']['value'];
                $html_sitemap[$i]['url'] = $siteinfo['1']['value'];
                $i++;
        
                //分类链接
                $Category = M('Category')->where('status = 1')->select();
                $url = 0;
                foreach ($Category as $key => $value) {
                        $url = $siteinfo['1']['value'].'/'.$value['url'];
                        $sitemap->addUrl($url,date('c'),'','0.5');

                        $html_sitemap[$i]['title'] = $value['name'];
                        $html_sitemap[$i]['url'] = $url;
                        $i++;
                }

                //文章链接
                $armap['status'] = array('eq',1);
                $armap['type'] = array('eq','article');
                $post = D('ArticleView')->where($armap)->select();
                $url = 0;
                foreach ($post as $key => $value) {
                        $url = $siteinfo['1']['value'].'/'.$value['curl'].'/'.$value['url'].'.html';
                        $sitemap->addUrl($url,date('c'),'daily');

                        $html_sitemap[$i]['title'] = $value['title'];
                        $html_sitemap[$i]['url'] = $url;
                        $i++;
                }

                //标签链接
                $tags = M('Tags')->select();
                $url = 0;
                foreach ($tags as $key => $value) {
                        $url = $siteinfo['1']['value'].'/tags/'.$value['urlname'];
                        $sitemap->addUrl($url,date('c'));

                        $html_sitemap[$i]['title'] = $value['name'];
                        $html_sitemap[$i]['url'] = $url;
                        $i++;
                }
                //生成HTML版本的网站地图
                $this->html_sitemap($html_sitemap);

                // create sitemap
                $sitemap->createSitemap();

                // write sitemap as file
                $sitemap->writeSitemap();

                // submit sitemaps to search engines
                $sitemap->submitSitemap();
        }

        //生成Rss
        public function rss(){
                import('ORG.Util.Rss');
                $siteinfo = M('Config')->select();
                $RssConf = array(
                        'channelTitle' => $siteinfo['0']['value'],
                        'channelLink'=> $siteinfo['1']['value'],
                        'copyright'=>'zuilive'
	);
                $post = D('ArticleView')->select();
                $RSS = new Rss($RssConf);
                foreach ($post as $k => $v) {
                        $RSS->AddItem($v['title'],$v['id'],$v['description'],$v['updatetime'],$v['id'],'作者','分类');
                }
                $RSS->SaveToFile('rss.xml');
        }

        //Html地图生成
        public function html_sitemap($html_sitemap){

                $file = fopen('sitemap.html', "w");
                foreach ($html_sitemap as $key => $value) {
                        fwrite($file, '<p><a href=" '.$value['url'].' ">'.$value['title'].'</a></p>');
                }
                
                fclose($file);  //关闭文件
        }
}

?>
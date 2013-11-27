<?php
/**
 * @todo 百度动漫全量和追加抓取
 * @author gaobo
 */
class clBaiduDonmanTask extends sfMondongoTask
{
    var $category;
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
            // add your own options here
            new sfCommandOption('pages', null, sfCommandOption::PARAMETER_OPTIONAL, 'pages'),
        ));

        $this->namespace        = 'cl';
        $this->name             = 'BaiduDonman';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [cl:BaiduTelplay|INFO] task does things.
Call it with:

[php symfony cl:BaiduDonman|INFO]
EOF;

        $this->acceptTypes = array("program","series");
    }

    protected function execute($arguments = array(), $options = array())
    { 
        error_reporting(0);
		require $project_path.'lib/vendor/simple_html_dom.php';
        if($options['pages']){
            self::clAddition(intval($options['pages']));
        }else{
            self::clAll(348);
        }
    }
    
    /**
     * 追加抓取
     */
    protected function clAddition($page)
    {
        $mongo = $this->getMondongo();
        $vc    = $mongo->getRepository("VideoCrawler");
        $WikiRepository = $mongo->getRepository('Wiki');
        
        while ($page>0) {
            $url = 'http://video.baidu.com/comic/?area=&author=&type=&prop=&start=&order=pubtime&pn='.$page;
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HEADER, 1);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $data = curl_exec($curl);
            curl_close($curl);
            //print_r($data);exit;
            $html = str_get_html($data);
            $j = 1;
            foreach($html->find('li[class=video-item]') as $element) {
                $title = $element->find('dt',0)->plaintext;
                $onecv = $vc->findOne(array('query'=>array('title'=>$title,'model'=>'teleplay')));
                if($onecv){
                    echo 'had it!',"\n";
                    continue;
                }
                $url   = $element->find('a',0)->href;
                $cl = new VideoCrawler();
        
                $cl->setSite('www.baidu.com');
                $cl->setModel('teleplay');
                $cl->setTitle($title);
                $cl->setUrl($url);
                $wiki  = $WikiRepository->getWikiByTitle($title);
                if($wiki){
                    if($wiki->getModel()=='teleplay'){
                        $wkId  = (string)$wiki->getId();
                        $cl->setWikiId($wkId);
                        // 匹配wiki，如果匹配成功则抓取其分集信息入wiki_meta！！！！！！！此功能暂不开启
                        /* $arr = explode('?',$url);
                         preg_match('/id=(\d+)/i',$arr[1],$match);
                        $wiki_meta_url = 'http://video.baidu.com/tvplot/?id='.$match[1];
                        $wiki_metas    = json_decode(file_get_contents($wiki_meta_url),true);
                        $Wiki_metas_Repository = $mongo->getRepository('WikiMeta');
                        foreach($wiki_metas['intros'] as $k=>$v){
                        $meta = $Wiki_metas_Repository->find(array('query'=>array('wiki_id'=>$wkId,'title'=>str_replace(' ', '', $v['subtitle']))));
                        if($meta){
                        echo 'meta had it!',"\n";
                        continue;
                        }else{
                        $wm = new WikiMeta();
                        $wm->setWikiId($wkId);
                        $wm->setTitle(str_replace(' ', '', $v['subtitle']));
                        $wm->setContent($v['intro']);
                        $wm->setHtmlCache($v['intro']);
                        $wm->setMark($v['episode']);
                        }
                        } */
                    }
                }
                $cl->setState(1);
                $cl->save();
                echo "the ",$j,"\n";
                $j++;
                //echo iconv('UTF-8','GB2312//IGNORE',$element->find('dt',0)->plaintext."<br>\n");
            }
            echo "page:",$page,"OVER","\n";
            $page--;
        }
    }
    
    /**
     * 全量抓取
     */
    protected function clAll($page)
    {
        $mongo = $this->getMondongo();
        $vc    = $mongo->getRepository("VideoCrawler");
        $WikiRepository = $mongo->getRepository('Wiki');
        while ($page>0) {
            $url = 'http://video.baidu.com/topcomic/?area=&figure=&type=&prop=&start=&order=hot&pn='.$page;
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HEADER, 1);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $data = curl_exec($curl);
            curl_close($curl);
            $html = str_get_html($data);
            $j = 1;
    
            foreach($html->find('a[statisic=name]') as $element) {
                
                $urltemp   = str_replace('amp;', '', $element->href);
                //echo $urltemp;exit;
                
                $curl2 = curl_init();
                curl_setopt($curl2, CURLOPT_URL, $urltemp);
                curl_setopt($curl2, CURLOPT_HEADER, 1);
                curl_setopt($curl2, CURLOPT_RETURNTRANSFER, 1);
                $data = mb_convert_encoding(curl_exec($curl2), 'utf-8', 'gb2312,UTF-8,ASCII');
                curl_close($curl2);
                
                //$html2 = str_get_html($data);
                //$data = mb_convert_encoding($data, 'utf-8', 'gb2312,UTF-8,ASCII');
                preg_match_all('/<h3>(.*?)<\/span>/s',$data,$matches);
                
                foreach($matches[0] as $val){
                    preg_match('/<font color=#c60a00>(.*?)<\/span>/s',$val,$title);
                    $temparr = array('</font>'=>'','<font color=#c60a00>'=>'',"\r\n"=>'','	'=>'','  '=>'',' '=>'','&nbsp;'=>' ');
                    $title = strtr($title[1],$temparr);
                    preg_match('/<a href="(.*?)"/s',$val,$url);
                    $url = $url[1];
                    
                    $onecv = $vc->findOne(array('query'=>array('title'=>$title,'model'=>'teleplay')));
                    if($onecv){
                        $onecv->setState(0);
                        $onecv->save();
                        echo 'had it!',"\n";
                        continue;
                    }
                    //$url   = str_replace('amp;', '', $element->href);
                    $cl = new VideoCrawler();
                    
                    $cl->setSite('www.baidu.com');
                    $cl->setModel('teleplay');
                    $cl->setTitle($title);
                    $cl->setUrl('http://video.baidu.com'.$url);
                    $wiki  = $WikiRepository->getWikiByTitle($title);
                    if(!$wiki){
                        $wiki  = $WikiRepository->getWikiBySlug($title);
                    }
                    if(!$wiki){
                        $wiki  = $WikiRepository->findOne(array("query"=>array("alias"=>$title)));
                    }
                    if($wiki){
                        if($wiki->getModel()=='teleplay'){
                            $wkId  = (string)$wiki->getId();
                            $cl->setWikiId($wkId);
                        }
                    }
                    $cl->setState(0);
                    $cl->save();
                }
                
                
                /* foreach($html2->find('div[class=title-wrapper]') as $el){
                    //$title = mb_convert_encoding($el->find('a',0)->plaintext, 'utf-8', 'gb2312,UTF-8,ASCII');
                    $title = $el->find('a',0)->plaintext;
                    $temparr = array('	'=>'','  '=>'',' '=>'','&nbsp;'=>' ');
		            $title = strtr($title,$temparr);
                    $url   = $el->find('a',0)->href;
                    
                    
                } */
                echo "the ",$j,"\n";
                $j++;
            }
            echo "page:",$page,"OVER","\n";
            $page--;
        }
    }
}

<?php
/**
 * @tobo   按其更新顺序抓取百度电影视频，入库content_temp
 * @author gaobo
 * @time   2012-12-13
 */
class clBaiduTask extends sfMondongoTask
{
    var $category;
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','stb'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
            // add your own options here
        ));

        $this->namespace        = 'cl';
        $this->name             = 'Baidu';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [cl:Baidu|INFO] task does things.
Call it with:

[php symfony cl:Baidu|INFO]
EOF;

        $this->acceptTypes = array("program","series");
    }

    protected function execute($arguments = array(), $options = array())
    { 
        error_reporting(0);
        require '/www/newepg/lib/vendor/simple_html_dom.php';
        $mongo = $this->getMondongo();
        $vc    = $mongo->getRepository("VideoCrawler");
        $WikiRepository = $mongo->getRepository('Wiki');
        $i = 1;
        while ($i<=1413) {
          $url = 'http://video.baidu.com/movie/?director=&actor=&type=&area=&complete=&order=pubtime&start=&rating=&pn='.$i;
          $curl = curl_init();
          curl_setopt($curl, CURLOPT_URL, $url);
          curl_setopt($curl, CURLOPT_HEADER, 1);
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
          $data = curl_exec($curl);
          curl_close($curl);
          
          $html = str_get_html($data);
          $j = 1;
          foreach($html->find('li[class=video-item]') as $element) {
            $title = $element->find('dt',0)->plaintext;
            $onecv = $vc->findOne(array('query'=>array('title'=>$title,'model'=>'film')));
            if($onecv){
              echo 'had it!',"\n";
              continue;
            }
            $url   = $element->find('a',0)->href;
            $cl = new VideoCrawler();
            
            $cl->setSite('www.baidu.com');
            $cl->setModel('film');
            $cl->setTitle($title);
            $cl->setUrl($url);
            $wiki  = $WikiRepository->getWikiByTitle($title);
            if($wiki){
              if($wiki->getModel()=='film'){
              	$wkId  = (string)$wiki->getId();
              	$cl->setWikiId($wkId);
              }
            }
            $cl->setState(1);
            $cl->save();
            echo "the ",$j,"\n";
            $j++;
            //echo iconv('UTF-8','GB2312//IGNORE',$element->find('dt',0)->plaintext."<br>\n");
          }
          echo "page:",$i,"OVER","\n";
          $i++;
        }
    }
}

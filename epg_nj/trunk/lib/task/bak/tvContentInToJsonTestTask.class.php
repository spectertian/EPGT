<?php
/**
 * @tobo   将Content_Temp表中数据按接口规范导出为XML格式文件。交予CMS方
 * @author gaobo
 * @time   2012-12-13
 */
class tvContentInToJsonTestTask extends sfMondongoTask
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

        $this->namespace        = 'tv';
        $this->name             = 'ContentInToJsonTest';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [tv:ContentInToJsonTest|INFO] task does things.
Call it with:

[php symfony tv:ContentInToJsonTest|INFO]
EOF;

        $this->acceptTypes = array("program","series");
    }

    protected function execute($arguments = array(), $options = array())
    { 
        error_reporting(0);
        $mongo = $this->getMondongo();
        $import_repo = $mongo->getRepository("ContentTemp");
        $WikiRepository = $mongo->getRepository('Wiki');
        $i = 0;
        
        $pid = 'www.dayang.com';
        $imObj = $import_repo->find(array('query'=>array('wiki_id'=>array('$exists'=>true))));
        $date = date("Y-m-d",strtotime(time()));

        foreach ($imObj as $val){
            $wiki_id = $val->getWikiId();
            echo $i."\t".$wiki_id."\n";
            $nodeArray = array();
            $wiki = $WikiRepository->findOneById(new MongoId($wiki_id));
            if($wiki)
            {
                $wikititle = $wiki->getTitle();
                $newArray = array();
                $nodeArray['asset_id'] = $val->getFromId();
                $nodeArray['media'] = array(
                    'wiki_id'    => (string)$wiki->getId(),
                    'title'   => $wikititle,
                );
                $nodeArray = $this->getOneWikiVideoSource($wiki, 0, $nodeArray);
                
                $title = $val->getFromTitle();
                echo $i."\t".iconv("utf8","GBK",$wikititle)."\n";
                $newArray['Metadata'][0]['AMS'][0][DOM::ATTRIBUTES]=array(
                    'Verb'=>'',
                    'Asset_Class'=>'package',
                    'Asset_ID'=>$val->getInjectId(),
                    'Asset_Name'=>$wikititle,
                    'Provider_ID'=>$pid,
                    'Creation_Date'=>$date,
                    'Description'=>$wikititle,
                    'Version_Major'=>'1',
                    'Version_Minor'=>'2',
                    'Product'=>'MOD',
                );
                $newArray['Metadata'][0]['App_Data'][0][DOM::ATTRIBUTES]=array(
                    'Value'=>'CableLabsVOD 1.1',
                    'Name'=>'Metadata_Spec_Version',
                    'App'=>'MOD',
                );
                $newArray['Asset'][0]['Metadata'][0]['AMS'][0][DOM::ATTRIBUTES]=array(
                    'Verb'=>'',
                    'Asset_Class'=>'title',
                    'Asset_ID'=>$nodeArray['asset_id'],
                    'Asset_Name'=>$wikititle,
                    'Provider_ID'=>$pid,
                    'Creation_Date'=>$date,
                    'Description'=>$wikititle,
                    'Version_Major'=>'1',
                    'Version_Minor'=>'2',
                    'Product'=>'MOD',
                );
                
                $newArray['Asset'][0]['Metadata'][0]['App_Data'][0][DOM::ATTRIBUTES]=array(
                    'App'=>'MOD',
                    'Name'=>'Asset_ID',
                    'Value'=>$nodeArray['asset_id'],
                );
                $newArray['Asset'][0]['Metadata'][0]['App_Data'][1][DOM::ATTRIBUTES]=array(
                    'App'=>'MOD',
                    'Name'=>'Wiki_ID',
                    'Value'=>$nodeArray['media']['wiki_id'],
                );
                $newArray['Asset'][0]['Metadata'][0]['App_Data'][2][DOM::ATTRIBUTES]=array(
                    'App'=>'MOD',
                    'Name'=>'Title',
                    'Value'=>$nodeArray['media']['title'],
                );
                
                //info start
                $j = 3;
                foreach ($nodeArray['media']['info'] as $k=>$v){
                  $newk = $k;
                  $newk = ucfirst($newk);
                  $newArray['Asset'][0]['Metadata'][0]['App_Data'][$j][DOM::ATTRIBUTES]=array(
                      'App'=>'MOD',
                      'Name'=>$newk,
                      'Value'=>$nodeArray['media']['info'][$k],
                  );
                  $j++;
                }
                
                $newArray['Asset'][0]['Metadata'][0]['App_Data'][$j+1][DOM::ATTRIBUTES]=array(
                    'App'=>'MOD',
                    'Name'=>'Description',
                    'Value'=>$nodeArray['media']['description'],
                );//info end
                
                //posters start
                foreach ($nodeArray['media']['posters'] as $k=>$v){
                      $newArray['Asset'][0]['Asset'][$k+1]['Metadata'][0]['AMS'][0][DOM::ATTRIBUTES]=array(
                          'Product'=>'MOD',
                          'Version_Minor'=>"1",
                          'Version_Major'=>"2",
                          'Description'=>$wikititle."海报",
                          'Creation_Date'=>$date,
                          'Provider_ID'=>$pid,
                          'Asset_Name'=>$wikititle."海报",
                          //'Asset_ID'=>'g'.$k.'b'.microtime(),
                          'Asset_ID'=>self::getcode($k.$nodeArray['asset_id']),
                          'Asset_Class'=>"poster",
                          'Verb'=>""
                      );
                      $size = explode('*', $nodeArray['media']['posters'][$k]['size']);
                      $newArray['Asset'][0]['Asset'][$k+1]['Metadata'][0]['App_Data'][0][DOM::ATTRIBUTES]=array(
                          'App'=>'MOD',
                          'Name'=>'Image_Aspect_Ratio',
                          'Value'=>$nodeArray['media']['posters'][$k]['size']
                      );
                      $newArray['Asset'][0]['Asset'][$k+1]['Metadata'][0]['App_Data'][1][DOM::ATTRIBUTES]=array(
                          'App'=>'MOD',
                          'Name'=>'Content_File_Size',
                          'Value'=>'1000'
                      );
                      $newArray['Asset'][0]['Asset'][$k+1]['Metadata'][0]['App_Data'][2][DOM::ATTRIBUTES]=array(
                          'App'=>'MOD',
                          'Name'=>'Content_Check_Sum',
                          'Value'=>'11111111111111111111111111111111'
                      );
                      $newArray['Asset'][0]['Asset'][$k+1]['Metadata'][0]['App_Data'][3][DOM::ATTRIBUTES]=array(
                          'App'=>'MOD',
                          'Name'=>'Color_Type',
                          'Value'=>'RGB'
                      );
                      $newArray['Asset'][0]['Asset'][$k+1]['Metadata'][0]['App_Data'][4][DOM::ATTRIBUTES]=array(
                          'App'=>'MOD',
                          'Name'=>'Image_Encoding_Profile',
                          'Value'=>'jpg',
                      );
                      $newArray['Asset'][0]['Asset'][$k+1]['Metadata'][0]['App_Data'][5][DOM::ATTRIBUTES]=array(
                          'App'=>'MOD',
                          'Name'=>'Horizontal_Pixels',
                          'Value'=>$size[0]
                      );
                      $newArray['Asset'][0]['Asset'][$k+1]['Metadata'][0]['App_Data'][6][DOM::ATTRIBUTES]=array(
                          'App'=>'MOD',
                          'Name'=>'Vertical_Pixels',
                          'Value'=>$size[1]
                      );
                      $newArray['Asset'][0]['Asset'][$k+1]['Content'][0][DOM::ATTRIBUTES] = array(
                          //'Value'=>str_replace('image.5i.tv', '172.31.139.17:81', $nodeArray['media']['posters'][$k]['url'])
                          'Value'=>$nodeArray['media']['posters'][$k]['url']
                      );
                }//posters end
                echo $i."\t".iconv("utf8","GBK",$newArray['Metadata'][0]['AMS'][0][DOM::ATTRIBUTES]['Asset_Name'])."\n";
                $file='tmp/adinew/1228/'.$nodeArray['asset_id'].'.xml';
                $assetidXML = DOM::arrayToXMLString($newArray,'ADI',array(''=>''));
                file_put_contents($file,$assetidXML);
                $i++;
            }
        }
    }
    
    /*
     * wiki对象返回视频源数组
    * @param  mongo object  $wiki
    * @param  array $nodeArray
    * @param  int $type 默认为1 如果为GetEpisodeListByUser调用此函数则传入数组
    * $type['eid']：分集video_id
    * $type['marktime']：标记秒数
    * $type['markid']：mark_id
    * @return $nodeArray
    * @author guoqiang.zhang
    * @editor lifucang
    * 和getWikiVideoSource一样，只是$nodeArray['media'][$i]部分全部换为$nodeArray['media']，目前只有GetWikiInfo调用
    */
    private function getOneWikiVideoSource($wiki,$i,$nodeArray,$mytag='media',$type='',$biaozhi=0){
      $director = !$wiki->getDirector() ? '' : implode(',', $wiki->getDirector());
      $actors = !$wiki->getStarring() ? '' : implode(',', $wiki->getStarring());
      $tags = !$wiki->getTags() ? '' : $this->getTag($wiki->getTags(),array($this->category[1]['name'],$this->category[2]['name']));
      $area = !$wiki->getCountry() ? "" : $wiki->getCountry();
      $language = !$wiki->getLanguage() ? "" : $wiki->getLanguage();
      $score = $wiki->getRating() ?  $wiki->getRatingFloat() : $wiki->getRatingInt();
      $playdate = !$wiki->getReleased() ? '' : $wiki->getReleased();
      $praise = !$wiki->getLikeNum() ? 0 : $wiki->getLikeNum();
      $dispraise = !$wiki->getDislikeNum() ? 0 : $wiki->getDislikeNum();
      //$videos = $wiki->getVideos();
      //$refererSource = array('youku'=>"优酷",'qiyi'=>'奇艺','sohu'=>'搜狐','sina'=>'新浪','cms'=>'cms','tps'=>'tps');
      //$source = '';
      //$prefer = "奇艺"; //优选片源
      /*
      if ($videos != NULL) {
        foreach ($videos as $video) {
          $source = $source ? $source.",".$refererSource[$video->getReferer()]: $refererSource[$video->getReferer()];
        }
      }*/
      
      $whether_mark = (gettype($type) =='array')?true:false;
      $nodeArray[$mytag]['info'] = array(
          "director" => $director,
          "actors" => $actors,
          "type" => $tags,
          "area" => $area,
          "language" => $language,
          "score" => $score,
          "playdate" => $playdate,
          "praise" => $praise,
          "dispraise" => $dispraise,
          "source" => $source,
          "prefer" => $prefer
      );
      
      $nodeArray[$mytag]['description'] = $wiki->getContent();
      $cover = $wiki->getCover();
      if ($cover) {
        $nodeArray[$mytag]['posters'][0] = array(
            "type" => "small",
            "size" => "120*160",
            "url" => self::thumb_url($cover, 120, 160),
        );
        $nodeArray[$mytag]['posters'][1] = array(
            "type" => "big",
            "size" => "240*320",
            "url" => self::thumb_url($cover, 240, 320),
        );
        $nodeArray[$mytag]['posters'][2] = array(
            "type" => "max",
            "size" => "1240*460",
            "url" => self::thumb_url($cover, 1240, 460),
        );
      }
      
      /* $screens = $wiki->getScreenshotUrls();
      foreach($screens as $k => $screen)
      {
        $nodeArray[$mytag]['screens'][$k]= array(
        'url'    =>  $screens[$k],
         );
      }
      exit('a'); */
      return $nodeArray;
    }
   
    private function getcode($assetid)
    {
      return substr(md5($assetid),8,16).'1905';
    }
    
    private function setCategory(){
      $mongo = $mongo = $this->getMondongo();
      $wikiRepository = $mongo->getRepository('wiki');
      $category = $wikiRepository->getCategory();
      return $this->category = $category;
    }
    
    /*
     * 返回wiki的类型
    * @param array $tags
    * @return string $tag
    * author lifucang
    */
    public function getTag($tags,$arr){
      $tmpstr = array();
      foreach($tags as $tag){
        if(!array_search($tag, $arr)){
          $tmpstr[] = $tag;
        }
      }
      return implode(",",$tmpstr);
    }
    
    /**
     * 获取动态缩略图
     * @param <string> $key
     * @param <int> $width
     * @param <int> $height
     */
    public function thumb_url($key=null, $width=75, $height=110) {
      if (empty($key)) return '';
    
      return sprintf('http://172.31.139.17:81/thumb/'.'%s/%s/%s', $width, $height, $key);
    }
}

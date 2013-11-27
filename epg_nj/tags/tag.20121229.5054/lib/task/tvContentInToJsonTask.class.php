<?php
/**
 * @tobo   此为临时任务，用于读取CMS方提供的不规则文本内容。需要按照所提供的文本格式进行程序微调 ，入库Content_Import
 * @author gaobo
 * @time   2012-12-13
 */
class tvContentInToJsonTask extends sfMondongoTask
{
    var $category;
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
            // add your own options here
        ));

        $this->namespace        = 'tv';
        $this->name             = 'ContentInToJson';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [tv:ContentInToJson|INFO] task does things.
Call it with:

[php symfony tv:ContentInToJson|INFO]
EOF;

        $this->acceptTypes = array("program","series");
    }

    protected function execute($arguments = array(), $options = array())
    { 
        $mongo = $this->getMondongo();
        $import_repo = $mongo->getRepository("ContentImport");      
		    $wiki_repository = $mongo->getRepository('wiki');
		    
        $i = 0;
        
        $fp = fopen('/www/newepg/tmp/json/importToJson.txt', 'w');
        $str = '[';
        if($fp){
          $imObj = $import_repo->find(array('query'=>array('wiki_id'=>array('$exists'=>true))));
          foreach ($imObj as $val){
            $wiki_id = $val->getWikiId();
            $nodeArray = array();
            $WikiRepository = $mongo->getRepository('Wiki');
            $wiki = $WikiRepository->findOneById(new MongoId($wiki_id));
            if($wiki)
            {
              $nodeArray['asset_id'] = $val->getFromId();
              $nodeArray['media'] = array(
                  'wiki_id'    => (string)$wiki->getId(),
                  'title'   => $wiki->getTitle(),
              );
              $nodeArray = $this->getOneWikiVideoSource($wiki, 0, $nodeArray);
              $str .= (string)json_encode($nodeArray,true).",";
              $i++;
            }
          }
          echo 'total:'.$i,"\n";
          $str  = substr($str,0,-1);
          $str .= ']';
          fwrite($fp, $str);
        }
        fclose($fp);
        
        
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
      $videos = $wiki->getVideos();
      $refererSource = array('youku'=>"优酷",'qiyi'=>'奇艺','sohu'=>'搜狐','sina'=>'新浪','cms'=>'cms','tps'=>'tps');
      $source = '';
      $prefer = "奇艺"; //优选片源
      if ($videos != NULL) {
        foreach ($videos as $video) {
          $source = $source ? $source.",".$refererSource[$video->getReferer()]: $refererSource[$video->getReferer()];
        }
      }
      
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
    
      return sprintf(sfConfig::get('app_static_url').'thumb/'.'%s/%s/%s', $width, $height, $key);
    }
}

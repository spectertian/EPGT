<?php
/**
 *  @todo  : 统计video表中不能播放的视频
 *  @author: lifucang
 */
class tvVideoPlayCountTask extends sfMondongoTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','stba'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
      new sfCommandOption('time', null, sfCommandOption::PARAMETER_OPTIONAL, 'time'),
    ));

    $this->namespace        = 'tv';
    $this->name             = 'VideoPlayCount';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tv:VideoPlayCount|INFO] task does things.
Call it with:

  [php symfony tv:VideoPlayCount|INFO]
EOF;
  }

    protected function execute($arguments = array(), $options = array())
    {
        $memcache = tvCache::getInstance();
        $key = "videoPlayCount";
        $mongo = $this->getMondongo();
        $video_repo = $mongo->getRepository('Video');

        $i = 0;
        $num = 0;
        $video_count = $video_repo->count(array());
        $id='519b02ab7b5fbd8f20000000';
        $datas=array();
        while ($i < $video_count) 
        {
            $videos = $video_repo->find(array("query"=>array("_id"=>array('$gt'=>new MongoId($id))), "limit" => 50,"sort"=>array('_id'=>1)));
            foreach ($videos as $video) {
                $id = (string)$video->getId();
                $pageId=$video->getPageId();
                $url=$this->getYangVideoUrl($pageId);
                if(!$url){
                    $config=$video ->getConfig();
                    $datas[] = array(
                        'id' => $id,
                        'title' => $video ->getTitle(),
                        'asset_id' => $config['asset_id'],
                        'page_id' => $video ->getPageId()
                    );
                    $num++;
                }
            }
            $i = $i + 50;
            echo $i,'*****',"\n";
            sleep(1); 
        }       
        if(isset($options['time'])){
            $memTime = intval($options['time']);
        }else{
            $memTime = 86400;
        }
        $memcache->set($key,$datas,$memTime);  //1小时
        echo "No Play:$num, finished!\n";
    }
    private function getYangVideoUrl($contented)
	{
        $clientid = '01006608470056014';
        $backurl = '';
        $playtype = 0;
        if(!$contented) {
            return null;    
        }        
        $submit_url = sfConfig::get("app_cpgPortal_url")."?clientid=".$clientid."&playtype=".$playtype."&startpos=0&devicetype=6&rate=0&hasqueryfee=y&contented=".$contented."&backurl=".urlencode($backurl); 
        $curl = curl_init();  
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC ) ; 
        curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($curl, CURLOPT_USERPWD, sfConfig::get("app_cpgPortal_username").":".sfConfig::get("app_cpgPortal_password")); 
        curl_setopt($curl, CURLOPT_URL, $submit_url); 
        $data = curl_exec($curl);
        curl_close($curl); 
        if(!$data) {
            return '';
        }
        $xmls = @simplexml_load_string($data);        
        if(isset($xmls->url)) {
            $url=strval($xmls->url);
            if($url=='null'){
                return null;
            }else{
                return $url;
            }
        }else{
            return null;
        }
	}
}

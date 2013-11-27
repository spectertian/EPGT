<?php
/**
 *  @todo  : 查找video中config.asset_id相同的
 *  @author: lifucang
 */
class tvSearchVideoAssetTask extends sfMondongoTask
{
  protected function configure()
  {

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','stba'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
      new sfCommandOption('skip', null, sfCommandOption::PARAMETER_OPTIONAL, 'skip'),
      new sfCommandOption('limit', null, sfCommandOption::PARAMETER_OPTIONAL, 'limit') 
    ));

    $this->namespace        = 'tv';
    $this->name             = 'SearchVideoAsset';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tv:SearchVideoAsset|INFO] task does things.
Call it with:

  [php symfony tv:SearchVideoAsset|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
	    set_time_limit(0); 
        $mongo = $this->getMondongo();
        $video_repo = $mongo->getRepository("Video");

        $query=array();
        $count = $video_repo->count();
        $i = 0;
        if (isset($options['skip'])) {
            $i=$options['skip'];
        }
        if (isset($options['limit'])) {
            $count=$options['limit'];
        }       
        //echo "count:",$count-$i,"\n";
        sleep(1);
        $arr_title=array();
        while ($i < $count) 
        {
            $videos = $video_repo->find(array("query"=>$query,"sort" => array("_id" => 1), "skip" => $i, "limit" => 200));
            foreach ($videos as $video) 
            {
                $config=$video->getConfig();
                $asset_id=$config['asset_id'];
                $videoexists=$video_repo->findOne(array("query"=>array('config.asset_id'=>$asset_id,'_id'=>array('$ne'=>$video->getId()))));
                if($videoexists){
                    $arr_title[]=$video->getTitle();
                }
            }
            $i = $i + 200;
            echo $i,',';
            sleep(1);
        }
        $arr_title=array_unique($arr_title);
        echo "repeat:",count($arr_title),"\n";
        sleep(2);
        foreach($arr_title as $value){
            echo iconv('utf-8','gbk',$value),"\n";
        }
  }
}

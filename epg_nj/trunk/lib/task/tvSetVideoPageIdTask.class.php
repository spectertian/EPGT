<?php
/**
 *  @todo  : 根据config.asset_id写page_id，只是临时用
 *  @author: lifucang 2013-5-23
 */
class tvSetVideoPageIdTask extends sfMondongoTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','stba'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
      // add your own options here
    ));

    $this->namespace        = 'tv';
    $this->name             = 'SetVideoPageId';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tv:SetVideoPageId|INFO] task does things.
Call it with:

  [php symfony tv:SetVideoPageId|INFO]
EOF;
  }

    protected function execute($arguments = array(), $options = array())
    {
        $query=array();
        $mongo = $this->getMondongo();
        $video_repo = $mongo->getRepository("Video"); 
        $count = $video_repo->count($query);
        echo $count,"\n";
        $limit = 200; 
        $i = 0;
        while ($i < $count) 
        {
            $videos = $video_repo->find(array("query"=>$query,"sort" => array("_id"=>1),"skip" => $i,"limit" => $limit));
            if($videos){ 
                foreach($videos as $video){
                    $config=$video->getConfig();
                    $asset_id=$config['asset_id'];
                    $video->setPageId($asset_id);
                    $video->save();
                }
            }
            $i = $i + $limit;
            echo $i,'*************************************',"\n"; 
            sleep(1);  
        } 
        echo "finished!\n";      
    }
}

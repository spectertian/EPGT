<?php
/**
 *  @todo  : 根据video表更新playList中的数据，未用，还未完善
 *  @author: lifucang
 */
class tvVideoPlayListUpdateTask extends sfMondongoTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','stba'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
    ));

    $this->namespace        = 'tv';
    $this->name             = 'VideoPlayListUpdate';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tv:VideoPlayListUpdate|INFO] task does things.
Call it with:

  [php symfony tv:VideoPlayListUpdate|INFO]
EOF;
  }

    protected function execute($arguments = array(), $options = array())
    {
        //还没完善
        $mongo = $this->getMondongo();
        $video_repo = $mongo->getRepository("Video");  
        $videoPlaylist_repo = $mongo->getRepository("VideoPlaylist");  
        $wiki_repo = $mongo->getRepository("Wiki");  
        $wikis=$wiki_repo->find();
        echo count($wikis),"\n";
        $i=0;
        $video_num=0;
        foreach($wikis as $wiki){
            $has_video=$wiki->getVideoCount();
            $wiki->setHasVideo($has_video);
            $wiki->save();
            if($has_video>0)
                $video_num++;
            $i++;
            if($i%100==0){
                echo "$i\n";
            }
        }
        echo "has_video > 0:$video_num, finished!\n";
    }
}

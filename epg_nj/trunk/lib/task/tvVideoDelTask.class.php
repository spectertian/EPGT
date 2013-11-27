<?php
/**
 *  @todo  : 删除video中url为空的记录，同时把wiki表中的has_video设为0，同时删除video_playlist 相关记录
 *  @author: lifucang
 */
class tvVideoDelTask extends sfMondongoTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','stba'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
    ));

    $this->namespace        = 'tv';
    $this->name             = 'VideoDel';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tv:VideoDel|INFO] task does things.
Call it with:

  [php symfony tv:VideoDel|INFO]
EOF;
  }

    protected function execute($arguments = array(), $options = array())
    {
        $mongo = $this->getMondongo();
        $video_repo = $mongo->getRepository("Video");  
        $videoPlaylist_repo = $mongo->getRepository("VideoPlaylist");  
        $wiki_repo = $mongo->getRepository("Wiki");  
        //$videos=$video_repo->find(array('query'=>array('url'=>'')));
        //$videos=$video_repo->find(array('query'=>array('referer'=>'CP1N02A08_003')));
        //$videos=$video_repo->find(array('query'=>array('title'=>new MongoRegex("/.*隋唐演义.*/i"))));
        
        
        //$videos=$video_repo->find(array('query'=>array('page_id'=>array('$exists'=>0))));    这种的播放不出来，所以删掉
        $videos=$video_repo->find(array('query'=>array('_id'=>array('$lte'=>new MongoId("519af5c97b5fbd715b0001c3")))));
        echo count($videos),"\n";
        $i=0;
        foreach($videos as $video){
            //$wiki_id=$video->getWikiId();
            //$wiki=$wiki_repo->findOneByID(new MongoId($wiki_id));
            //$wiki->setHasVideo(false);
            //$wiki->save();
            $video_playlist_id=$video->getVideoPlaylistId();
            if($video_playlist_id){
                $videoPlaylist=$videoPlaylist_repo->findOne(array('query'=>array('_id'=>new MongoId($video_playlist_id))));
                if($videoPlaylist)
                    $videoPlaylist->delete();
            }
            $video->delete();
            $i++;
            if($i%100==0){
                echo "$i\n";
            }
        }
        echo "delete:$i, finished!\n";
    }
}

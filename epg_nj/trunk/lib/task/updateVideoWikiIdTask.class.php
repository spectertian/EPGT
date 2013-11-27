<?php
/**
 *  @todo  : 更新video的wiki_id
 *  @author: lifucang 2013-06-08
 */
class updateVideoWikiIdTask extends sfMondongoTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','stba'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
      new sfCommandOption('wikiId', null, sfCommandOption::PARAMETER_OPTIONAL, 'wikiId'),
      new sfCommandOption('wikiIdNew', null, sfCommandOption::PARAMETER_OPTIONAL, 'wikiIdNew'),
    ));

    $this->namespace        = '';
    $this->name             = 'updateVideoWikiId';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [updateVideoWikiId|INFO] task does things.
Call it with:

  [php symfony updateVideoWikiId|INFO]
EOF;
   //symfony updateVideoWikiId --wikiId=4f1d4b62cf6357d3140000ff --wikiIdNew=519de1136dbde1a647000002
  }

  protected function execute($arguments = array(), $options = array())
  {
        $wikiId=$options['wikiId'];
        $wikiIdNew=$options['wikiIdNew'];
        
        $mongo = $this->getMondongo();
        $wiki_repo = $mongo->getRepository("Wiki");
        $video_repo = $mongo->getRepository("Video");  
        $videoPlaylist_repo = $mongo->getRepository("VideoPlaylist");  
        $import_repo = $mongo->getRepository("ContentImport");  
        
        //替换video中的相关wiki
        $videos=$video_repo->find(array('query'=>array('wiki_id'=>$wikiId)));
        foreach($videos as $video){
            $video->setWikiId($wikiIdNew);
            $video->save();
        }
        //替换videoPlayList中的相关wiki
        $videoPlayLists=$videoPlaylist_repo->find(array('query'=>array('wiki_id'=>$wikiId)));
        foreach($videoPlayLists as $videoPlayList){
            $videoPlayList->setWikiId($wikiIdNew);
            $videoPlayList->save();
        }
        //替换content_import中的相关wiki
        $imports=$import_repo->find(array('query'=>array('wiki_id'=>$wikiId)));
        foreach($imports as $import){
            $import->setWikiId($wikiIdNew);
            $import->save();
        }
        
        //原有wiki的has_video设为0
        $wiki=$wiki_repo->findOne(array('query'=>array('_id'=>new MongoId($wikiId))));
        $wiki->setHasVideo(0);
        $wiki->save();
        echo 'finished';
  }
}

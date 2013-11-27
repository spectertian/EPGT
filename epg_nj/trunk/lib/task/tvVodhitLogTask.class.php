<?php
/**
 *  @todo  : 每月1日执行一次（统计上月的点播情况），记录影片点播情况
 *  @author: lifucang 2013-08-21
 */
class tvVodhitLogTask extends sfMondongoTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','stba'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
    ));

    $this->namespace        = 'tv';
    $this->name             = 'VodhitLog';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tv:VodhitLog|INFO] task does things.
Call it with:

  [php symfony tv:VodhitLog|INFO]
EOF;
  }

    protected function execute($arguments = array(), $options = array())
    {
        $date=date('Y-m',strtotime("-1 days"));  //每月1日减1天是上月月底
        $mongo = $this->getMondongo();
        $wiki_repo = $mongo->getRepository('Wiki');
        
        $querya = array('watched_num'=>array('$gt'=>0));
        $wikis=$wiki_repo->find(array('query'=>$querya));
        foreach($wikis as $wiki){
            $wiki_id=(string)$wiki->getId();
            $wiki_title=(string)$wiki->getTitle();
            $watchedNum=$wiki->getWatchedNum();
            
            $vodhitLog=new VodhitLog();
            $vodhitLog->setDate($date);
            $vodhitLog->setWikiId($wiki_id);
            $vodhitLog->setTitle($wiki_title);
            $vodhitLog->setHits($watchedNum);
            $vodhitLog->save();
            $wiki->setWatchedNum(0);
            $wiki->save();
        }
        echo "finished\n";
    }
}

<?php
/**
 *  @todo  : 每1小时执行一次，记录直播点击数量
 *  @author: lifucang 2013-08-01
 */
class tvLiveLogTask extends sfMondongoTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','stba'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
    ));

    $this->namespace        = 'tv';
    $this->name             = 'LiveLog';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tv:LiveLog|INFO] task does things.
Call it with:

  [php symfony tv:LiveLog|INFO]
EOF;
  }

    protected function execute($arguments = array(), $options = array())
    {
        $memcache = tvCache::getInstance(); 
        $mem_key = 'liveHit';
        $hits=$memcache->get($mem_key);
        if(!$hits){
            $hits = 0;
        }
        $mongo = $this->getMondongo();
        $liveLog=new LiveLog();
        $liveLog->setHits($hits);
        $liveLog->setDate(date('Y-m-d'));
        $liveLog->save();

        $memcache->set($mem_key,0,60);
        echo "finished\n";
    }
}

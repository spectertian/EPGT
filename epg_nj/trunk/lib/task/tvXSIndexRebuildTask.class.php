<?php

class tvXSIndexRebuildTask extends sfMondongoTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'stba'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
      new sfCommandOption('update', null, sfCommandOption::PARAMETER_OPTIONAL, 'update')
    ));

    $this->namespace        = 'tv';
    $this->name             = 'XSIndexRebuild';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tv:XSIndexRebuild|INFO] task does things.
Call it with:

  [php symfony tv:XSIndexRebuild|INFO]
EOF;
   //symfony tv:XSIndexRebuild --update=day/week/month/year/video
   //注意:因rebuildXunSearchDocument开启了$index->beginRebuild(); 所以加了--update=day后索引库里只有当天的索引，以前的都会被删除替换
  }

    protected function execute($arguments = array(), $options = array())
    {
        $mongo = $this->getMondongo();
        $query=array();
        if (isset($options['update'])) {
            switch($options['update']){
                case 'day':
                    $query=array("created_at" => array('$gte' => new MongoDate(mktime(0, 0, 0, date('m'), date('d'), date('Y')))));
                    break;
                case 'week':
                    $query=array("created_at" => array('$gte' => new MongoDate(strtotime('-7 days'))));
                    break;
                case 'month':
                    $query=array("created_at" => array('$gte' => new MongoDate(strtotime('-1 month'))));
                    break;      
                case 'year':
                    $query=array("created_at" => array('$gte' => new MongoDate(strtotime('-1 year'))));
                    break;                 
                case 'video':
                    $query=array('has_video'=>array('$gt'=>0));
                    break;
                default:
                    $query=array();
            }    
        }
        $wiki_repo = $mongo->getRepository("Wiki");
        $wiki_repo->rebuildXunSearchDocument($query);
    }
}


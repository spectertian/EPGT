<?php
/**
 *  @todo  : 查找wiki中title值相同且model也相同的
 *  @author: lifucang
 */
class tvSearchWikiTitleTask extends sfMondongoTask
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
    $this->name             = 'SearchWikiTitle';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tv:SearchWikiTitle|INFO] task does things.
Call it with:

  [php symfony tv:SearchWikiTitle|INFO]
EOF;
    //symfony tv:SearchWikiTitle --skip=10000 --limit=20000
  }

  protected function execute($arguments = array(), $options = array())
  {
	    set_time_limit(0); 
        $mongo = $this->getMondongo();
        $wiki_repo = $mongo->getRepository("WikiTemp");

        $query=array();
        $wiki_count = $wiki_repo->count();
        $i = 0;
        if (isset($options['skip'])) {
            $i=$options['skip'];
        }
        if (isset($options['limit'])) {
            $wiki_count=$options['limit'];
        }       
        //echo "count:",$wiki_count-$i,"\n";
        sleep(1);
        $arr_title=array();
        while ($i < $wiki_count) 
        {
            $wikis = $wiki_repo->find(array("query"=>$query,"sort" => array("_id" => 1), "skip" => $i, "limit" => 200));
            foreach ($wikis as $wiki) 
            {
                $title=$wiki->getTitle();
                $model=$wiki->getModel();
                $wikititle=$wiki_repo->findOne(array("query"=>array('title'=>$title,'model'=>$model,'_id'=>array('$ne'=>$wiki->getId()))));
                if($wikititle){
                    $arr_title[]=$title;
                }
            }
            $i = $i + 200;
            //echo $i,',';
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

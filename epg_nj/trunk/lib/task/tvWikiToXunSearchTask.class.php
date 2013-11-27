<?php

class tvWikiToXunSearchTask extends sfMondongoTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'stba'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
      new sfCommandOption('wiki_id', null, sfCommandOption::PARAMETER_OPTIONAL, 'The connection name'),
      new sfCommandOption('day', null, sfCommandOption::PARAMETER_OPTIONAL, 'day'),
      new sfCommandOption('update', null, sfCommandOption::PARAMETER_OPTIONAL, 'update')
    ));

    $this->namespace        = 'tv';
    $this->name             = 'wikiToXunSearch';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tv:wikiToXunSearch|INFO] task does things.
Call it with:

  [php symfony tv:wikiToXunSearch|INFO]
EOF;
  //symfony tv:wikiToXunSearch --day=date           //创建或更新时间大于等于当天的
  //symfony tv:wikiToXunSearch --day=2012-12-13     //创建或更新时间大于等于某天的
  //symfony tv:wikiToXunSearch --update=video       //只更新有video的
  //symfony tv:wikiToXunSearch --update=date        //只更新当天的
  //symfony tv:wikiToXunSearch --update=2013-05-11  //更新大于5月11日
  //symfony tv:wikiToXunSearch --wiki_id=4edc58cdedcd88c50700930f //更新单条wiki
  }

    protected function execute($arguments = array(), $options = array())
    {
        $mongo = $this->getMondongo();
        $wiki_repo = $mongo->getRepository("Wiki");
    	if (isset($options['wiki_id'])) 
    	{
            $wiki_id = $options['wiki_id'];
            $wiki = $wiki_repo->findOneById(new MongoId($wiki_id));
            $wiki->updateXunSearchDocument();
        }else{
            if (isset($options['update'])) {
                if($options['update']=='video'){
                    $query=array('has_video'=>array('$gt'=>0));
                }elseif($options['update']=='date'){
                    //更新时间大于等于当天的
                    $query=array('updated_at'=>array('$gte' => new MongoDate(strtotime(date("Y-m-d 00:00:00")))));
                }else{
                    //更新时间大于等于某一天的
                    $query=array('updated_at'=>array('$gte' => new MongoDate(strtotime($options['update']))));
                }
            }elseif (isset($options['day'])) {
                if($options['day']=='date'){
                    $query=array('$or' => array(
                                        array("created_at" => array('$gte' => new MongoDate(strtotime(date("Y-m-d 00:00:00"))))),
                                        array("updated_at" => array('$gte' => new MongoDate(strtotime(date("Y-m-d 00:00:00"))))),
                                  ));
                }else{
                    $query=array('$or' => array(
                                        array("created_at" => array('$gte' => new MongoDate(strtotime($options['day'])))),
                                        array("updated_at" => array('$gte' => new MongoDate(strtotime($options['day'])))),
                                  ));
                }
                
            }else{
                $query=array();
            }
            $wiki_count = $wiki_repo->count($query);
            echo "count:",$wiki_count,"\n";
            sleep(1);
            $i = 0;
            while ($i < $wiki_count) 
            {
                //$wikis = $wiki_repo->find(array("query"=>$query,"sort" => array("_id" => 1), "skip" => $i, "limit" => 50));
                $wikis = $wiki_repo->find(array("query"=>$query,"sort" => array("_id" => 1),"skip" => $i,  "limit" => 50));
                //$wikis = $wiki_repo->find(array("query"=>array("model"=>"television"),"sort" => array("created_at" => 1), "skip" => $i, "limit" => 50));
                foreach ($wikis as $wiki) 
                {
                    $wiki->updateXunSearchDocument();
                    echo $wiki->getTitle(),"\n";
                }
                $i = $i + 50;
                echo $i,'*************************************',"\n";
                sleep(1);
            }  
        } 
        
/*
    	global $argv;
    	if (isset($options['wiki_id'])) 
    	{
            $wiki_id = $options['wiki_id'];
            $wiki = $wiki_repo->findOneById(new MongoId($wiki_id));
            $wiki->updateXunSearchDocument();
        }else{
    		$cmd = implode(" ", $argv);
    //		$wiki_date = new MongoDate(1293516989);//2010-12-28 
    		$wiki_date = new MongoDate(1287676800);
            $i = 0;
            while (true) 
            {
    			$wikis = $wiki_repo->find(array("query" => array("created_at" => array('$gt' => $wiki_date)), "sort" => array("created_at" => 1), "limit" => 100));
    			if (!$wikis) break;
    			foreach ($wikis as $wiki) 
    			{
    				printf("%s\n", $wiki->getTitle());
    				exec("php ".$cmd." --wiki_id=".$wiki->getId());
    				$wiki_date = new MongoDate($wiki->getCreatedAt()->getTimestamp());
    				unset($wiki);
    			}	
                $i = $i + 100;
                echo $i,'*************************************',"\n";                	
    		}
        }
*/
        echo "finished! \n";
        
	}
}
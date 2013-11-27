<?php
/**
 * 给把wiki表中数据导入到tosou_match_wiki 表中
 * 执行一次
 * @author tianzhongsheng
 * @time 2013-08-14 16:31:00
 */
class tvWikiToTvsouMatchTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
        ));

        $this->namespace        = 'tv';
        $this->name             = 'WikiToTvsouMatch';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [tv:WikiToTvsouMatch|INFO] task does things.
Call it with:

  [php tv:WikiToTvsouMatch|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
    	
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        $wiki_repository = $this->getMondongo()->getRepository('Wiki');

     	$options['query'] = array('tvsou_id'=>array('$ne'=>NULL));
        $options['fields'] = array('id','tvsou_id','title','created_at');
        $options['limit'] = 1000;
		$options['sort'] = array('$natural' => -1);
		$wikiCount = $wiki_repository->count($options['query']);
        $s = 0;
        $k = 0;
        $w = 0;
        $author = array('user_id'=>'system','user_name'=>'system');
        $tvsouArray = array();
		$noImport = array();
		$updateTvsouId = array();
		$wikiIdArray = array();
        for($i=0; $i<=$wikiCount; $i=$i+1000)
        {
			$options['skip'] = $i;
			$wikiRes = $wiki_repository->find($options);
                
			foreach($wikiRes as $obj)
			{
				$tvsouId = $obj->getTvsouId();
				echo "tvsou_id: {$tvsouId}\n";
				if(empty($tvsouId))	//去除空格的tvsou_id
				{
					echo "tvsou_id: {$tvsouId}不存在\n";
					continue;
				}
				$wikiId = $obj->getId();
				if(in_array($tvsouId,$tvsouArray))
				{
					$timss = $obj->getCreatedAt()->format("Y-m-d H:i:s");
					$timss= strtotime($timss);
					$wiki_re = $wiki_repository->findOneById(new MongoId($wikiIdArray[$tvsouId]));
					$difTime = $wiki_re->getCreatedAt()->format("Y-m-d H:i:s");
					$difTime = strtotime($difTime);
					echo $timss,"\t".$difTime."\n";
					if($timss > $difTime )
					{
						echo $obj->getCreatedAt()->format("Y-m-d H:i:s")."\t";
						$tvsouMathWiki_repository = $this->getMondongo()->getRepository('TvsouMatchWiki');
						$option_res['query'] = array(
									'tvsou_id' => $tvsouId,
							);
						$wikiTitle = $obj->getTitle();
						$tvsouMathWiki_re = $tvsouMathWiki_repository->findOne($option_res);
						$tvsouMathWiki_re->setWikiId($wikiId);
						$tvsouMathWiki_re->setWikiTitle($wikiTitle);
						$tvsouMathWiki_re->setAuthor($author);
						$tvsouMathWiki_re->save();
						$updateTvsouId[] = $tvsouId;
						$wikiIdArray[$tvsouId] = $wikiId ;
						$w++;
						echo "更新的tvsou_id: {$tvsouId}\t--wiki_id: {$wikiId}\n";
						continue;
					}else 
					{
						$k++;
						$noImport[] = $tvsouId;
						echo "重复的tvsou_id: {$tvsouId}\n";
						continue;
					}
					
				}
				
				$wikiTitle = $obj->getTitle();
				$tvsouMatchWikis = new TvsouMatchWiki();
				$tvsouMatchWikis->setTvsouId($tvsouId);
				$tvsouTitle = Common::getTvsouTitleByID($tvsouId);
				$tvsouMatchWikis->setTvsouTitle($tvsouTitle);
				$tvsouMatchWikis->setWikiId($wikiId);
				$tvsouMatchWikis->setWikiTitle($wikiTitle);
				$tvsouMatchWikis->setAuthor($author);
				$tvsouMatchWikis->save();
				$tvsouArray[] = $tvsouId;
				$wikiIdArray[$tvsouId] = $wikiId ;
				$s++;
				echo $tvsouId."\t已入库-----{$s}\t-tvou_title-----{$tvsouTitle}\t更新的-----{$w}\t未能能入库-----{$k}\n";
			}
        }

		echo "未能入库的tvsou_id: ".join(',',$noImport)." --success\n";
		echo "更新的的tvsou_id: ".join(',',$updateTvsouId)." --success\n";
		
    }

}

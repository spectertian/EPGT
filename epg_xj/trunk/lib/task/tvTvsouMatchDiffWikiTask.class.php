<?php
/**
 * 对比tvsou_match_wiki和tv_name 和wiki_title
 * 执行一次
 * @author tianzhongsheng
 * @time 2013-08-27 15:54:00
 */
class tvTvsouMatchDiffWikiTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
        ));

        $this->namespace        = 'tv';
        $this->name             = 'TvsouMatchDiffWiki';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [tv:TvsouMatchDiffWiki|INFO] task does things.
Call it with:

  [php tv:TvsouMatchDiffWiki|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
    	
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        $tvsouMathWiki_repository = $this->getMondongo()->getRepository('TvsouMatchWiki');
		$options['query'] = array();
        $options['fields'] = array('tvsou_title','wiki_title');
        $options['limit'] = 1000;
		$options['sort'] = array('$natural' => -1);
		$wikiCount = $tvsouMathWiki_repository->count($options['query']);
        $s = 0;
        $k = 0;
        $author = array('user_id'=>'system','user_name'=>'system');
        $tvsouArray = array();
		$noImport = array();
		$updateTvsouId = array();
		$wikiIdArray = array();
        for($i=0; $i<=$wikiCount; $i=$i+1000)
        {
			$options['skip'] = $i;
			$wikiRes = $tvsouMathWiki_repository->find($options);
                
			foreach($wikiRes as $obj)
			{
				if($obj->getTvsouTitle() == $obj->getWikiTitle())
				{
					$s++;
					$obj->setCompare(true);
					$obj->save();
					echo "相同的: ".$obj->getTvsouTitle()."\t".$obj->getWikiTitle()."\t".$s." 个--\n";
					
				}else{
					
					$k++;
					$obj->setCompare(false);
					$obj->save();
					echo "不相同的: ".$obj->getTvsouTitle()."\t".$obj->getWikiTitle()."\t".$s.$k." 个--\n";

				}
			}
        }

		echo "相同的: ".$s." 个--success\n";
		echo "不相同的: ".$k." 个--success\n";
		
    }

}

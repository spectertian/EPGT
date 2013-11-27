<?php

class tvWikiToXunSearchTask extends sfMondongoTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
      new sfCommandOption('wiki_id', null, sfCommandOption::PARAMETER_OPTIONAL, 'The connection name'),
      // add your own options here
    ));

    $this->namespace        = 'tv';
    $this->name             = 'wikiToXunSearch';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tv:wikiToXunSearch|INFO] task does things.
Call it with:

  [php symfony tv:wikiToXunSearch|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $mongo = $this->getMondongo();
    $wiki_repo = $mongo->getRepository("Wiki");
   /* $wiki_count = $wiki_repo->count();

    $i = 0;
    while ($i < $wiki_count) 
    {
        $wikis = $wiki_repo->find(array("sort" => array("created_at" => 1), "skip" => $i, "limit" => 50));
        foreach ($wikis as $wiki) 
        {
            $wiki->updateXunSearchDocument();
        }
        $i = $i + 50;
    }*/
	global $argv;
	if (isset($options['wiki_id'])) 
	{
        $wiki_id = $options['wiki_id'];
        $wiki = $wiki_repo->findOneById(new MongoId($wiki_id));
        $wiki->updateXunSearchDocument();
    }
	else
	{
		$cmd = implode(" ", $argv);
//		$wiki_date = new MongoDate(1293516989);//2010-12-28 
		$wiki_date = new MongoDate(1287676800);
        while (true) 
        {
			$wikis = $wiki_repo->find(array("query" => array("created_at" => array('$gt' => $wiki_date)), "sort" => array("created_at" => 1), "limit" => 100));
			if (!$wikis) break;
			foreach ($wikis as $wiki) 
			{
				printf("%s\n", $wiki->getTitle());
				exec("php ".$cmd." --wiki_id=".$wiki->getId());echo "\n";
				$wiki_date = new MongoDate($wiki->getCreatedAt()->getTimestamp());
				unset($wiki);
			}		
		}
  }
	}
}
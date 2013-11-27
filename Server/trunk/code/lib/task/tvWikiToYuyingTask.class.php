<?php

class tvWikiToYuyingTask extends sfMondongoTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
      // add your own options here
    ));

    $this->namespace        = 'tv';
    $this->name             = 'wikiToYuying';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tv:wikiToYuying|INFO] task does things.
Call it with:

  [php symfony tv:wikiToYuying|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $mongo = $this->getMondongo();
    $wiki_repo = $mongo->getRepository("Wiki");
    $wiki_count = $wiki_repo->count();
	
	$wikis = $wiki_repo->find(array("sort" => array("created_at" => 1)));
	foreach ($wikis as $wiki) 
	{
		file_put_contents("yuying--.txt",$wiki->getSlug()."\n",FILE_APPEND);
	}
	echo "finished!";
	exit;

	/*
	$content = '';
    $i = 17400;
	echo "$wiki_count \n";
    while ($i < $wiki_count) 
    {
        $wikis = $wiki_repo->find(array("sort" => array("created_at" => 1), "skip" => $i, "limit" => 50));
        foreach ($wikis as $wiki) 
        {
			//$content .= $wiki->getSlug()."\n";		  
			file_put_contents("yuying.txt",$wiki->getSlug()."\n",FILE_APPEND);
        }
        $i = $i + 50;
    }*/
  }
}

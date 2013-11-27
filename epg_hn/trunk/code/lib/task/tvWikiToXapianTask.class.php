<?php

class tvWikiToXapianTask extends sfMondongoTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
      new sfCommandOption('wiki_id', null, sfCommandOption::PARAMETER_OPTIONAL, 'The connection name'),
      // add your own options here
    ));

    $this->namespace        = 'tv';
    $this->name             = 'wikiToXapian';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tv:wikiToXapian|INFO] task does things.
Call it with:

  [php symfony tv:wikiToXapian|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    global $argv;

    $mongo = $this->getMondongo();
    $wiki_repo = $mongo->getRepository("Wiki");

    if (isset($options['wiki_id'])) {
        $wiki_id = $options['wiki_id'];
        $wiki = $wiki_repo->findOneById(new MongoId($wiki_id));
        $wiki->updateXapianDocument();
    } else {
        $cmd = implode(" ", $argv);

        //$wiki_date = new MongoDate(0);
        $wiki_date = new MongoDate(1293516989);
        while (true) {
            $wikis = $wiki_repo->find(array("query" => array("created_at" => array('$gt' => $wiki_date)), "sort" => array("created_at" => 1), "limit" => 100));
            if (!$wikis) break;
            foreach ($wikis as $wiki) {
                printf("%s\n", $wiki->getTitle());
                exec($cmd." --wiki_id=".$wiki->getId());
                $wiki_date = new MongoDate($wiki->getCreatedAt()->getTimestamp());
                unset($wiki);
            }
        }
    }
    /*var_dump($arguments);
    var_dump($options);
    var_dump($argv);*/
    return true;

    /*$mongo = $this->getMondongo();
    $wiki_repo = $mongo->getRepository("Wiki");

    $wiki_count = $wiki_repo->count();

    //$wiki_date = new MongoDate(0);
    $wiki_date = new MongoDate(1293516989);
    $i = 1;
    while (true) {
        printf("Page Start %d\n", $i);
        $wikis = $wiki_repo->find(array("query" => array("created_at" => array('$gt' => $wiki_date)), "sort" => array("created_at" => 1), "limit" => 100));
        printf("Mongo OK\n");
        //$wikis = $wiki_repo->find();
        if (!$wikis) break;
        foreach ($wikis as $wiki) {
            printf("%s\n", $wiki->getTitle());
            echo $wiki->getId()."\n";
            $wiki->updateXapianDocument();
            echo $wiki->getCreatedAt()->getTimestamp()."\n";
            $wiki_date = new MongoDate($wiki->getCreatedAt()->getTimestamp());
            unset($wiki);
        }
        printf("Page End %d\n", $i);
        $i++;
    }*/
  }
}

<?php

class programDataToMongodbTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'master'),
      // add your own options here
    ));

    $this->namespace        = 'tv';
    $this->name             = 'programDataToMongodb';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [programDataToMongodb|INFO] task does things.
Call it with:

  [php symfony programDataToMongodb|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
      
    // add your code here
    $programs = Doctrine::getTable('Program')->createQuery()
                ->orderBy('created_at DESC')
                ->limit(1000)
                ->execute();
    
    foreach($programs as $program) {
        $data = array(
            'program_id' => $program->getId(),
            'name' => $program->getName(),
            'publish' => $program->getPublish(),
            'wiki_id' => $program->getWikiId(),
            'channel_code' => $program->getChannelCode(),
            'start_time' => $program->getDate() . ' ' . $program->getTime(),
            'end_time' => '',
            'tags' => $program->getTagsName(),
            'date' => $program->getDate(),
            'time' => $program->getTime(),
            'created_at' => $program->getCreatedAt(),
            'updated_at' => $program->getUpdatedAt()
        );
        
        $mongo = MzMongoDB::getMongoDB();;
        $mongo_program = $mongo->selectCollection('program');
        $mongo_program->insert($data);
    }
    
  }
}

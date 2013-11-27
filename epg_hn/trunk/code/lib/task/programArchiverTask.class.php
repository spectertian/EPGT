<?php

class programArchiverTask extends sfMondongoTask
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
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
      // add your own options here
    ));

    $this->namespace        = 'tv';
    $this->name             = 'programArchiver';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [programArchiver|INFO] task does things.
Call it with:

  [php symfony programArchiver|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    // add your code here
    $now_week = date("N",time());
     //上星期日 日期
    $per_week_sun= date("Y-m-d",time()-$now_week*86400);
    $mongo = $this->getMondongo();
    $program_repository = $mongo->getRepository('Program');
    $programArchiver_repository = $mongo->getRepository('ProgramArchiver');
    while ($programs = $program_repository->getNeedPrograms($per_week_sun,20)){
        foreach ($programs as $key=>$program){
            $is_exit = $programArchiver_repository->getProframArchiver($program->getDate(),$program->getTime(),$program->getChannelCode());
            if(!$is_exit){
                $programArchiver = new ProgramArchiver();
                $programArchiver->setName($program->getName());
                $programArchiver->setChannelCode($program->getChannelCode());
                $programArchiver->setStartTime($program->getStartTime());
                $programArchiver->setTags($program->getTags());
                $programArchiver->setDate($program->getDate());
                $programArchiver->setPublish($program->getPublish());
                $programArchiver->setTime($program->getTime());
                $programArchiver->setCreatedAt($program->getCreatedAt());
                $programArchiver->save();
            }
            $program->delete();
        }
    }
  }
}

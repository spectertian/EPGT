<?php
/**
 * 节目表增加创建全文搜索功能任务
 * @author luren
 */
class tvProgramToXapianTask extends sfMondongoTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
      // add your own options here
    ));

    $this->namespace        = 'tv';
    $this->name             = 'programToXapian';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tvProgramToXapian|INFO] task does things.
Call it with:

  [php symfony tvProgramToXapian|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $mongo = $this->getMondongo();
    $program_repository = $mongo->getRepository('Program');

    $v = true;
    $i = 0;
    while($v) {
       $programs = $program_repository->find(array(
            'query' => array(
                'date' => array('$gte' => date('Y-m-d', time()))
            ),
            'skip' => $i,
            'limit' => 50,
        ));
       
        if (!is_null($programs)) {
            foreach ($programs as $program) {
                $program->updateXapianDocument();
                printf("%s \n", $program->getName(). $program->getDate());
            }
        } else {
            $v = false;
        }
        
        $i += 50;
    }

    echo "End .. \n";
  }
}

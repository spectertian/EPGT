<?php
/**
 *  @todo  : 每天23点执行一次，统计节目匹配率
 *  @author: lifucang 2013-08-21
 */
class tvProgramLogTask extends sfMondongoTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','stba'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
    ));

    $this->namespace        = 'tv';
    $this->name             = 'ProgramLog';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tv:ProgramLog|INFO] task does things.
Call it with:

  [php symfony tv:ProgramLog|INFO]
EOF;
  }

    protected function execute($arguments = array(), $options = array())
    {
        $date=date('Y-m-d');
        $mongo = $this->getMondongo();
        $program_repo = $mongo->getRepository('Program');

        $query=array('date' =>$date);
        $programNum=$program_repo->count($query);
        
        $query=array('wiki_id' => array('$exists'=>true),'date' =>$date);
        $programWikiNum=$program_repo->count($query);

        $programLog=new ProgramLog();
        $programLog->setDate($date);
        $programLog->setNums($programNum);
        $programLog->setWikiNums($programWikiNum);
        $programLog->save();
        echo "finished\n";
    }
}

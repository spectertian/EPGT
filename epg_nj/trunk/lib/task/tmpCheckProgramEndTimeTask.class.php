<?php
/**
 *  @todo  : 检查节目的结束时间是否大于开始时间
 *  @author: lifucang 2013-08-13
 */
class tmpCheckProgramEndTimeTask extends sfMondongoTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','stba'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
    ));

    $this->namespace        = 'tmp';
    $this->name             = 'CheckProgramEndTime';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tmp:CheckProgramEndTime|INFO] task does things.
Call it with:

  [php symfony tmp:CheckProgramEndTime|INFO]
EOF;
  }

    protected function execute($arguments = array(), $options = array())
    {  
        $mongo = $this->getMondongo();
        $program_repo = $mongo->getRepository("Program");      
        $date = date("Y-m-d");
        $programs = $program_repo->find(array('query'=>array('date'=>$date)));
        $arr=array();
        foreach($programs as $program){
            $startTime=$program->getStartTime();
            $endTime=$program->getEndTime();
            if($endTime<$startTime){
                $arr=array(
                    'id' => $program -> getId(),
                    'channel_code' =>  $program -> getChannelCode(),
                    'name' => $program -> getName(),
                    'date' => $program -> getDate(),
                    'time' => $program -> getTime(),
                );
            }
        }
        echo '共有：',count($arr),"个\n";
        sleep(2);
        echo '<pre>';
        foreach ($arr as $value){
            print_r($arr);
        }
        echo 'finished!';
    }
}

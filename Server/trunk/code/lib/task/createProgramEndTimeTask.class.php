<?php
/**
 * 更新节目的结束时间
 * 
 * @author superwen
 */
class createProgramEndTimeTask extends sfMondongoTask
{
	protected function configure()
	{
		$this->addOptions(array(
			new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
			new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
			new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
			new sfCommandOption('date', null, sfCommandOption::PARAMETER_REQUIRED, 'what date???????', ''),
			// add your own options here
		));

		$this->namespace        = 'tv';
		$this->name             = 'createProgramEndTime';
		$this->briefDescription = '';
		$this->detailedDescription = <<<EOF
The [createProgramEndTime|INFO] task does things.
Call it with:

  [php symfony createProgramEndTime|INFO]
EOF;
	}

	protected function execute($arguments = array(), $options = array())
	{		
		$databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        $program_repostitory = $this->getMondongo()->getRepository('Program');

		if($options['date']) {
			$today = $options['date'];
		} else {
			$today = date('Y-m-d');
		}		
	    
	    $channels = Doctrine::getTable('Channel')->findAll();
	    for($i=0; $i<=3; $i++)
		{
			$date_from = date('Y-m-d', strtotime($today.'+'.$i.' day'));
			foreach($channels as $channel) {
		        $channel_code = $channel->getCode();
		        $programs = $program_repostitory->find(
		                    array('query' => array('channel_code' => $channel_code, 'date' => $date_from),
		                          'sort' => array('time' => 1)
		                    )
		                );
		        if(!$programs)            continue;
		        $prev_program = '';
		        foreach($programs as $program) {
		            if(!$prev_program) {
		                $prev_program = $program;
		                continue;
		            }
		            //echo $program->getStartTime()->format('Y-m-d H:i');die();
		            $prev_program->setEndTime($program->getStartTime());
		            $prev_program->save();
		            $prev_program = $program;
		        }
		
		        //当天最后一条end_time取隔天第一条start_time
		        $next_program = $program_repostitory->findOne(
		                            array(
		                                'query' => array('channel_code' => $channel_code, 'date' => date('Y-m-d', strtotime($date_from.'+'.($i+1).'day'))),
		                                'sort' => array('time' => 1)
		                            )
		                        );
		        if($next_program) {
		            $prev_program->setEndTime($next_program->getStartTime());
		            $prev_program->save();
		        }
		    }
		}	
		echo 'finished!';
	}
}

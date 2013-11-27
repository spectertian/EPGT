<?php
/**
 * 更新所有频道正在播放和下一个的节目
 * 每分钟执行一次
 * @author wn
 * @modify qhm 2013/6/18
 */
class programLiveTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
            new sfCommandOption('is_update_all', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'no'),
            // add your own options here
        ));

        $this->namespace    = 'tv';
        $this->name         = 'programLive';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [programLive|INFO] task does things.
Call it with:
    [php symfony programLive|INFO]
EOF;
    }

	protected function execute($arguments = array(), $options = array())
	{
        if($options["is_update_all"] == "yes") {
            $this->updateAllChannelLive();
        }else {
            $this->updateCurChannelLive();
        }		
	}
    
    protected function updateCurChannelLive()
    {
        $mongo = $this->getMondongo();
		$program_repository = $mongo->getRepository('Program');
		$programlive_repository = $mongo->getRepository('ProgramLive');
		$wiki_repository = $mongo->getRepository('wiki');
		$starttime = date("Y-m-d H:i");
		$starttimes=strtotime($starttime);
		$m_starttime =date("Y-m-d H:i",$starttimes);
		$m_endtime = date("Y-m-d H:i",$starttimes+60*10);
		//插入数据
		$programs = $program_repository->getAllLivePrograms(strtotime($starttime));
		if($programs) {
			foreach($programs as $program) {
				$channel_code = $program->getChannelCode();
                $programLives = $programlive_repository->getProgramByCode($channel_code);
                if(!$programLives) {
                    $programLive = new programLive(); 
                    if($channel_code){         
                    $next_program = $program_repository->getNextProgram(array($channel_code),'',false,true);  
                    }                  
                    $wiki = $wiki_repository->findOneById(new MongoId($program->getWikiId()));
                    if($type) {
                    	$programLive->setType($type);
                    }
                    $programLive->setName($program->getName());
                    $programLive->setStartTime($program->getStartTime());
                    if($program->getEndTime()){
                    	$programLive->setEndTime($program->getEndTime());
                    }else{
                    	$l_endtime = date("Y-m-d H:i",$starttimes+60*30);
                    	$programLive->setEndTime($l_endtime);
                    }
                    $programLive->setChannelCode($program->getChannelCode());
                    if($wiki) {
                    	$programLive->setWikiCover($wiki->getCover());
                    	$programLive->setWikiTitle($wiki->getTitle());
                    	$programLive->setWikiId((string)$wiki->getId());
                    }
                    if($next_program[0]){
                     $programLive->setNextName($next_program[0]->getName());
                    }else{
                     $programLive->setNextName("未知");
                    } 
                    $programLive->save();
                 }

			}
		}
		
		//更新过期数据
		$guoqi_programlives = $programlive_repository->getAllGuoQiPrograms(strtotime($starttime));
		if($guoqi_programlives) {
		
			foreach($guoqi_programlives as $guoqi_programlive) {
				$channel_code = $guoqi_programlive->getChannelCode();
		
				if($channel_code){
					$next_program = $program_repository->getNextProgram(array($channel_code),'',false,true);//获取接下来要播放的节目
					$now = new MongoDate();
					$program_now=  $program_repository->getLiveProgramByCode($channel_code,'',false,true);//获取当前播放的节目
					if($program_now){
						$programLive_s = $mongo->getRepository('ProgramLive');
						$wiki = $wiki_repository->findOneById(new MongoId($program_now->getWikiId()));
						$program_Live = $programLive_s->findOneById(New MongoId($guoqi_programlive->getId()));
						$program_Live->setName($program_now->getName());
						$program_Live->setStartTime($program_now->getStartTime());		
						if($program_now->getEndTime()){
							$program_Live->setEndTime($program_now->getEndTime());
						}else{
							$l_endtime = date("Y-m-d H:i",$starttimes+60*30);
							$program_Live->setEndTime($l_endtime);
						}		
						if($next_program[0]){
					        $program_Live->setNextName($next_program[0]->getName());
						}else{
								
							$program_Live->setNextName("未知");
						}
		
						if($wiki) {
							$program_Live->setWikiCover($wiki->getCover());
							$program_Live->setWikiTitle($wiki->getTitle());
							$program_Live->setWikiId((string)$wiki->getId());
						}
		
						$program_Live->save();
						 
					}else{
						 
 
						$programLive_ss = $mongo->getRepository('ProgramLive'); 
						$program_Live_s = $programLive_ss->findOneById(New MongoId($guoqi_programlive->getId()));
						$program_Live_s->setName("未知");
						$program_Live_s->setStartTime($m_starttime);
						$program_Live_s->setEndTime($m_endtime);
						if($next_program[0]){
							
			                $program_Live_s->setNextName($next_program[0]->getName());
						}else{
							
							$program_Live_s->setNextName("未知");
						}
						 $program_Live_s->save();	 
					
				  }
				}
			}
		}
    }
    //更新所有频道数据过
    protected function updateAllChannelLive()
    {
    	$mongo = $this->getMondongo();
    	$program_repository = $mongo->getRepository('Program');
    	$programlive_repository = $mongo->getRepository('ProgramLive');
    	$wiki_repository = $mongo->getRepository('wiki');
    	$starttime = date("Y-m-d H:i");
    	$starttimes=strtotime($starttime);
    	$m_starttime =date("Y-m-d H:i",$starttimes);
    	$m_endtime = date("Y-m-d H:i",$starttimes+60*10);
    	//查询所有的频道
    	$allchennels = Doctrine::getTable('Channel')->createQuery()
    	->execute();
    	foreach($allchennels as $channel) {
    		$channel_code = $channel->getCode();
    		if($channel_code){
    			$next_program = $program_repository->getNextProgram(array($channel_code),'',false,true);//获取接下来要播放的节目
    			$now = new MongoDate();
    			$program_now=  $program_repository->getLiveProgramByCode($channel_code,'',false,true);//获取当前播放的节目
    			if($program_now){
    				$programlive_channel_code=$programlive_repository->getProgramByCode($channel_code);
    				if($programlive_channel_code){
    					$programLive_s = $mongo->getRepository('ProgramLive');
    					$wiki = $wiki_repository->findOneById(new MongoId($program_now->getWikiId()));
    					$program_Live = $programLive_s->findOneById(New MongoId($programlive_channel_code->getId()));
    					$program_Live->setName($program_now->getName());
    					$program_Live->setStartTime($program_now->getStartTime());
    					$program_Live->setEndTime($program_now->getEndTime());
    					if($next_program[0]){
    						$program_Live->setNextName($next_program[0]->getName());
    					}else{
    	
    						$program_Live->setNextName("未知");
    					}
    					
    					if($wiki) {
    						$program_Live->setWikiCover($wiki->getCover());
    						$program_Live->setWikiTitle($wiki->getTitle());
    						$program_Live->setWikiId((string)$wiki->getId());
    					}
    	
    					$program_Live->save();
    				}
    			}else{
    				$programlive_channel_codes=$programlive_repository->getProgramByCode($channel_code);
    				if($programlive_channel_codes){
    					$programLive_sss = $mongo->getRepository('ProgramLive');
    					$program_Live_s = $programLive_sss->findOneById(New MongoId($programlive_channel_codes->getId()));
    					$program_Live_s->setName("未知");
    					$program_Live_s->setStartTime($m_starttime);
    					$program_Live_s->setEndTime($m_endtime);
    					if($next_program[0]){
    						$program_Live_s->setNextName($next_program[0]->getName());
    					}else{
    						$program_Live_s->setNextName("未知");
    					}
    					$program_Live_s->save();
    				}
    			}
    	
    		}
    	}
    }
    
}
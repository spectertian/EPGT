<?php
class defaultComponents extends sfComponents {
    public function executeLiveList( sfWebRequest $request ) 
    {
		$mongo = $this->getMondongo();
        //$channels = Doctrine::getTable('Channel')->getChannels();       
        $channels=$mongo->getRepository('SpService')->getServicesByTag(null,'hot',-1);
        $programRes = $mongo->getRepository('program');
		//$this->program_list = $programRes->getLiveProgramByTag('', $channels,10);
        $k=0;
        foreach($channels as $channel){
            if($k>=10) break;
            $program=$programRes->getLiveProgramByChannel($channel->getChannelCode());
            if($program){
                 $arr_program[]= $program;
                 $k++;
            }
        }
        $this->program_list=$arr_program;
    }
    
}
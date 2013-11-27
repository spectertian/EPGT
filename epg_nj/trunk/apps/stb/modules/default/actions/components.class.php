<?php
class defaultComponents extends sfComponents {
    public function executeLiveList( sfWebRequest $request ) 
    {
        $memcache = tvCache::getInstance(); 
        $this->program_list=$memcache->get("index_programs");
        if(!$this->program_list){
    		$mongo = $this->getMondongo();   
            $channels=$mongo->getRepository('SpService')->getServicesByTag(null,'hot',-1);
            $programRes = $mongo->getRepository('program');
    		//$this->program_list = $programRes->getLiveProgramByTag('', $channels,10);
            $k=1;
            $arr_program=array();
            foreach($channels as $channel){
                if($k>10) break;
                $program=$programRes->getLiveProgramByChannel($channel->getChannelCode());
                if($program){
                     //$arr_program[]= $program;
                     $all = strtotime($program->getEndTime()->format("Y-m-d H:i:s")) - strtotime($program->getStartTime()->format("Y-m-d H:i:s"));
                     $plan = time() - strtotime($program->getStartTime()->format("Y-m-d H:i:s"));
                     $width = round($plan/$all,2) * 100;
                     $arr_program[] = array(
                         'wikiscreen' => $program->getWikiScreen(),
                         'width' => $width,
                         'spname' => $program->getSpName(),
                         'wikititle' => $program->getWikiTitle(),
                         'sphot' => $program->getSpHot(),
                     );
                     $k++;
                }
            }
            $memcache->set("index_programs",$arr_program,60);  //1·ÖÖÓ
            $this->program_list=$arr_program; 
        }
    }
}
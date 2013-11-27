<?php
/**
 * 豆瓣影人信息抓取任务
 * 
 */
class tvDoubanCelebrityInfoTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo')
        ));

        $this->namespace        = 'tv';
        $this->name             = 'DoubanCelebrityInfo';
        $this->briefDescription = '';
        $this->detailedDescription = '';
    }

    protected function execute($arguments = array(), $options = array())
    {    
        $mongo = $this->getMondongo();
        $dmRep = $mongo->getRepository("DoubanMovie");
   
        
        $douban_id = 0;
        $i = 0;
        while(1) {
            $dmCol = $dmRep->find(array(
                "query" => array("douban_id" => array('$gt' => $douban_id),"syn_status" => array('$exists' => true)),
                "sort" => array("douban_id" => 1),
             	'skip' => $i,
                "limit" => 100
            		));
            $dmNum = count($dmCol);
            if($dmNum > 0) {
                foreach($dmCol as $dmDoc) {
                    $douban_id = $dmDoc->getDoubanId();
                    $dmDoc->getDoubanId();
          
                    
                    if($dmDoc->getDirectors()){
                       echo "aaaaaaaaaaaaaaaa";
                       echo "\n";
                    	foreach($dmDoc->getDirectors() as $Directors) {
                    		
                    		if(!empty($Directors['id'])){
	                    		$Celebrity_m = $mongo->getRepository("DoubanCelebrity");
	                    	    $m= $Celebrity_m->getCelebrityInfoOne(intval($Directors['id']));
	                    	     if(empty($m)){
	                   
	                    		 $Celebrity = new DoubanCelebrity();
	                    	     $Celebrity->setDoubanId($Directors['id']);
	                    	     $Celebrity->setName($Directors['name']);
	                    	     $Celebrity->Save();
	                    	     }
                    		}
                    		
                    	}
       
                    }
                    
                    if($dmDoc->getCasts()){
                    	
                    	echo "bbbbbbbbbbbbbbbbbbbbbbbbbbb";
                    	echo "\n";
                    	foreach($dmDoc->getCasts() as $Casts) {

                    		if(!empty($Casts['id'])){
                    			$Celebrity_mm = $mongo->getRepository("DoubanCelebrity");
                    		   $mm= $Celebrity_mm->getCelebrityInfoOne(intval($Casts['id']));
	                    		if(empty($mm)){
	                    			echo $Casts['id'];
	                    			echo "\n";
	                    		  $Celebritys = new DoubanCelebrity();
	                    		  $Celebritys->setDoubanId($Casts['id']);
	                    		  $Celebritys->setName($Casts['name']);
	                    		  $Celebritys->Save();
	                    		} 
                    		}
                    		
                    	}
                    	
                    }

                   // file_put_contents("./log/task_douban_moiveinfo.log","$douban_id\n",FILE_APPEND);
                    sleep(2);
                }
            }else{
                break;
            }
            $i += 100;
        }
        
    }
    
    protected function getMoiveBySubject($id)
    {
        require_once("lib/vendor/simple_html_dom.php");
        
        $id = intval($id);
        $url = "https://api.douban.com/v2/movie/subject/".$id;
        $html = Common::get_url_content($url);
        $movie = json_decode($html,true);
        if(!$movie) {
            return null;
        }
        if(isset($movie['id'])) {            
            return $movie;
        }else{
            return null;
        }
    }
    
    protected function ignore_non_utf8($text)
    {
        $text = htmlspecialchars_decode(htmlspecialchars($text, ENT_IGNORE, 'UTF-8'));
        $text = preg_replace('~\s+~u', ' ', $text);
        $text = preg_replace('~\p{C}+~u', '?', $text);
        return $text;
    }
}
?>
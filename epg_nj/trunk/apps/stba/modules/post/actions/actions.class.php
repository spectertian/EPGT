<?php

/**
 * post actions.
 *
 * @package    epg2.0
 * @subpackage post
 * @author     Huan Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class postActions extends sfActions
{
 /**
  * 测试其他接口（tcl，运营中心等接口）返回的数据
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
        if ($request->getMethod() == 'POST') {
            $jsonstr = $request->getPostParameter('xmlString');
            $content = file_get_contents($jsonstr);
            $this->getResponse()->setContentType('text/plain');
            return $this->renderText($content);            
        } else {
            //$this->getResponse()->setContentType('text/plain');
            //return $this->renderText('The Json Server accepts POST requests only.');
        }    
  }
  
 /**
  * 测试接口反应时间
  *
  * @param sfRequest $request A request object
  */
  public function executeTestTime(sfWebRequest $request)
  {
        if ($request->getMethod() == 'POST') {
            $jsonstr = $request->getPostParameter('xmlString');
            $start=microtime(true);
            $content = file_get_contents($jsonstr);
            //sleep(2);
            $end=microtime(true);   
            $runtime=$end-$start;   
            //echo "运行时间:".number_format($runtime, 10, '.', '').'秒'; 
            echo "运行时间:".$runtime.'秒'; 
            return sfView::NONE;           
        } else {
            //$this->getResponse()->setContentType('text/plain');
            //return $this->renderText('The Json Server accepts POST requests only.');
        }    
  }

    /**
    * 测试tcl直播程序处理时间
    * @param sfRequest $request A request object
    */
    public function executeTclLiveTime(sfWebRequest $request)
    {
        $runtime=$this->getTclLivePrograms('8250102372401749',6,'Series');
        echo "运行时间:".$runtime.'秒'; 
        return sfView::NONE;  
    }  
    /**
     * 获取tcl的直播推荐，用于测试时间。
     * @author superwen
     * @editor lifucang 2013-01-05
     * @date   2013-01-03
     */
    protected function getTclLivePrograms($user_id,$count=20,$type='')
    {
        $programList = null;
        $mongo = $this->getMondongo();
        $sp_repository = $mongo->getRepository('SpService');
        $programs = $mongo->getRepository('program');
        $ccount = $count*2;
        $user_id = substr($user_id,0,strlen($user_id)-1);
        //按标签推荐
        $url = sfConfig::get('app_recommend_tclUrl')."?accesskey=123&service=cep20&operation=GetRecommendList&rtype=recommend.bygenre.v1&ctype=epg&count=".$ccount."&genre=".$type."&uid=".$user_id;
        $contents = Common::get_url_content($url);
        if(!$contents){
            return null;
        }
        
        $start=microtime(true);
        $arr_contents = json_decode($contents);
        if(!$arr_contents) {
            return null;
        }
        $k=0;
        foreach($arr_contents->recommend as $value){
            $sp=$sp_repository->findOne(array('query'=>array('channel_id'=>$value->contid_id)));
            $channelCode = $sp->getChannelCode(); 
            $program=$programs->getLiveProgramByChannel($channelCode);
            if($program&&$program->getWiki()){
                 $programList[]= $program;
                 $k++;
            }    
            if($k>=$count) break;     
        }
        $end=microtime(true);
        $runtime=$end-$start;   
        return $runtime;
    }
    
    /**
    * 测试传递对象的时间
    * @param sfRequest $request A request object
    */
    public function executeTestObjTime(sfWebRequest $request)
    {
        $start=microtime(true);
        for($i=0;$i<1000;$i++){
            //echo $i,"<br>";
            $this->testObj($i);
        }
        $end=microtime(true);
        $runtime=$end-$start;   
        echo "1运行时间:".$runtime.'秒'."<br/>";
        
        //另一种方法
        $httpsqs = HttpsqsService::get(); 
        $start=microtime(true);
        for($i=0;$i<1000;$i++){
            //echo $i,"<br>";
            $this->testObj1($httpsqs,$i);
        }
        $end=microtime(true);
        $runtime=$end-$start;   
        echo "2运行时间:".$runtime.'秒'."<br/>";
         
        //另一种方法
        $httpsqs = HttpsqsService::get(); 
        $start=microtime(true);
        for($i=0;$i<1000;$i++){
            $queue=$this->testObj2($i);
            $httpsqs->put("epg_test",$queue);
        }
        $end=microtime(true);
        $runtime=$end-$start;   
        echo "3运行时间:".$runtime.'秒'."<br/>";
        
        return sfView::NONE;  
    }  
    
    private function testObj($file_key) {
        $httpsqs = HttpsqsService::get();  
        $arr = array(
                   "title" => $file_key,
                   "action" => "attachment_copy",
                   "parms" => array("file_key" => $file_key)
                   );
        return $httpsqs->put("epg_test",json_encode($arr));
    } 
    private function testObj1($httpsqs,$file_key) {
        //$httpsqs = HttpsqsService::get();  
        $arr = array(
                   "title" => $file_key,
                   "action" => "attachment_copy",
                   "parms" => array("file_key" => $file_key)
                   );
        return $httpsqs->put("epg_test",json_encode($arr));
    }   
    private function testObj2($file_key) { 
        $arr = array(
                   "title" => $file_key,
                   "action" => "attachment_copy",
                   "parms" => array("file_key" => $file_key)
                   );
        return json_encode($arr);
    }       
    public function executeTestHttpsqs() {
        $httpsqs = HttpsqsService::get();  
        $result = $httpsqs->status_json('epg_test');
        echo '<pre>';
        print_r($result);
        $httpsqs->reset('epg_test');
        $result = $httpsqs->status_json('epg_test');
        print_r($result);
        return sfView::NONE;  
    }   
}

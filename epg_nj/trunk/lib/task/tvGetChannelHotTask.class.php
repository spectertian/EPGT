<?php
/**
 * @tobo   从终端获取频道的热度
 * @author superwen
 * @time   2012-12-13
 */
class tvGetChannelHotTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'stba'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
        ));
        
        $this->namespace        = 'tv';
        $this->name             = 'GetChannelHot';
        $this->briefDescription = '';
        $this->detailedDescription = '';
      }

  protected function execute($arguments = array(), $options = array())
  {
        $url = sfConfig::get("app_statsQuery_biz")."?CMD=Channel";
        $cu = curl_init();
        curl_setopt($cu, CURLOPT_URL, $url);
        curl_setopt($cu, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($cu, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($cu, CURLOPT_USERPWD, 'north_user:north_user');
        $ret = curl_exec($cu);
        curl_close($cu);
        $ca = @json_decode($ret);
        if($ca and isset($ca->BizStatsInfo)) {                 
            $mongo = $this->getMondongo();
            $repository = $mongo->getRepository('SpService');
            foreach($ca->BizStatsInfo as $key => $value){
                $spService = $repository->findone(array('query' => array( "name" => $key)));
                if($spService){
                    $spService->setHot($value);
                    $spService->save();   
                }   
            }
            echo date("Y-m-d H:i:s")." finished!\n";
        }else{
            @file_put_contents("./log/tv_getChannelHot_error_".date("Ymd").".log",date("Ymd")." 写入错误!",FILE_APPEND);            
            @file_put_contents("./log/tv_getChannelHot_error_".date("YmdHis").".log",$ret);
        }
    }  
}

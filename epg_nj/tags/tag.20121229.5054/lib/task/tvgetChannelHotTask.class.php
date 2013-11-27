<?php

class tvgetChannelHotTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
        ));

        $this->namespace        = 'tv';
        $this->name             = 'getChannelHot';
        $this->briefDescription = '';
        $this->detailedDescription = '';
      }

  protected function execute($arguments = array(), $options = array())
  {
        $url = "http://172.31.6.27:8080/hems/servlet/BizStatsQuery?CMD=Channel";
        $cu = curl_init();
        curl_setopt($cu, CURLOPT_URL, $url);
        curl_setopt($cu, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($cu, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($cu, CURLOPT_USERPWD, 'north_user:north_user');
        $ret = curl_exec($cu);
        curl_close($cu);

        $ca = @json_decode($ret);
        if($ca) {
            $this->connectMaster($options);
            
            $q = Doctrine_Query::create() 
                    ->update('channel') 
                    ->set('hot=?',0)
                    ->execute();
             
            foreach($ca->BizStatsInfo as $key => $value){
                //echo $key ."\t". $value . "\n";
                $q = Doctrine_Query::create() 
                        ->update('channel') 
                        ->set('hot=?',$value) 
                        ->where('name = ?', $key)
                        ->execute();
                //更新spservice        
                $mongo = $this->getMondongo();
                $repository = $mongo->getRepository('SpService');
                $spService = $repository->findone(array('query' => array( "name" => $key)));
                if($spService){
                    $spService->setHot($value);
                    $spService->save();   
                }   
            }
            echo date("Y-m-d H:i:s")." finished!";
        }else{
            file_put_contents("../log/tv_getChannelHot_error_".date("Ymd").".log",date("Ymd")." 写入错误!");            
            file_put_contents("../log/tv_getChannelHot_error_".date("YmdHis").".log",$ret);
        }
    }
  
    /**
     * 连接 master 中的数据库
     * @param array $options
     */
    private function connectMaster($options) {
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    }  
}

<?php
/**
 *  从南京获取Inject
 *  @author: lifucang 2013-05-27
 */
class tvGetContentInjectTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
            new sfCommandOption('startTime', null, sfCommandOption::PARAMETER_OPTIONAL, 'startTime'),
            new sfCommandOption('endTime', null, sfCommandOption::PARAMETER_OPTIONAL, 'endTime'),
        ));

        $this->namespace        = 'tv';
        $this->name             = 'GetContentInject';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [tv:GetContentInject|INFO] task does things.
Call it with:

  [php symfony tv:GetContentInject|INFO]
EOF;
   //php symfony tv:GetContentInject --startTime=2013-05-21 --endTime=2013-05-22
    }

    protected function execute($arguments = array(), $options = array())
    {
        $mongo = $this->getMondongo();
        $url = "http://122.193.13.36:8082/json";
        
        $startTime=$options['startTime']?$options['startTime']:date("Y-m-d 00:00:00",strtotime("-1 days"));
        $endTime=$options['endTime']?$options['endTime']:date("Y-m-d 23:59:59",strtotime("-1 days"));
        echo $startTime,'---',$endTime,"\n";
        $json_post='{"action":"GetContentInject","device":{"dnum":"123"},"user":{"userid":"123"},"param":{"start_time":"'.$startTime.'","end_time":"'.$endTime.'"}}';        
        $getinfo = Common::post_json($url,$json_post);
		$result = json_decode($getinfo,true); 
        $injects=$result['injects']?$result['injects']:array(); 
        echo "count:",count($injects),"\n";
        sleep(1);
        foreach($injects as $inject){
               $injectExists = $mongo->getRepository("ContentInject")->findOneById(new MongoId($inject['id']));
               if(!$injectExists){
                    $injectLocal = new ContentInjectNew();
                    $injectLocal -> setId(new MongoId($inject['id']));
                    $injectLocal -> setContent($inject['content']);
                    $injectLocal -> setFrom($inject['from']);
                    $injectLocal -> setState($inject['state']);
                    $injectLocal -> save();
               }
        }    
        echo date("Y-m-d H:i:s"),"------finished!\r\n";  
  }
}

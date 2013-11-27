<?php
/**
 * @tobo   调用GetAttachments获取EPG平台附件信息，获取很多天的用这个
 * @author lifucang
 * @time   2013-01-20
 * @example   symfony tv:GetAttachmentsByDate --days=1 --date=2013-03-10
 */
class tvGetAttachmentsByDateTask extends sfMondongoTask
{
  protected function configure()
  {

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','stba'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
      new sfCommandOption('date', null, sfCommandOption::PARAMETER_OPTIONAL, 'date'),
      new sfCommandOption('days', null, sfCommandOption::PARAMETER_OPTIONAL, 'days'),
    ));

    $this->namespace        = 'tv';
    $this->name             = 'GetAttachmentsByDate';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tv:GetAttachmentsByDate|INFO] task does things.
Call it with:

  [php symfony tv:GetAttachmentsByDate|INFO]
EOF;
  }
    protected function execute($arguments = array(), $options = array())
    {
        if(isset($options['days'])){
            $daynum = $options['days'];
        }else{
            $daynum = 30;
        }
        if(isset($options['date'])){
            $date = $options['date'];
        }else{
            $date = date("Y-m-d");
        }
        $dates=explode('-',$date);
        $year=$dates[0];
        $month=$dates[1];
        $day=$dates[2];
        for($days = 0; $days < $daynum ; $days ++) {  
            $date1 = date("Y-m-d",mktime(0,0,0,$month,$day-$days-1,$year));
            $date2 = date("Y-m-d",mktime(0,0,0,$month,$day-$days,$year));
            echo $date1,'---',$date2,"\n";
            exec("/usr/local/php5.3.8/bin/php /usr/share/nginx/5itv/symfony tv:GetAttachments --start_time=$date1 --end_time=$date2 --isprint=true >> /usr/share/nginx/5itv/tmp/GetAttachmentsXX.txt");
            //exec("php /www/newepg/symfony tv:GetAttachments --start_time=$date1 --end_time=$date2 --isprint=true >> /www/newepg/tmp/GetAttachmentsXX.txt");
            sleep(1);
        }
        echo "finished \n";    
    }
}

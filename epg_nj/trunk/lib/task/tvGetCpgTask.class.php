<?php
/**
 * @todo   从ftp获取CPG，导入CPG表中，同时关联Program。
 * @author superwen
 * @time   2012-12-18
 */
class tvGetCpgTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'stba'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
            new sfCommandOption('date', null, sfCommandOption::PARAMETER_OPTIONAL, 'The connection name')
        ));

        $this->namespace        = 'tv';
        $this->name             = 'GetCpg';
        $this->briefDescription = '';
        $this->detailedDescription = '';
    }

    protected function execute($arguments = array(), $options = array())
    { 
        ini_set('memory_limit','512M');
        $mongo = $this->getMondongo();
        //先记录日志
        $crontabStartTime=date("Y-m-d H:i:s");
        $crontabLog=new CrontabLog();
        $crontabLog->setTitle('GetCpg');
        $crontabLog->setContent('');
        $crontabLog->setState(0);
        $crontabLog->setStartTime($crontabStartTime);
        $crontabLog->save();
        //开始
      $contentid = 0;
      //ftp到tmp/cpg
      //$program   = $mongo->getRepository("Program");
      $spservice = $mongo->getRepository('SpService');
      $cpgrepo   = $mongo->getRepository('Cpg');
      
      if (isset($options['date'])){
            if(preg_match("/^\d{4}-\d{2}-\d{2}$/s",$options['date'])){
                $date[] = str_replace('-', '', $options['date']);
            }else{
                echo 'date format wrong!',"\n";exit;
            }
      }else{
            $date[] = date('Ymd',time());
            //$date[] = date('Ymd',strtotime('+1 day'));
            //$date = array('20121217','20121223','20121224','20121225','20121226','20121227');
      }
      $update_numall=0;
      $add_numall=0;
      foreach($date as $dateval){
            $true  = 0;
            $false = 0;
            $update_num=0;
            $add_num=0;
            $file = "./tmp/cpg/Stbschedual".$dateval.".csv";
            $conn = @ftp_connect("172.20.224.235") or die("FTP服务器连接失败");
            @ftp_login($conn,"timeshift","timeshift123") or die("FTP服务器登陆失败");
            @ftp_get($conn,$file,'Stbschedual'.$dateval.'.csv',FTP_BINARY);
            if(!file_exists($file)){
              echo $file,'--is not exists!!!',"\n";continue;
            }
            $csvArr = self::getCSVdata($file);
            foreach($csvArr as $k=>$v){
                $sp_one = $spservice->findOne(array('query'=>array('channel_id'=>$v[0])));
                if($sp_one){
                    $channelCode = $sp_one->getChannelCode();
                    $sstart_time = new MongoDate(strtotime($v[2]));
                    $send_time   = new MongoDate(strtotime($v[3]));
                    $issameCpg = $cpgrepo->findOne(array('query'=>array('channel_code'=>$channelCode,'start_time'=>$sstart_time,'end_time'=>$send_time,'content_id'=>$v[4])));
                    if($issameCpg){
                        $issameCpg->setProgramName($v[1]);
                        $issameCpg->setContentId($v[4]);
                        $issameCpg->save();
                        $update_num++;
                    }else{
                        $cpg = new Cpg();
                        $cpg->setChannelCode($channelCode);
                        $cpg->setContentId($v[4]);
                        $cpg->setDate(date('Y-m-d',strtotime($v[2])));
                        $cpg->setStartTime(date('Y-m-d H:i:s',strtotime($v[2])));
                        $cpg->setEndTime(date('Y-m-d H:i:s',strtotime($v[3])));
                        $cpg->setProgramName($v[1]);
                        $cpg->save();
                        $add_num++;
                    }
                    
                    $true++;
                    /*
                    $start_time = new MongoDate(strtotime($v[2]));
                    $pro_one = $program->findOne(array('query'=>array('channel_code'=>$channelCode,'start_time'=>$start_time)));
                    if($pro_one){
                      $pro_one->setCpgContentId($v[4]);
                      $pro_one->save();
                      $contentid++;
                    }
                    */
                }else{
                    $false++;
                }
            }
            echo "$dateval---total:",count($csvArr),' true:',$true," add:",$add_num," update:",$update_num," false:",$false,"\n";
            $add_numall+=$add_num;
            $update_numall+=$update_num;
      }
      echo "------------------------\n";
        $content="Add:".$add_numall."--Update:".$update_numall;
        //更新计划任务日志
        $crontabLog_repo = $mongo->getRepository("CrontabLog");  
        $crontabLoga=$crontabLog_repo->findOneById($crontabLog->getId());
        $crontabLoga->setContent($content);
        $crontabLoga->setState(1);
        $crontabLoga->save();
    }
    
    /**
     * @todo 读取csv数据，返回数组
     * @author gaobo 2012-12-19
     * 
     * @param $filename(string) csv文件路径
     * @return array
     */
    public function getCSVdata($filename)
    {
      $row = 1;//第一行开始
      if(($handle = fopen($filename, "r")) !== false){
        while(($dataSrc = fgetcsv($handle)) !== false){
          $num = count($dataSrc);
          for ($c=0; $c < $num; $c++){//列 column
              $data[] = $dataSrc[$c];
          }
          if(!empty($data)){
            $dataRtn[] = $data;
            unset($data);
          }
          $row++;
        }
        fclose($handle);
        return $dataRtn;
      }
    }
}

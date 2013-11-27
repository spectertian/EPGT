<?php
/**
 * 从TVMAO导出TVSOU暂无的EPG为txt格式给新疆用
 * 
 * @author superwen
 */
class xjProgramExportTxtTempTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
        ));

        $this->namespace        = 'xj';
        $this->name             = 'ProgramExportTxtTemp';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [xj:ProgramExportTxtTemp|INFO] task does things.
Call it with:

  [php symfony xj:ProgramExportTxtTemp|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        $databaseManager = new sfDatabaseManager($this->configuration);
        $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
        
        $programRep = $this->getMondongo()->getRepository('Program');
        
        $conn = ftp_connect("110.173.3.73","1001") or die("FTP服务器连接失败");
        ftp_login($conn,"shanghai-epg","shanghai-epg021") or die("FTP服务器登陆失败");
        echo "FTP connected!\n";
        $dateArr = array(0,1,2,3,4,5,6);
        $wNum = date('w',time());
        $channels = array (
             '央广健康频道' => 'http://www.tvmao.com/program/BAMC-BAMC14-w1.html',
             '测试五'      => 'http://www.tvmao.com/program/SZCATV-STAR-MOVIES-w1.html'
        );
        
		foreach ($channels as $channel => $url) {
			echo $channel.":  ";
			$content = "\n";
			$fileName = $channel.'.txt';
            if($url == '') {
                continue;
            }
			foreach ($dateArr as $date) {
				$targetDate = ($date == 0)?date('Y-m-d',time()):date('Y-m-d',strtotime("+$date day"));
				$targetDate_format = ($date == 0)?date('y/m/d',time()):date('y/m/d',strtotime("+$date day"));
				echo $targetDate."  ";
				$content .= "\n".$targetDate_format."\n\n";
                //$dayPrograms = $programRep->getDayPrograms($channelCode,$targetDate);
                //抓取FIXME
				$str = '-w'.($wNum+$date).'.';
    			$urltemp = str_replace('-w1.',$str,$url);
    			echo $urltemp;
                $curl2 = curl_init();
    			curl_setopt($curl2, CURLOPT_URL, $urltemp);
    			curl_setopt($curl2, CURLOPT_HEADER, 1);
    			curl_setopt($curl2, CURLOPT_RETURNTRANSFER, 1);
    			$data = mb_convert_encoding(curl_exec($curl2), 'utf-8', 'gb2312,UTF-8,ASCII');
    			curl_close($curl2);
    			
    			preg_match_all('/<li><span class="(.*?)"(.*?)<\/li>/s',$data,$matches);
    			foreach($matches[0]  as $v){
    			    $str = str_replace('    ','',strip_tags($v));
    			    $str = str_replace(' 剧照','',$str);
    			    $str = str_replace(' 演员表','',$str);
    			    $str = str_replace(' 剧情','',$str);
    				$content .= $str."\n";
    			}
			}
			echo "\n";
			if (! empty($content)) {
				$file_date = date('Y-m-d',time());
				$target_file= '/epg_xj/'.$file_date.'/'.@iconv("UTF-8","GBK//IGNORE",$channel).'.txt';
				file_put_contents($fileName, @iconv("UTF-8","GBK//IGNORE",$content));
				ftp_pasv($conn,true);
				@ftp_mkdir($conn,'/epg_xj/'.$file_date);
				ftp_put($conn,$target_file,$fileName,FTP_ASCII);
				echo $target_file." upload!\n";
				@unlink($fileName);
			}
		}
        ftp_close($conn);
        echo "finished! connect closed!\n";
    }
    
    
    function getNullProgramList()
    {   
        return "00:00  以播出为准\n02:00  以播出为准\n04:00  以播出为准\n06:00  以播出为准\n08:00  以播出为准\n10:00  以播出为准\n12:00  以播出为准\n14:00  以播出为准\n16:00  以播出为准\n18:00  以播出为准\n20:00  以播出为准\n22:00  以播出为准\n";
    }
}

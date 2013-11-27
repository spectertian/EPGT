<?php

class test2Task extends sfMondongoTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application','stba'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
      // add your own options here
    ));

    $this->namespace        = '';
    $this->name             = 'test2';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [test2|INFO] task does things.
Call it with:

  [php symfony test2|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
        /*
        $contents = Common::get_url_content("http://www.qq.com",5);
        $fsc=new FSC();
        $filename=date("His").rand(100,999);
        if($contents){
            $fsc->writefile('./tmp/test/'.$filename.'.txt',$contents);
        }
        */
        for($i=0;$i<28;$i++){
            
            //$date = $request->getParameter('date',date("Y-m-d"));
            $date=date("Y-m-d",strtotime("-$i days"));
            $mongo = $this->getMondongo();
            $programRes = $mongo->getRepository('program');
            
            $program_num = $programRes->count(array('date'=>$date));
            echo $date,iconv('utf-8','gbk','共有节目数：'),$program_num,"\r\n";
            
            $wiki_num = $programRes->count(array('date'=>$date,'wiki_id'=>array('$exists'=>true)));
            echo iconv('utf-8','gbk','匹配wiki数：'),$wiki_num,"\r\n";
                    
            $coverNum=0;
            $noCoverNum=0;        
            $query = array('date'=>$date,'wiki_id'=>array('$exists'=>true));        
            $programs=$programRes->find(array('query'=>$query));
            
            $storage = StorageService::get('photo');
            foreach($programs as $program){
                $wikiCover=$program->getWikiCover();
                $content = $storage->get($wikiCover);
                if($content){
                    $coverNum++;
                }else{
                    $noCoverNum++;
                }
            }
            echo iconv('utf-8','gbk','有海报数：'),$coverNum,iconv('utf-8','gbk','无海报数：'),$noCoverNum,"\r\n"; 
            sleep(1);
            
        }
  }
}

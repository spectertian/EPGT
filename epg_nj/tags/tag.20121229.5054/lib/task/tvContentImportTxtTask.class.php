<?php
/**
 * @tobo   此为临时任务，用于读取CMS方提供的不规则文本内容。需要按照所提供的文本格式进行程序微调
 * @author gaobo
 * @time   2012-12-13
 */
class tvContentImportTxtTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
            // add your own options here
        ));

        $this->namespace        = 'tv';
        $this->name             = 'ContentImportTxt';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [tv:ContentImportTxt|INFO] task does things.
Call it with:

[php symfony tv:ContentImportTxt|INFO]
EOF;

        $this->acceptTypes = array("program","series");
    }

    protected function execute($arguments = array(), $options = array())
    { 
        error_reporting(0);
        $mongo = $this->getMondongo();
        $import_repo = $mongo->getRepository("ContentTemp");      
		$wiki_repository = $mongo->getRepository('wiki');
        
        $i = 0;        
        $del_num=0;
        $add_num=0;
        
        $fcontents = @file_get_contents('/www/newepg/yyzx_utf8.txt');
        if($fcontents){
            $flines = explode("\n", $fcontents);
            for($i=0; $i < count($flines); $i++){
                $arrTemp = explode('%%', $flines[$i]);
                $ContentImport = new ContentTemp();
                //$ContentImport = $mongo->getRepository("ContentTemp")->findOne(array('query'=>array('from_id'=>$arrTemp[0])));
                
                $title = self::getSubTitle($arrTemp[4]);
                $wiki  = $wiki_repository->getWikiByTitle($title);
                if($wiki){
                  $ContentImport->setWikiId((string)$wiki->getId());
                }
                $ContentImport->setFrom("yyzw");
                $ContentImport->setInjectId($arrTemp[0]);
                $ContentImport->setFromId($arrTemp[1]);
                $ContentImport->setFromTitle($arrTemp[4]);
                $ContentImport->setChildrenId($arrTemp[2]);
                if(!$ContentImport->save()){
                  $add_num++;
                }
                echo $i,"\n";
            }
        }
        echo 'wrong:'.$del_num,"\n",'right:'.$add_num,"\n";
    } 
    
    /**
     * 对节目名称进行过滤
     * @param void $ftp_conn
     */ 
    private function getSubTitle($str)
    {
        //替换
        $patterns = array('/\(.*\)/','/:/','/：/','/、/','/\s/','/（.*）/',
                          '/电视剧/','/精华版/','/首播/','/复播/','/重播/','/转播/','/中央台/',
                          '/故事片/','/译制片/','/动画片/','/剧场/','/Ⅰ/',
                          '/第.*集/','/_done/','/_out/','/\d+年\d+月\d+日/','/\d+-\d+-\d+/','/\d+_.*/','/-.*/');
        $str = preg_replace($patterns, "", $str);
        //替换
        $patterns = array('/法治中国/','/视野/','/爱探险的朵拉/',
                          '/欧美流行.*/','/舌尖上的中国.*/');
        $repatt = array('法治中国（江苏）','视野（辽宁）','爱探险的Dora',
                        '欧美流行','舌尖上的中国');
        $str = preg_replace($patterns, $repatt, $str);
        return $str;
    }    
}

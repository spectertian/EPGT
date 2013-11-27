<?php
/**
 * 测试接口返回速度
 * @author lifucang 2013-06-26
 */
class tmpTestInterfaceTimeTask extends sfMondongoTask
{
  protected function configure()
  {

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','stba'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
      // add your own options here
    ));

    $this->namespace        = 'tmp';
    $this->name             = 'testInterfaceTime';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [tmp:testInterfaceTime|INFO] task does things.
Call it with:

  [php symfony tmp:testInterfaceTime|INFO]
EOF;
  }

    protected function execute($arguments = array(), $options = array())
    {
        while(true){
            $start=microtime(true);
            $wikis=$this->getCenterVodPrograms('99766609340071223',4,'Series');
            $end=microtime(true);
            $runtime=$end-$start;   
            if($runtime>2){
                echo "******",date("Y-m-d H:i:s"),iconv('utf-8','gbk',"接口时间"),":",$runtime,"\n";
            }else{
                echo date("Y-m-d H:i:s"),iconv('utf-8','gbk',"接口时间"),":",$runtime,"\n";
            }
            
            //获取图片时间
            $start=microtime(true);
            foreach($wikis as $wiki){
                file_get_contents($wiki['poster']);
            }
            $end=microtime(true);
            $runtime=$end-$start;   
            if($runtime>2){
                echo "******",date("Y-m-d H:i:s"),iconv('utf-8','gbk',"获取图片时间"),":",$runtime,"\n";
            }else{
                echo date("Y-m-d H:i:s"),iconv('utf-8','gbk',"获取图片时间"),":",$runtime,"\n";
            }
            sleep(5);
        }
    }
    /**
     * 获取运营中心的点播推荐。
     * @author superwen
     * @editor lifucang 2013-01-05
     * @date   2013-01-03
     */
    protected function getCenterVodPrograms($user_id,$count=10,$type='',$backurl='',$alg='CF')
    {
        $wikis = null;
        $user_id = $user_id ? $user_id."_0" : "99766609340071223_0";
        //$filter  = $type ? urlencode("Category6='".$type."'") : "";
        switch($type){
            case "Series":
                $filter="Category6%3D%27%E7%94%B5%E8%A7%86%E5%89%A7%27";
                break;
            case "Movie":
                $filter="Category6%3D%27%E7%94%B5%E5%BD%B1%27";
                break;
            case "Sports":
                $filter="Category6%3D%27%E4%BD%93%E8%82%B2%27";
                break;
            case "Entertainment":
                $filter="Category6%3D%27%E7%BB%BC%E8%89%BA%27";
                break;
            case "Cartoon":
                $filter="Category6%3D%27%E5%8A%A8%E6%BC%AB%27";
                break;
            case "Culture":
                $filter="Category6%3D%27%E6%96%87%E5%8C%96%27";
                break;
            case "News":
                $filter="Category6%3D%27%E6%96%B0%E9%97%BB%E6%97%B6%E7%A7%BB%27";
                break; 
            default:
                $filter="";                                              
        }
        if($type=='News'){
            $recomUrl = sfConfig::get('app_recommend_centerUrl').'?accesskey=f06ffc3a9d1c4d1d9adc95912d4c66da&service=ie.v2&operation=GetRecommendList&rtype=recommend.toprating.v1&ctype=vod&postertype=1&count='.$count.'&lang=zh&urltype=1&alg='.$alg.'&uid='.$user_id.'&user_weight=0.4&optr_weight=0.6&filter='.$filter.'&backurl='.$backurl;
        }else{
            $recomUrl = sfConfig::get('app_recommend_centerUrl').'?accesskey=f06ffc3a9d1c4d1d9adc95912d4c66da&service=ie.v2&operation=GetRecommendList&rtype=recommend.rs.v1&ctype=vod&postertype=1&count='.$count.'&lang=zh&urltype=1&alg='.$alg.'&uid='.$user_id.'&filter='.$filter.'&backurl='.$backurl;
        }
        $recomTxt = Common::get_url_content($recomUrl);
        if($recomTxt){
            $recomJson = json_decode($recomTxt,true);
            if($recomJson)
                $wikis = $recomJson['recommend'];
        }
        return $wikis;
    }
}

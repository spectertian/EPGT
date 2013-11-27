<?php
/**
 *  导出每天JSON文本节目数据至FTP服务器epg目录下
 *  @author: gaobo
 */
class autoCompletionDoubanWikiTask extends sfMondongoTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
            new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
            new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'mondongo'),
        ));

        $this->namespace        = 'tv';
        $this->name             = 'autoCompletionDoubanWiki';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [autoCompletionDoubanWiki|INFO] task does things.
Call it with:

  [php symfony tv:autoCompletionDoubanWiki|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        $logName = 'autoCompletionDoubanWiki'.date('Y-m-d_H-i-s',time());
        $file = fopen('tmp/execlog/'.$logName,'a+');
        
        $doubanMongo = $this->getMondongo()->getRepository("DoubanMovie");
	    $wikiMongo   = $this->getMondongo()->getRepository("Wiki");
	    $model = array('','film','teleplay');
	    $filed = array('director','starring');
	    
	    $modelArr = array('movie'=>'film','tv'=>'teleplay');//豆瓣subtype对应维基model,目前已知对应关系是：豆瓣subtype的值movie==维基model的值film
	    $filedDou = array('director'=>'directors','starring'=>'casts');
	    
	    $i = 0;
	    $j = 0;
	    $str = '';
	    foreach($model as $m){
	        $query = array();
	        $query['douban_id'] = array('$exists'=>true);
	        
	        if($m == ''){//先查找model为空，并且存在豆瓣ID的数据，若豆瓣的数据有model值，则填充至WIKI里面
	            $query['model'] = array('$exists'=>false);
	            $wikis = $wikiMongo->find(array('query'=>$query));
	            $i = $i+count($wikis);//计数
	            if($wikis){
	                foreach($wikis as $wiki){
	                    $str .= $wiki->getId().'    ';
	                    $doubanId   = intval($wiki->getDoubanId());
	                    $doubanWiki = $doubanMongo->findOne(array('query'=>array('douban_id'=>$doubanId)));
	                    if($doubanWiki){
	                        //豆瓣subtype值有两种，tv和movie。
	                        if( ($doubanModel = $doubanWiki->getSubtype()) && (isset($modelArr[$doubanModel])) ){
                                $wiki->setModel($modelArr[$doubanModel]);
                                $wiki->save();
                                $j++;
                                $str .= $doubanWiki->getId();
	                        }
	                    }
	                    $str .= "\n";
	                    unset($wiki,$doubanWiki);
	                }
	            }
	            
	        }else{//查找model为film和teleplay，并且演员和导演为空的数据，若豆瓣的数据有相关字段的值，则填充至WIKI里面
	            $query['model'] = $m;
	            foreach($filed as $f){
	                $query[$f] = array('$exists'=>false);
	                //print_r($query);exit;
	                $wikis = $wikiMongo->find(array('query'=>$query));
	                $i = $i+count($wikis);//计数
	                foreach($wikis as $wiki){
	                    $str .= $wiki->getId().'    ';
	                    $doubanId   = intval($wiki->getDoubanId());
	                    echo $doubanId,"\n";
	                    $doubanWiki = $doubanMongo->findOne(array('query'=>array('douban_id'=>$doubanId)));
	                    if($doubanWiki){
	                        //echo $doubanWiki->getId();echo "\n";
	                        $getMethod = 'get'.ucfirst($filedDou[$f]);//组合出豆瓣的字段get方法
	                        $setMethod = 'set'.ucfirst($f);           //wiki的set字段方法
	                        
                            $valueArr = $doubanWiki->$getMethod();
                            $value = array();
                            if($valueArr){
                                foreach($valueArr as $d){
                                    $value[] = $d['name'];
                                }
                            }
	                        
	                        if( $value ){
	                            $wiki->$setMethod($value);
	                            $wiki->save();
	                            $j++;
	                            $str .= $doubanWiki->getId();
	                        }
	                    }
	                    $str .= "\n";
	                    unset($wiki,$doubanWiki);
	                }
	                unset($query[$f]);
	            }
	        }
	    }
	    echo 'writing log now , wait for a moment'."\n";
	    
	    if($str!=''){
	        if(fwrite($file,$str)){
	            echo 'write log success'."\n";   
	        }else{
	            echo 'write log false!!!'."\n";
	        }
	    }
	    fclose();
	    echo 'all:'.$i,"\n";
	    echo 'done:'.$j,"\n";
    }
}

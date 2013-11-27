<?php

/**
 * tool actions.
 *
 * @package    epg
 * @subpackage tool
 * @author     superwen
 * @modify     2012-12-29
 */
class toolActions extends sfActions 
{
    public function executeIndex(sfWebRequest $request) 
    {        
        return $this->renderText("小工具");
    }  
    
    public function executeSynFile(sfWebRequest $request)
    {
        $this->PageTitle = '单图片同步';
    }
    
    public function executePostSynFile(sfWebRequest $request) {
        if ($request->isXmlHttpRequest()) {
            $name = $request->getParameter('file');
            //$tvStation_ids = Doctrine::getTable('TvStation')->getTvStationIdsByParentId($topTvStation_id);
            //$channels = Doctrine::getTable('Channel')->getChannelsForTvStations($tvStation_ids);
            sfConfig::set('app_photo1_config', array('hosts' => '172.31.201.101:6001', 'domain' => 'epg', 'class' => 'image'));
            sfConfig::set('app_photo1_type', 'MogilefsStorage');

            $storage = StorageService::get('photo1');
            $content = $storage->get($name);
            if($content) {
                return $this->renderText("$name 已经存在。");
            }
                    
            $content = Common::get_url_content("http://image.epg.huan.tv/show/10/10/".$name);
            if($content) {
                @file_put_contents("../tmp/upload/".$name,$content);                     
                sleep(1);                     
            }
            if(file_exists("../tmp/upload/".$name)) {
                $storage->save($name,"../tmp/upload/".$name);
                //@unlink("../tmp/upload/".$name);
                return $this->renderText("$name 导入完成。<a href='http://172.31.139.17:81/2012/12/12/$name'>查看图片</a>");
            }else{
                return $this->renderText("$name 导入失败。");
            }            
        }
    }
}

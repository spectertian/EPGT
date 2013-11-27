<?php
/**
 * wordsWiki actions.
 *
 * @package    epg2.0
 * @subpackage wordsWiki
 * @author     Huan Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class wordsWikiActions extends sfActions
{
    
    private $patterns = array();   //敏感词数组
    private $status = '自动审核';  //敏感词状态
    //检查所有wiki中的敏感词
    public function executeIndex(sfWebRequest $request)
    {
        $mongo = $this->getMondongo();
        $wiki_repo = $mongo->getRepository("Wiki");
        $this->count = $wiki_repo->count();
    }
    public function executeCheck(sfWebRequest $request)
    {
        set_time_limit (0);
        $this -> getSensitiveWords();
        if($this->status=='人工审核') exit;
        $mongo = $this->getMondongo();
        $wiki_repo = $mongo->getRepository("Wiki");
        
        //ob_end_flush();
        //echo str_pad(" ", 4096); 
        $query=array();
        $wiki_count = $wiki_repo->count($query);
        echo "count:",$wiki_count,"<br/>";
        sleep(1);
        $i = 0;
        while ($i < $wiki_count) 
        {
            $wikis = $wiki_repo->find(array("query"=>$query,"sort" => array("created_at" => 1), "skip" => $i, "limit" => 50));
            foreach ($wikis as $wiki) 
            {
                $title=$this->updateWiki($wiki);
                if($title!='')
                    echo $title,"<br/>";
            }
            $i = $i + 50;
            echo $i,'*************************************',"<br/>";
            //ob_flush();
            //flush(); 
            sleep(1);
        }  
        return sfView::NONE;
    }
    public function executeCheckAjax(sfWebRequest $request)
    {
        $i=$request->getParameter('i',0);
        $str='';
        $this -> getSensitiveWords();
        
        if($this->status=='人工审核') exit;
        
        $mongo = $this->getMondongo();
        $wiki_repo = $mongo->getRepository("Wiki");
        $wikis = $wiki_repo->find(array("sort" => array("created_at" => 1), "skip" => $i, "limit" => 50));
        foreach ($wikis as $wiki) 
        {
            $title=$this->updateWiki($wiki);
            if($title!='')
                $str.=$title."<br/>";
        }
        $str.=$i."**************<br/>";
        return $this->renderText($str);
    }
    //获取敏感词
    private function getSensitiveWords(){
        $mongo = $this->getMondongo();
        $repository = $mongo->getRepository('words');
        $words_res = $repository->find();
        $arr=array();
        foreach($words_res as $rs){
            $arr[] = $rs->getWord();
        }
        $words=implode(',',$arr);
        $this->patterns=Common::getSensitiveWords($words);
        //查询敏感词状态
        $setting_repository = $mongo->getRepository('Setting');
        $rs = $setting_repository->findOne(array('query' => array( "key" => 'words' )));
        if($rs){
            $this->status=$rs->getValue();
        }
    }
    //更新wiki
    private function updateWiki($wikiinfo){
        $mongo = $this->getMondongo();
        $wordLog_res = $mongo->getRepository('WordsLog');
        
        $wiki_title=$wikiinfo->getTitle();
        $wiki_content=$wikiinfo->getContent();
        $wikititle = preg_replace($this->patterns, "*", $wiki_title);
        $content = preg_replace($this->patterns, "*", $wiki_content);
        $return = '';
        //敏感词日志记录
        if($this->status=='半自动审核'){
            $wikititlea = '';
            $contenta = '';
            $verify = 0;
        }else{
            //自动审核
            $wikititlea = $wikititle;
            $contenta = $content;
            $verify = 1;
        }
        $wiki_id=(string)$wikiinfo->getId();
        if($wikititle!=$wiki_title){
            $wordlog = $wordLog_res->findOne(array('query'=>array('from_id'=>$wiki_id)));
            if(!$wordlog){
                $words=new WordsLog();
                $words->setWord($wiki_title);
                $words->setReword($wikititlea);
                $words->setFrom('wiki');
                $words->setFromId($wiki_id);
                $words->setStatus($verify);
                $words->save(); 
                if($this->status=='自动审核'){
                    $wikiinfo->setTitle($wikititle);
                    $wikiinfo->save();  
                }else{
                    $wikiinfo->setVerify(0);
                    $wikiinfo->save();
                }
            }
            $return=$wiki_title;
        }
        if($content!=$wiki_content){
            $wordlog = $wordLog_res->findOne(array('query'=>array('from_id'=>$wiki_id)));
            if(!$wordlog){
                $words=new WordsLog();
                $words->setWord($wiki_content);
                $words->setReword($contenta);
                $words->setFrom('wiki');
                $words->setFromId($wiki_id);
                $words->setStatus($verify);
                $words->save(); 
                if($this->status=='自动审核'){
                    $wikiinfo->setContent($content);
                    $wikiinfo->save();  
                }else{
                    $wikiinfo->setVerify(0);
                    $wikiinfo->save();
                }
            }
            $return=$wiki_title;
        }
        return $return;
    }    
}

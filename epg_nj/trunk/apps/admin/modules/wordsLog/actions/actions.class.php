<?php

/**
 * wordsLog actions.
 *
 * @package    epg2.0
 * @subpackage wordsLog
 * @author     Huan Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class wordsLogActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
    public function executeIndex(sfWebRequest $request)
    {
        $query_arr=array();
    	$this->status = $request->getParameter('status',-1);
    	if($this->status!=-1){
            $query_arr['status']= intval($this->status);
        }
        $this->pager = new sfMondongoPager('WordsLog', 20);
        $this->pager->setFindOptions(array('query'=>$query_arr,'sort' => array('created_at' => -1)));
        $this->pager->setPage($request->getParameter('page', 1));
        $this->pager->init();
        $this->patterns = $this->getSensitiveWords();
    }
    public function executeDelete(sfWebRequest $request)
    {
        $id = $request->getParameter('id');
        $mongo = $this->getMondongo();
        $wordres = $mongo->getRepository('WordsLog');
        $wiki_res = $mongo->getRepository("Wiki");
        $words=$wordres->findOneById(new MongoId($id));
        //同时自动审核wiki 
        $wiki =$wiki_res ->findOneById(new MongoId($words->getFromId()));
        if($wiki){
            $wiki->setVerify(1);
            $wiki->save();
        }
        //删除该条日志  
        $query = array( "_id" => new MongoId($id)); 
        $wordres->remove($query);
        
        $this->getUser()->setFlash('notice', '已删除！');
        $this->redirect('wordsLog/index');
    }    
    public function executeView(sfWebRequest $request)
    {
        $id = $request->getParameter('id');
        $mongo = $this->getMondongo();
        $repository = $mongo->getRepository('WordsLog');
        $words=$repository->findOneById(new MongoId($id));
        if($words){
            $content = $words->getWord();
            $patterns = $this->getSensitiveWords();
            foreach($patterns as $value){
                $value = str_replace('/','',$value);
                $content = str_replace($value,'<font style="color:#ff0000">'.$value.'</font>',$content);
                //$content1 = strtr($content,$value,'<font style="color:#ff0000">'.$value.'</font>');
            }
        }
        echo $content;
        exit;
        //$this->content = $content;
    }    
    public function executeViewRe(sfWebRequest $request)
    {
        $id = $request->getParameter('id');
        $mongo = $this->getMondongo();
        $repository = $mongo->getRepository('WordsLog');
        $words=$repository->findOneById(new MongoId($id));
        if($words){
            $content = $words->getReWord();
            $pattern = $words->getResensitive();
            if($pattern){
                $patterns = explode(',',$pattern);
                foreach($patterns as $value){
                    $content = str_replace($value,'<font style="color:#ff0000">'.$value.'</font>',$content);
                    //$content1 = strtr($content,$value,'<font style="color:#ff0000">'.$value.'</font>');
                }
            }
            $content = str_replace('*','<font style="color:#ff0000">*</font>',$content);
        }
        echo $content;
        exit;
        //$this->content = $content;
    }     
    public function executeBatchDelete(sfWebRequest $request)
    {
       if($request->isMethod("POST"))
       {
           $ids = $request->getPostParameter('ids');
           if(count($ids)==0)
           {
               $this->getUser()->setFlash("error",'删除失败！请选择需要删除的日志！');
           }else{
               $mongo = $this->getMondongo();
               $words_mongo = $mongo->getRepository("WordsLog");
               $wiki_res = $mongo->getRepository("Wiki");
               foreach($ids as $id){
                    $words = $words_mongo->findOneById(new MongoId($id));
                    //自动审核wiki 
                    $wiki =$wiki_res ->findOneById(new MongoId($words->getFromId()));
                    if($wiki){
                        $wiki->setVerify(1);
                        $wiki->save();
                    }
                    //删除
                    $words -> delete();
               }
               $this->getUser()->setFlash("notice",'删除成功!');
           }
       }
       $this->redirect($this->generateUrl('',array('module'=>'wordsLog','action'=>'index')));
    }
    
    public function executeVerify(sfWebRequest $request)
    {
       $ids = $request->getParameter('ids');
       $id = $request->getParameter('id');
       if($id){
           $ids=array($id);
       }
       if(count($ids)==0){
           $this->getUser()->setFlash("error",'请选择需要替换的内容！');
       }else{
           $mongo = $this->getMondongo();
           $words_mongo = $mongo->getRepository("WordsLog");
           $wiki_res = $mongo->getRepository("Wiki");
           $patterns = $this->getSensitiveWords();
           foreach($ids as $id){
               $words = $words_mongo->findOneById(new MongoId($id));
               if($words->getStatus()==1) continue; //如果已替换，跳过
               //进行wiki的替换
               $wiki =$wiki_res ->findOneById(new MongoId($words->getFromId()));
               if($wiki){
                    $wiki_title=$wiki['title'];       //记录后用于110行判断
                    $wiki_content=$wiki['content'];   //记录后用于110行判断
                    $wikititle = preg_replace($patterns, "*", $wiki['title']);
                    $content = preg_replace($patterns, "*", $wiki['content']);
                    $wiki->setTitle($wikititle);
                    $wiki->setContent($content);
                    $wiki->setVerify(1);
                    $wiki->save();
               }
               //记录替换的日志
               if($wikititle!=$wiki_title){
                   $content_replace=$wikititle;
               }else{
                   $content_replace=$content;
               }
               $words -> setResensitive('*');
               $words -> setReword($content_replace);
               $words -> setStatus(1);
               $words -> save();
           }
           $this->getUser()->setFlash("notice",'替换成功!');
       }
       $this->redirect($this->generateUrl('',array('module'=>'wordsLog','action'=>'index')));
    }
    
    public function executeAjaxUpdate(sfWebRequest $request)
    {
        $id = $request->getParameter('log_id');
        $sensitive = $request->getParameter('sensitive');       //敏感词
        $resensitive = $request->getParameter('resensitive');   //置换的词
        $sensitives_arr = explode(',',$sensitive);
        $resensitives = explode(',',$resensitive);
        foreach($sensitives_arr as $value){
            $sensitives[]="/".$value."/";
        }
        $mongo = $this->getMondongo();
        $words_mongo = $mongo->getRepository("WordsLog");
        $wiki_res = $mongo->getRepository("Wiki");
        $patterns = $this->getSensitiveWords();
        $words = $words_mongo->findOneById(new MongoId($id));
        //进行wiki的替换
        $wiki =$wiki_res ->findOneById(new MongoId($words->getFromId()));
        if($wiki){
            $wiki_title=$wiki['title'];       //记录后用于151行判断
            $wiki_content=$wiki['content'];   //记录后用于151行判断
            $wikititle = preg_replace($sensitives, $resensitives, $wiki['title']);
            $content = preg_replace($sensitives, $resensitives, $wiki['content']);
            $wiki->setTitle($wikititle);
            $wiki->setContent($content);
            $wiki->setVerify(1);
            $wiki->save();
            //记录替换的日志
            if($wikititle!=$wiki_title){
               $content_replace=$wikititle;
            }else{
               $content_replace=$content;
            }
            $words -> setReword($content_replace);
            $words -> setSensitive($sensitive);
            $words -> setResensitive($resensitive);
            $words -> setStatus(1);
            $words -> save();
            $return = array('code'=>1, 'msg'=>'更新成功');
        }else{
            $return = array('code'=>0, 'msg'=>'更新失败');
        }
        return $this->renderText(json_encode($return));
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
        return Common::getSensitiveWords($words);
    }
}

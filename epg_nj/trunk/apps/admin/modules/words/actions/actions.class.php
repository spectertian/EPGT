<?php
/**
 * words actions.
 *
 * @package    epg2.0
 * @subpackage words
 * @author     Huan Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class wordsActions extends sfActions
{
    /**
    * Executes index action
    *
    * @param sfRequest $request A request object
    */
    public function executeIndex(sfWebRequest $request)
    {
    	$this->page = $request->getParameter('page', 1);
    	$this->q = $request->getParameter('q','');
        $query_arr=array();
    	if($this->q!=''){
            $query_arr['word']= new MongoRegex("/.*$this->q.*/i");
        }
        $query = array('query'=>$query_arr,'sort' => array('_id' => -1));
        $this->pager = new sfMondongoPager('Words', 100);
        $this->pager->setFindOptions($query);
        $this->pager->setPage($this->page);
        $this->pager->init();
    }
    
    public function executeAdd(sfWebRequest $request)
    {
    	$keyWord = $request->getParameter('addWord');
    	if ($keyWord){
    		$word = new Words();
    		$word -> setWord($keyWord);
    		$word -> save();
    		$this->getUser()->setFlash("notice",'添加成功');
    	}else {
    		$this->getUser()->setFlash("error",'请填写关键词');
    	}
    	$this->redirect($request->getReferer());
    	
    }
    public function executeDelete(sfWebRequest $request)
    {
    	$mongo = $this->getMondongo();
    	$wordRep = $mongo->getRepository('words');
    	
    	$ids = $request->getParameter('ids');
    	if (is_array($ids)){
    		foreach ($ids as $id) {
    			$word = $wordRep -> findOneById(new MongoId($id));
    			$word -> delete();
    		}
    		$this->getUser()->setFlash("notice",'删除成功');
    	}else {
    		$this->getUser()->setFlash("error",'删除失败');
    	}
    	$this->redirect($request->getReferer());
    }
    public function executeImport(sfWebRequest $request)
    {
        if ($request->getMethod() == 'POST') {
            $file = $this->getRequest()->getFiles('wordfile');
            $file_handle = fopen($file['tmp_name'], "r");
        	$mongo = $this->getMondongo();
        	$wordRep = $mongo->getRepository('words');
            $k=0;
            while (!feof($file_handle)) {
               $line = fgets($file_handle);
               if($line){
                    $str=str_replace("\r\n",'',$line);
                    $words = $wordRep->findOne(array('query'=>array('word'=>$str)));
                    if($str!=''&&!$words){
                        $word = new Words();
                        $word -> setWord($str);
                        $word -> save();
                        $k++;
                    }
               }
            }
            $this->getUser()->setFlash("notice",'成功导入'.$k.'个敏感词');
            $this->redirect('words/index');
        }
    }
    public function executeExport(sfWebRequest $request)
    {
    	$mongo = $this->getMondongo();
    	$wordRep = $mongo->getRepository('words');
    	$words = $wordRep->find();
        $text='';
        foreach($words as $word){
            $text .= $word->getWord()."\r\n";
        }
        $this->getResponse()->clearHttpHeaders(); 
        $this->getResponse()->setHttpHeader('Content-Type', 'text/plain'); 
        $this->getResponse()->setHttpHeader('Content-Disposition', 'attachment; filename=words.txt');
        return $this->renderText($text);
    }
}

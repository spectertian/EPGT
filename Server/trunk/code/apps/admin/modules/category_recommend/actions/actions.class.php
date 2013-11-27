<?php
/**
 * category actions.
 *
 * @package    epg
 * @subpackage category
 * @author     Mozi Tek
 * @version    
 */
class category_recommendActions extends sfActions
{
	public function executeIndex(sfWebRequest $request)
    {
			/*$mongo = $this->getMondongo();
			$repository = $mongo->getRepository('CategoryRecommend');
			$arr=$repository->find();              
			print_r($arr);die;*/
			$this->pager = new sfMondongoPager('CategoryRecommend', 20);
			$query_arr=array();
		    $this->pager->setFindOptions(array('query' => $query_arr, 'sort' => array('created_at' => -1)));

            $this->pager->setPage($request->getParameter('page', 1));
            $this->pager->init();
           
			 //print_r($arr);die;
    }

	public function executeAdd(sfWebRequest $request)
	{		
		if($request->isMethod("POST")){
			 $categoryrecommend = new CategoryRecommend();
             $categoryrecommend -> setCategory($request->getParameter('category'));
             $categoryrecommend -> setTemplate($request->getParameter('template'));
 		     $categoryrecommend -> setIsDefault($request->getParameter('is_default'));
  		     $categoryrecommend -> setStartTime($request->getParameter('startime'));
   		     $categoryrecommend -> setEndTime($request->getParameter('endtime'));
			 //var_dump($categoryrecommend);die;
			 if($categoryrecommend -> save()==null){
					$this->getUser()->setFlash("notice",'操作完成！');
				}else{
					$this->getUser()->setFlash('error','操作失败，请重试！');
				}
			$this->redirect("category_recommend/index");       		
		}
	}
}      
<?php

class mediaComponents extends sfComponents {

    /**
     * 文件列表分页显示
     * @param sfWebRequest $request
     * @author zhigang
     */
    public function executeList(sfWebRequest $request) {
        $this->category_id = $this->category_id ? $this->category_id : 0;
        $page = $this->page ? $this->page : 1;
        $this->source_name = $this->source_name ? $this->source_name : '';
        $this->popup = $this->popup or false;
        
        $this->pager = new sfDoctrinePager('Attachments', 10);
		if($this->source_name!='')
		{
			$this->pager->getQuery()
					->where('category_id = ?', $this->category_id)
					->andWhere('source_name like ?','%'.$this->source_name.'%')  
					->orderBy('created_at DESC');
		}
		else{
			$this->pager->getQuery()
					->where('category_id = ?', $this->category_id)
					->orderBy('created_at DESC');
		}
        $this->pager->setPage($page);
        $this->pager->init();
        $this->categorys = Doctrine::getTable('AttachmentCategorys')->getSelectCategorys();
//        $this->attachments = Doctrine::getTable('Attachments')->createQuery()
//                ->where('category_id = ?',$category_id)
//                ->orderBy('created_at DESC')
//                ->execute();
    }
}


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
        $this->wiki_title = $this->wiki_title ? $this->wiki_title : '';
        $this->wiki = $this->wiki ? $this->wiki : '';
        $this->popup = $this->popup or false;
        
        $this->pager = new sfDoctrinePager('Attachments', 10);
    	if($this->wiki_title)
		{
			$this->file_names = array();
			if($this->wiki)
			{
				if($this->wiki->getScreenshots())
					$this->file_names = $this->wiki->getScreenshots();
				if($this->wiki->getCover())
					$this->file_names[] = $this->wiki->getCover();
				if(empty($this->file_names))
					$this->file_names = array('null');	    
			}
			$this->pager->getQuery()
					->whereIn('file_name', $this->file_names)
					->orderBy('created_at DESC');
		}
		elseif($this->source_name!='')
		{
		    if($this->category_id!=0){
    			$this->pager->getQuery()
    					->where('category_id = ?', $this->category_id)
    					->andWhere('source_name like ?','%'.$this->source_name.'%')  
    					->orderBy('created_at DESC');
		    }else{
    			$this->pager->getQuery()
    					->where('source_name like ?','%'.$this->source_name.'%')  
    					->orderBy('created_at DESC');
		    }
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


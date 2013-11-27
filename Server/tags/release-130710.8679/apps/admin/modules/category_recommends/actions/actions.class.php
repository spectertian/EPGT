<?php

/**
 * category_recommends actions.
 *
 * @package    epg2.0
 * @subpackage category_recommends
 * @author     Huan Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class category_recommendsActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
	public function executeIndex(sfWebRequest $request)
    {
    	//类型设定
    	$moldArray = array(
//     				'prgrom' => '节目',	modify by tianzhongsheng-ex@huan.tv 本功能占时屏蔽，需要时在做调整 Time 2013-02-18 15:41:00
    				'vod' => '点播',
    				'theme' => '主题',
    				'ad' => '广告',
					'shortmovie_package' => '短视频包',	//add  by tianzhongsheng-ex@huan.tv 短视频包 Time 2013-02-20 16:00:00
    				);
    				
		$classesArray = array(
    				'电视剧' => '电视剧',
    				'电影' => '电影',
    				'广告' => '广告',
    				'节目' => '节目',
    				'体育' => '体育',
    				'娱乐' => '娱乐',
			    	"少儿" => '少儿',
			        "科教" => '科教',
    				"财经" => '财经',
			        "综合" => '综合',
    				);
    				
		$this->moldArray = $moldArray;
		$this->classesArray = $classesArray;
		$this->PageTitle = '添加模块';
		$this->row = '1';
		$this->column = '1';

    }
    
    //异步加载wike数据
	public function executeLoadWiki(sfWebRequest $request)
    {
        $str='';
        $query = $request->getParameter('query');
        $mongo =  $this->getMondongo();
        $wiki_mongo = $mongo->getRepository("Wiki");
        $this->wikis = $wiki_mongo->likeWikiName($query);

    }

	//异步加载prgrom数据
	public function executeLoadChannel(sfWebRequest $request)
    {
    	$channel_name = $request->getParameter('query');
		$this->channels = Doctrine_Query::create()->from('channel')->where("name like ?","%$channel_name%")->limit(8)->execute();

    }
    
	//异步加载theme数据
	public function executeLoadTheme(sfWebRequest $request)
    {

    	$theme_name = $request->getParameter('query');
		$this->themes = Doctrine_Query::create()->from('theme')->where("title like ?","%$theme_name%")->limit(8)->execute();


    }
    
	//异步加载ad数据
	public function executeLoadAd(sfWebRequest $request)
    {
        $str='';
        $query = $request->getParameter('query');
        $mongo =  $this->getMondongo();
        $ad_mongo = $mongo->getRepository("SimpleAdvert");
        $this->ads = $ad_mongo->likeSimpleAdvertName($query);

    }
    
    //接收模板异步数据
    function executeGetdata(sfWebRequest $request)
    {
	    if($request->isMethod("POST"))
		{
			$id= $request->getParameter('id');
			$name = $request->getParameter('name');
			$template = $request->getParameter('template');
			$category = $request->getParameter('category');
			$start_time = $request->getParameter('start_time');
			$end_time = $request->getParameter('end_time');
			$is_default = $request->getParameter('is_default');
			
			$stockjson = json_decode($template);
			$re = $this->object_to_array($stockjson);
			$re = array_filter($re);
			// 需求更改后，模板可以为空 Modify by tianzhongsheng-ex@huan.tv Time 2013-03-18 10:08:00
//			if(count($re) < 1)
//			{
//				echo 'false';
//				exit;
//			}
			
			/*
			 * @desc 二位数组入库前进行排序，在前端不再进行排序，减少逻辑判断
			 * @author tianzhongsheng-ex@huan.tv
			 * @time 2013-03-12 13:03:00
			 */
			
			$re = $this->multi_array_sort($re,'row','column');
			
			$template = json_encode($re);
			
			// 需求更改后，模板可以为空 Modify by tianzhongsheng-ex@huan.tv Time 2013-03-18 10:08:00
			if($name == ''  || $category == '' || $start_time == '' || $end_time == '' || $is_default == '')
			{
				echo 'false';
				exit;
			}
			
			//修改数据
			if(!empty($id))
			{
				$id = strval($request->getParameter('id'));
				$category_recommends = self::getMdb()->findOneByID(new MongoId($id));
				if($category_recommends ==NULL)
				{
					echo 'false';
					exit(2);
				}
				$category_recommends->setName($name);
				$category_recommends->setCategory($category);
				$category_recommends->setTemplate($template);
				$category_recommends->setStartTime($start_time);
				$category_recommends->setEndTime($end_time);
				if($is_default == 'yes')
				{
					$mongo =  $this->getMondongo();
					$CategoryRecommend_mongo = $mongo->getRepository("CategoryRecommend");
					$query = array('query' => array( "category" => $category ));
					$re_cate = $CategoryRecommend_mongo->find($query);
					foreach($re_cate as &$redata)
					{
							
						$redata->setIsDefault(false);
						$redata->save();
							
					}
					
					$category_recommends->setIsDefault(true);
					
					
				}
				if($is_default == 'no')
				{
					$category_recommends->setIsDefault(false);
				}
				
				$category_recommends->save();
				echo 'true';
				exit;
				
			}
			if($is_default == 'yes')
			{
				
				$mongo =  $this->getMondongo();
				$CategoryRecommend_mongo = $mongo->getRepository("CategoryRecommend");
				$query = array('query' => array( "category" => $category ));
				$re_cate = $CategoryRecommend_mongo->find($query);
				
				foreach($re_cate as &$redata)
				{
					
					$redata->setIsDefault(false);
					$redata->save();
										
				}
				
				$sCateRe = new CategoryRecommend();
				$sCateRe->setName($name);
				$sCateRe->setCategory($category);
				$sCateRe->setTemplate($template);
				$sCateRe->setStartTime($start_time);
				$sCateRe->setEndTime($end_time);
				$sCateRe->setIsDefault(true);
				$sCateRe->save();
				$id = $sCateRe->getId();
				echo $id;
				exit;
				
			}
			if($is_default == 'no')
			{
				$sCateRe = new CategoryRecommend();
				$sCateRe->setName($name);
				$sCateRe->setCategory($category);
				$sCateRe->setTemplate($template);
				$sCateRe->setStartTime($start_time);
				$sCateRe->setEndTime($end_time);
				$sCateRe->setIsDefault(false);
				$sCateRe->save();
				$id = $sCateRe->getId();
				echo $id;
				exit;
			}
			echo 'false';
			exit;
			
		}
    }
    
    //模板列表
    function executeList(sfWebRequest $request)
    {
      	$this->name = $request->getParameter('name');
		$this->category = $request->getParameter('category');
		$this->start_time = $request->getParameter('start_time');
      	$this->end_time = $request->getParameter('end_time');
      	
      	$begin_mktime = strtotime($this->start_time);
      	$end_mktime = strtotime($this->end_time);
    	$this->pageTitle = '分类管理';
	    $this->pager = new sfMondongoPager('CategoryRecommend', 20);
	    
	    $querys=array();
		$sort=array('created_at' => -1);
 		if($this->name != '')
 		{
			$name = "/.*".$this->name.".*/i";
			$name = new MongoRegex($name);
 			$querys['name'] = $name;
 		}
    	if($this->category != '')
 		{
 			$category = "/.*".$this->category.".*/i";
			$category = new MongoRegex($category);
 			$querys['category'] = $category;
 		}
 		
		if($begin_mktime != '' && $end_mktime != '' && $begin_mktime > $end_mktime)
 		{
 			$this->getUser()->setFlash("error",'起始日期不能大约结束日期!');
 			$this->redirect($this->generateUrl('',array('module'=>'category_recommends','action'=>'list')));
 		}
 		
 		if($begin_mktime != '' )
 		{
// 			$begin_mktime = new MongoDate($begin_mktime);
// 			$querys['start_time'] = array('$gte' => $begin_mktime);
			$querys['start_time'] = array('$gte' =>$this->start_time);
 		}
 		
    	if($end_mktime != '' )
 		{
// 			$end_mktime = new MongoDate($end_mktime);
// 			$querys['end_time'] = array('$lte' => $end_mktime);
			$querys['end_time'] = array('$lte' => $this->end_time);
 		}

		$this->pager->setFindOptions(array('query' => $querys, 'sort' => $sort));
	    $this->pager->setPage($request->getParameter('page', 1));
	    $this->pager->init();
    	
    }
    
    //删除多个数据
	public function executeBatchDelete(sfWebRequest $request)
	{
		$ids = $request->getParameter('id');
		foreach($ids as $v)
		{
			$this->ad = self::getMdb()->findOneByID(new MongoId($v));
			if($this->ad)
			{
				$this->ad->delete();
			}
		}
		$this->getUser()->setFlash("notice",'删除成功!');
		$this->redirect($this->generateUrl('',array('module'=>'category_recommends','action'=>'list')));
	}
	
	//删除单个数据
	public function executeDelete(sfWebRequest $request)
	{
		$id = strval($request->getParameter('id'));
		$this->ad = self::getMdb()->findOneByID(new MongoId($id));
		if($this->ad)
		{
			if(!$this->ad->delete())
				$this->getUser()->setFlash("notice",'删除成功!');
			else
				$this->getUser()->setFlash("error",'删除失败!');
			}else{
				$this->getUser()->setFlash("error",'该记录不存在!');
				$this->forwardUnless($this->ad, 'category_recommends', 'list');
		}
		$this->redirect($this->generateUrl('',array('module'=>'category_recommends','action'=>'list')));
	}
	
	
	//编辑分类
	public function executeEdit(sfWebRequest $request)
	{
		$admin_id = $this -> getUser()->getAttribute('adminid');
		$id = strval($request->getParameter('id'));
		// 获取json 的值
		if($request->getParameter('ids') != '')
		{
			$id = strval($request->getParameter('ids'));
			echo $_SESSION['category_recommendsActions_'.$admin_id.'_'.$id]; 
			exit(2);
		}
		//类型设定
		$moldArray = array(
//     			'prgrom' => '节目',	modify by tianzhongsheng-ex@huan.tv 本功能占时屏蔽，需要时在做调整 Time 2013-02-18 15:41:00
				'vod' => '点播',
				'theme' => '主题',
				'ad' => '广告',
				'shortmovie_package' => '短视频包',	//add  by tianzhongsheng-ex@huan.tv 短视频包 Time 2013-02-20 16:00:00
				
		);
		
		//add by tianzhongsheng-ex@huan.tv	定义图片跳转的路径	Time 2013-03-13 15:00:00
		$skip_img_url_array = array(
		
				'vod' => array("/wiki/edit/id/","wiki_id"),
				'theme' => array("/theme/listwikis/id/","theme_id"),
				'ad' => array("/simple_ad/edit/id/","ad_id"),
				'shortmovie_package' => array("/shortmovie_package/edit/id/","shortmovie_package_id"),
		);
		
		$classesArray = array(
				'电视剧' => '电视剧',
				'电影' => '电影',
				'广告' => '广告',
				'节目' => '节目',
				'体育' => '体育',
				'娱乐' => '娱乐',
				"少儿" => '少儿',
				"科教" => '科教',
				"财经" => '财经',
				"综合" => '综合',
		);
		
		$this->moldArray = $moldArray;
		$this->classesArray = $classesArray;
		$this->row = '1';
		$this->column = '1';
		
		$this->PageTitle = '模块修改';
		$this->ids = $id;
		$templates = $this->ad = self::getMdb()->findOneByID(new MongoId($id));
		
		if($templates)
		{
			$template = $templates->getTemplate();
			$this->name = $templates->getName();
			$this->is_default = $templates->getIsDefault();
			$this->start_time = $templates->getStartTime();
			$this->end_time = $templates->getEndTime();
			$this->category = $templates->getCategory();
			$js_template = json_decode($template);
			$js_template = $this->object_to_array($js_template);
			
			$re_arr = array();
			$re_key = array();
			foreach($js_template as $k =>$v)
			{
				if(!in_array($v['row'], $re_key))
				{
					$re_arr[$v['row']] = array();
				}	

				array_push($re_arr[$v['row']] ,$v);
				array_push($re_key,$v['row']);
			}
			$this->templates = $re_arr;
			$this->skip_url = $skip_img_url_array;
			$this->js_tem = json_encode($re_arr);
			$_SESSION['category_recommendsActions_'.$admin_id.'_'.$id] = $this->js_tem;
		}else
		{
			
// 			$this->getUser()->setFlash("error",'xxxx');
// 			$this->forwardUnless($this->ad, 'category_recommends', 'list');
		}
	}
	
	//推荐预览 2013-03-13 10：21：00
	public function executePreview(sfWebRequest $request)
	{
		
		$this->PageTitle = '推荐预览';
		$id = strval($request->getParameter('id'));
		$this->ids = $id;
		$templates = $this->ad = self::getMdb()->findOneByID(new MongoId($id));

		if($templates)
		{
			$template = $templates->getTemplate();
			$this->name = $templates->getName();
			$this->is_default = $templates->getIsDefault();
			$this->start_time = $templates->getStartTime();
			$this->end_time = $templates->getEndTime();
			$this->category = $templates->getCategory();
			$js_template = json_decode($template);
			$js_template = $this->object_to_array($js_template);
			
			$re_arr = array();
			$re_key = array();
			foreach($js_template as $k =>$v)
			{
				if(!in_array($v['row'], $re_key))
				{
					$re_arr[$v['row']] = array();
				}	

				array_push($re_arr[$v['row']] ,$v);
				array_push($re_key,$v['row']);
			}

			$this->templates = $re_arr;
		}
	}

/**
   * Get mongodb handler
   * @return mongo | object
   */
	public static $mdb = null;
	public function getMdb()
	{
		if(null == self::$mdb)
		{
			$mongo = $this->getMondongo();
			return self::$mdb = $mongo->getRepository("CategoryRecommend");
		}else
		{
			return self::$mdb;
    	}
	}
	
	//把对象转成数组
	function object_to_array($obj)
	{
		$arr = is_object($obj) ? get_object_vars($obj) : $obj;
		foreach ($arr as $key => $val)
		{
			$val = (is_array($val) || is_object($val)) ? $this->object_to_array($val) : $val;
			$arr[$key] = $val;
		}
		return $arr;
	}
	
	/*
	 * @desc 多维数组的排序
	 * @author tianzhongsheng-ex@huan.tv
	 * @time 2013-03-14 17:08:00
	 */
	function multi_array_sort($multi_array,$sort_one_field,$sort_second_field,$sort_type = SORT_ASC)
	{
		if (!is_array($multi_array)) return FALSE;
		foreach ($multi_array as $row)
		{
			if(! is_array($row)) return FALSE;
			$arr_one_field[] = $row[$sort_one_field];
			$arr_second_field[] = $row[$sort_second_field];
		}
		array_multisort($arr_one_field,$sort_type,$arr_second_field,$sort_type,$multi_array);
		return $multi_array;
	}
	
	//异步加载shortmovie_package数据 add by tianzhongsheng-ex@huan.tv Time 2013-02-20 16:10:00
	public function executeLoadShortMoviePackage(sfWebRequest $request)
	{
		$str='';
		$query = $request->getParameter('query');
		$mongo =  $this->getMondongo();
		$short_movie_package_mongo = $mongo->getRepository("ShortMoviePackage");
		$this->short_movie_packages = $short_movie_package_mongo->likeShortMoviePackageName($query);
	
	}
	  
}

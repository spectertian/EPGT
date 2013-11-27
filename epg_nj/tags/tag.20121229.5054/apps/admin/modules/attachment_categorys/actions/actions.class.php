<?php

require_once dirname(__FILE__).'/../lib/attachment_categorysGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/attachment_categorysGeneratorHelper.class.php';

/**
 * attachmentCategorys actions.
 *
 * @package    epg
 * @subpackage attachmentCategorys
 * @author     Mozi Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class attachment_categorysActions extends autoAttachment_categorysActions
{
     public function executeIndex(sfWebRequest $request)
     {
         if($request->isXmlHttpRequest() && $request->isMethod('GET'))
         {
             $this->getResponse()->setHttpHeader('Content-type', 'application/json;charset=UTF-8');
             $category_id = $request->getParameter('category_id',0);
             $child_categorys = Doctrine::getTable('AttachmentCategorys')->createQuery()->where('parent_id = ?',$category_id)->execute();

             if($category_id == 0)
             {
                 $categorys = array(
                                'data' =>'Root Node',
                                'status'=>'open',
                                'attr'=>array(
                                        'id'=>'node_0',
                                ),
                              );
                 
                 foreach($child_categorys as $key => $child_category)
                 {
                     $categorys['children'][$key]['attr']['rel'] = $child_category->getId();
                     $categorys['children'][$key]['attr']['id'] = 'node_'.$child_category->getId();
                     $categorys['children'][$key]['data'] = $child_category->getName();
                     $categorys['children'][$key]['state'] = 'closed';
                 }
             }else{
                $categorys = array();
                foreach($child_categorys as $key => $child_category)
                 {
                     $categorys[$key]['attr']['rel'] = $child_category->getId();
                     $categorys[$key]['attr']['id'] = 'node_'.$child_category->getId();
                     $categorys[$key]['data'] = $child_category->getName();
                     $categorys[$key]['state'] = 'closed';
                 }
             }
             return $this->renderText(json_encode($categorys));
         }
         return;
     }

     public function executeChange( sfWebRequest $request )
     {
         if($request->isXmlHttpRequest() && $request->isMethod('POST'))
         {
             $this->getResponse()->setHttpHeader('Content-type', 'application/json;charset=UTF-8');
             $id = $request->getParameter('id');
             $name = $request->getParameter('new_name');
             $category = Doctrine::getTable('AttachmentCategorys')->createQuery()->where('id = ?',$id)->fetchOne();
             $category->setName($name);
             $category->save();
             $status = array();
             if(!$category)
             {
                $status['status'] = 0;
             }else{
                $status['status'] = $category->getId();
             }
             return $this->renderText(json_encode($status));
         }

         return;
     }

     public function executePre_remove( sfWebRequest $request )
     {
         
         if($request->isXmlHttpRequest() && $request->isMethod('POST'))
         {
             $this->getResponse()->setHttpHeader('Content-type', 'application/json;charset=UTF-8');
             $id = $request->getParameter('id',0);
             $category_files = Doctrine::getTable('Attachments')->createQuery()->where('category_id = ?',$id)->execute();
             $category = Doctrine::getTable('AttachmentCategorys')->createQuery()->where('id = ?',$id)->fetchOne();
             $status = array();
             if(!($category->isParentCategory()))
             {
                 if( $category_files->count() > 0 )
                 {
                     $status['status'] = 2; //此分类下有文件
                 }else{
                     $status['status'] = 0; //当前分类下无分类以及当前分类下无文件
                 }
             }else{
                  $status['status'] = 1;    //当前分类下有分类
             }
             
             return $this->renderText(json_encode($status));
         }

         return;
     }

     public function executeAdd_category( sfWebRequest $request )
     {
         if($request->isXmlHttpRequest() && $request->isMethod('POST'))
         {
             $this->getResponse()->setHttpHeader('Content-type', 'application/json;charset=UTF-8');
             $parent_id = $request->getParameter('parent_id',0);
             $name = $request->getParameter('name',null);
             $category = new AttachmentCategorys();
             $category->setParentId($parent_id)
                      ->setName($name);
             $category->save();
             $status = array();
             $status['id'] = $category->getId();
             $status['name'] = $category->getName();
             return $this->renderText(json_encode($status));
         }
         return;
     }

     public function executeRemove_category( sfWebRequest $request )
     {
        if($request->isXmlHttpRequest() && $request->isMethod('POST'))
        {
             $this->getResponse()->setHttpHeader('Content-type', 'application/json;charset=UTF-8');
             $id = $request->getParameter('id',0);
             $category = Doctrine::getTable('AttachmentCategorys')->createQuery()->where('id = ?',$id)->fetchOne();
             $status = array();
             if($category->isParentCategory())
             {
                 $status['status'] = 0;
             }else{
                 $status['status'] = $category->getId();
                 $category->delete();
             }
             return $this->renderText(json_encode($status));
         }
         return;
     }

     public function executeSelect_categorys( sfWebRequest $request )
     {
         $this->categorys = Doctrine::getTable('AttachmentCategorys')->getSelectCategorys();
     }


}

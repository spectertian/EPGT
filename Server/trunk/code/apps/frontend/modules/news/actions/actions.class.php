<?php

/**
 * news actions.
 *
 * @package    epg
 * @subpackage news
 * @author     Mozi Tek
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class newsActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    //$this->forward('default', 'module');
  }
  
  /**
   * 显示帮助中心内心
   */
  public function executeHelp(sfWebRequest $request)
  {
      $this->getResponse()->setTitle("帮助中心 - 我爱电视");
  }
  
  /**
   * 关于我们
   */
  public function executeAbout(sfWebRequest $request)
  {
      $this->getResponse()->setTitle("关于我们 - 我爱电视");
  }
  
  /**
   * 免责声明
   */
  public function executeDisclaimer(sfWebRequest $request)
  {
      $this->getResponse()->setTitle("免责声明 - 我爱电视");
  }
  
  /**
   * 隐私声明
   */
  public function executePrivacy(sfWebRequest $request)
  {
      $this->getResponse()->setTitle("隐私声明 - 我爱电视");
  }
  
  /**
   * 合作伙伴
   */
  public function executePartner(sfWebRequest $request)
  {
      $this->getResponse()->setTitle("合作伙伴 - 我爱电视");
  }
  
  /**
   *  加入我们
   */
  public function executeEmploy(sfWebRequest $request)
  {
      $this->getResponse()->setTitle("加入我们 - 我爱电视");
  }
  
  /**
   *  联系我们
   */
  public function executeContact(sfWebRequest $request)
  {
      $this->getResponse()->setTitle("联系我们 - 我爱电视");
  }
  
  /**
   * 协议
   */
  public function executeAgreement(sfWebRequest $request)
  {
      $this->getResponse()->setTitle("使用协议 - 我爱电视");
  }
  
}

<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Administrator
 * Date: 11-7-13
 * Time: 下午2:09
 * To change this template use File | Settings | File Templates.
 */
 
class userComponents extends sfComponents {

    /**
    * 用户信息
     * @param sfWebRequest $request
     * @author lizhi
     * @return void|obj
     */
    public function executeUser_borad(sfWebRequest $request) {
        $user_id = $request->getParameter('uid',0);
        if($user_id==0) {
            $user_id = $this->getUser()->getAttribute('user_id');
        }
        $mongo = $this->getMondongo();
        $userRep = $mongo->getRepository('User');
        $this->user = $userRep->findOneById(new MongoId($user_id));

        if(!$this->user->getProvince()) {
          $this->province = $this->city[36];
        }else{
          $this->province = $this->city[$this->user->getProvince()];
        }
        $this->desc = $this->user->getDesc();
    }
    
    /**
    * 用户信息的汇总
    * @param sfWebRequest $request
    * @author lizhi
    * @return void | obj
    */
    public function executeUser_summ(sfwebRequest $request) {
        $user_id = $request->getParameter('uid', 0);
        if($user_id==0) {
            $user_id = $this->getUser()->getAttribute('user_id');
        }
        $mongo = $this->getMondongo();
        $SingleChipRep = $mongo->getRepository('SingleChip');
        $chipList = $SingleChipRep->getUserChipByUserId($user_id,'','');
        $this->chiptotal = count($chipList);
        $commentRep = $mongo->getRepository('Comment');
        $commentList = $commentRep->getCommentsByUserId($user_id, '', '');
        $this->commenttotal = count($commentList);
        $channelRep = $mongo->getRepository('channelfavorites');
        $channelList = $channelRep->getChannelByUserId($user_id, '', '');
        $this->channeltotal = count($channelList);
    }
    
    private $city = array(
        '36'=>"未知",
		'67'=>"北京市",
		'68'=>"天津市",
		'69'=>"上海市",                                    
		'70'=>"重庆市",
        '37'=>"湖北",
		'38'=>"广东",
		'39'=>"江西",
		'40'=>"安徽",
		'41'=>"福建",
		'42'=>"广西",
		'43'=>"云南",
		'44'=>"四川",
		'45'=>"贵州",
		'46'=>"湖南",
		'47'=>"浙江",
		'48'=>"江苏",
		'49'=>"河南",
		'50'=>"河北",
		'51'=>"山东",
		'52'=>"山西",
		'53'=>"陕西",
		'54'=>"甘肃",
		'55'=>"青海",
		'56'=>"宁夏",
		'57'=>"内蒙古自治区",
		'58'=>"辽宁",
		'59'=>"吉林",
		'60'=>"黑龙江",
		'61'=>"新疆自治区",
		'62'=>"西藏自治区",
		'63'=>"海南",
		'64'=>"澳门",
		'65'=>"香港",
		'66'=>"台湾",
    );
}

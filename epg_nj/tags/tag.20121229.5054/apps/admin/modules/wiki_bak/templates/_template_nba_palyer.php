<script>
    tinymc_init();

function nba_player_img(key,url){
    $("#nba_nba_player_img_value_show").attr('src',url);
    $("#nba_nba_player_img_value").val(key);
    $("#nba_nba_player_img_value_show").show();
    tb_remove();
}
</script>
<div class="col width-60">
    <fieldset class="adminform">
        <legend>球员阵容</legend>
        <table class="admintable" id="html">
            <tbody>
                <tr>
                    <td class="key"><label for="wiki_title">球员</label></td>
                    <td>
                        <input type="text" id="wiki_title" name="wiki[title]" value="<?php echo $form->getObject()->getTitle();?>" size="40">
                        <input type="hidden" id="wiki_style" name="wiki[style]" value="<?php echo $sf_request->getParameter('style');?>">
                        赛季球员数据:<input type="text" name="no" value="" id="html_add" size="10">
                        <input type="button" value="添加" onclick="add_nba_team_palyer_html()">

                    </td>
                </tr>
                <tr>
                    <td class="key"><label for="nba_id">头像</label></td>
                    <td>
                        <input type="hidden" id="nba_nba_player_img_key" name="ext[NbaPlayerImg][WikiKey]"  value="nba_player_img" >
                        <input type="hidden" id="nba_nba_player_img_sort" name="ext[NbaPlayerImg][Sort]" value="0">
                        <input type="hidden" id="nba_nba_player_img_value" name="ext[NbaPlayerImg][WikiValue]" value="<?php echo $form->getObject()->getNbaPlayerImg();?>" size="70">
                        <a href="<?php echo url_for('media/link')?>?function_name=nba_player_img&height=600&width=600&TB_iframe=false" class="thickbox" onclick="global_save('team_image');">上传照片</a>
                        <br/>
                        <?php $pic  =  file_url($form->getObject()->getNbaPlayerImg());
                            if(!$pic){
                                $show   = 'none';
                            }
                        ?>
                        <img id="nba_nba_player_img_value_show" src="<?php echo $pic;?>" alt="加载中" style="display: <?php echo $show;?>"/>
                    </td>
                </tr>
                <tr>
                    <td class="key"><label for="nba_id">编号</label></td>
                    <td>
                        <input type="hidden" id="nba_id_key" name="ext[NbaId][WikiKey]"  value="nba_id" >
                        <input type="hidden" id="nba_id_sort" name="ext[NbaId][Sort]" value="0">
                        <input type="text"   id="nba_id_value" name="ext[NbaId][WikiValue]" value="<?php echo $form->getObject()->getNbaId();?>" size="70">
                    </td>
                </tr>
                <tr>
                    <td class="key"><label for="nba_id">英文名</label></td>
                    <td>
                        <input type="hidden" id="nba_ename_key" name="ext[NbaEname][WikiKey]"  value="nba_ename" >
                        <input type="hidden" id="nba_ename_sort" name="ext[NbaEname][Sort]" value="0">
                        <input type="text"   id="nba_ename_value" name="ext[NbaEname][WikiValue]" value="<?php echo $form->getObject()->getNbaEname();?>" size="70">
                    </td>
                </tr>
                <tr>
                    <td class="key"><label for="nba_location">位置</label></td>
                    <td>
                        <input type="hidden" id="nba_location_key" name="ext[NbaLocation][WikiKey]"  value="nba_location" >
                        <input type="hidden" id="nba_location_sort" name="ext[NbaLocation][Sort]" value="0">
                        <input type="text"   id="nba_location_value" name="ext[NbaLocation][WikiValue]" value="<?php echo $form->getObject()->getNbaLocation();?>" size="70">
                    </td>
                </tr>
                
                <tr>
                    <td class="key"><label for="height">身高</label></td>
                    <td>
                        <input type="hidden" id="nba_height_key" name="ext[NbaHeight][WikiKey]"  value="nba_height" >
                        <input type="hidden" id="nba_height_sort" name="ext[NbaHeight][Sort]" value="0">
                        <input type="text"   id="nba_height_value" name="ext[NbaHeight][WikiValue]" value="<?php echo $form->getObject()->getNbaHeight();?>" size="70">
                    </td>
                </tr>

                <tr>
                    <td class="key"><label for="height">体重</label></td>
                    <td>
                        <input type="hidden" id="nba_weight_key" name="ext[NbaWeight][WikiKey]"  value="nba_weight" >
                        <input type="hidden" id="nba_weight_sort" name="ext[NbaWeight][Sort]" value="0">
                        <input type="text"   id="nba_weight_value" name="ext[NbaWeight][WikiValue]" value="<?php echo $form->getObject()->getNbaWeight();?>" size="70">
                    </td>
                </tr>

                <tr>
                    <td class="key"><label for="age">年龄</label></td>
                    <td>
                        <input type="hidden" id="nba_age_key" name="ext[NbaAge][WikiKey]"  value="nba_age" >
                        <input type="hidden" id="nba_age_sort" name="ext[NbaAge][Sort]" value="0">
                        <input type="text"   id="nba_age_value" name="ext[NbaAge][WikiValue]" value="<?php echo $form->getObject()->getNbaAge();?>" size="70">
                    </td>
                </tr>

                <tr>
                    <td class="key"><label for="birthday">生日</label></td>
                    <td>
                        <input type="hidden" id="nba_birthday_key" name="ext[NbaBirthday][WikiKey]"  value="nba_birthday" >
                        <input type="hidden" id="nba_birthday_sort" name="ext[NbaBirthday][Sort]" value="0">
                        <input type="text"   id="nba_birthday_value" name="ext[NbaBirthday][WikiValue]" value="<?php echo $form->getObject()->getNbaBirthday();?>" size="70">
                    </td>
                </tr>
                <tr>
                    <td class="key"><label for="birthday">出生地</label></td>
                    <td>
                        <input type="hidden" id="nba_birthland_key" name="ext[NbaBirthland][WikiKey]"  value="nba_birthland" >
                        <input type="hidden" id="nba_birthland_sort" name="ext[NbaBirthland][Sort]" value="0">
                        <input type="text"   id="nba_birthland_value" name="ext[NbaBirthland][WikiValue]" value="<?php echo $form->getObject()->getNbaBirthland();?>" size="70">
                    </td>
                </tr>

                <tr>
                    <td class="key"><label for="wages">年薪</label></td>
                    <td>
                        <input type="hidden" id="nba_wages_key" name="ext[NbaWages][WikiKey]"  value="nba_wages" >
                        <input type="hidden" id="nba_wages_sort" name="ext[NbaWages][Sort]" value="0">
                        <input type="text"   id="nba_wages_value" name="ext[NbaWages][WikiValue]" value="<?php echo $form->getObject()->getNbaWages();?>" size="70">
                    </td>
                </tr>

                <tr>
                    <td class="key"><label for="nba_year">进入NBA</label></td>
                    <td>
                        <input type="hidden" id="nba_year_key" name="ext[NbaYear][WikiKey]"  value="nba_year" >
                        <input type="hidden" id="nba_year_sort" name="ext[NbaYear][Sort]" value="0">
                        <input type="text"   id="nba_year_value" name="ext[NbaYear][WikiValue]" value="<?php echo $form->getObject()->getNbaYear();?>" size="70">
                    </td>
                </tr>
                <tr>
                    <td class="key"><label for="nba_year">毕业大学</label></td>
                    <td>
                        <input type="hidden" id="nba_graduated_university_key" name="ext[NbaGraduatedUniversity][WikiKey]"  value="nba_graduated_university" >
                        <input type="hidden" id="nba_graduated_university_sort" name="ext[NbaGraduatedUniversity][Sort]" value="0">
                        <input type="text"   id="nba_graduated_university_value" name="ext[NbaGraduatedUniversity][WikiValue]" value="<?php echo $form->getObject()->getNbaGraduatedUniversity();?>" size="70">
                    </td>
                </tr>
                <tr>
                    <td class="key"><label for="nba_draft_situation">选秀情况</label></td>
                    <td>
                        <input type="hidden" id="nba_draft_situation_key" name="ext[NbaDraftSituation][WikiKey]"  value="nba_draft_situation" >
                        <input type="hidden" id="nba_draft_situation_sort" name="ext[NbaDraftSituation][Sort]" value="0">
                        <input type="text"   id="nba_draft_situation_value" name="ext[NbaDraftSituation][WikiValue]" value="<?php echo $form->getObject()->getNbaDraftSituation();?>" size="70">
                    </td>
                </tr>
                <tr>
                            <td width="40%" class="paramlist_key"><span class="editlinktip"><label for="appeared" style="color: red;">季赛平均(得分)</label></span></td>
                            <td class="paramlist_value">
                                    <input type="hidden" id="nba_season_averaging_points_key" name="ext[NbaSeasonAveragingPoints][WikiKey]"  value="nba_season_averaging_points" >
                                    <input type="hidden" id="nba_season_averaging_points_sort" name="ext[NbaSeasonAveragingPoints][Sort]" value="0">
                                    <input type="text"   id="nba_season_averaging_points_value" name="ext[NbaSeasonAveragingPoints][WikiValue]" class="mceNoEditor" value="<?php echo $form->getObject()->getNbaSeasonAveragingPoints();?>" size="70"/>
                            </td>
                       </tr>
                        <tr>
                            <td width="40%" class="paramlist_key"><span class="editlinktip"><label for="appeared" style="color: red;">季赛平均(篮板)</label></span></td>
                            <td class="paramlist_value">
                                    <input type="hidden" id="nba_season_averaging_rebounds_key" name="ext[NbaSeasonAveragingRebounds][WikiKey]"  value="nba_season_averaging_rebounds" >
                                    <input type="hidden" id="nba_season_averaging_rebounds_sort" name="ext[NbaSeasonAveragingRebounds][Sort]" value="0">
                                    <input type="text"   id="nba_season_averaging_rebounds_value" name="ext[NbaSeasonAveragingRebounds][WikiValue]" class="mceNoEditor" value="<?php echo $form->getObject()->getNbaSeasonAveragingRebounds();?>" size="70"/>
                            </td>
                       </tr>
                        <tr>
                            <td width="40%" class="paramlist_key"><span class="editlinktip"><label for="appeared" style="color: red;">季赛平均(助攻)</label></span></td>
                            <td class="paramlist_value">
                                    <input type="hidden" id="nba_season_averaging_assists_key" name="ext[NbaSeasonAveragingAssists][WikiKey]"  value="nba_season_averaging_assists" >
                                    <input type="hidden" id="nba_season_averaging_assists_sort" name="ext[NbaSeasonAveragingAssists][Sort]" value="0">
                                    <input type="text"   id="nba_season_averaging_assists_value" name="ext[NbaSeasonAveragingAssists][WikiValue]" class="mceNoEditor" value="<?php echo $form->getObject()->getNbaSeasonAveragingAssists();?>" size="70"/>
                            </td>
                       </tr>
                        <tr>
                            <td width="40%" class="paramlist_key"><span class="editlinktip"><label for="appeared" style="color: #117a9c;">季赛最高(得分)</label></span></td>
                            <td class="paramlist_value">
                                    <input type="hidden" id="nba_season_highest_points_key" name="ext[NbaSeasonhighestPoints][WikiKey]"  value="nba_season_highest_points" >
                                    <input type="hidden" id="nba_season_highest_points_sort" name="ext[NbaSeasonhighestPoints][Sort]" value="0">
                                    <input type="text"   id="nba_season_highest_points_value" name="ext[NbaSeasonhighestPoints][WikiValue]" class="mceNoEditor" value="<?php echo $form->getObject()->getNbaSeasonhighestPoints();?>" size="70"/>
                            </td>
                       </tr>
                        <tr>
                            <td width="40%" class="paramlist_key"><span class="editlinktip"><label for="appeared" style="color: #117a9c;">季赛最高(篮板)</label></span></td>
                            <td class="paramlist_value">
                                    <input type="hidden" id="nba_season_highest_rebounds_key" name="ext[NbaSeasonhighestRebounds][WikiKey]"  value="nba_season_highest_rebounds" >
                                    <input type="hidden" id="nba_season_highest_rebounds_sort" name="ext[NbaSeasonhighestRebounds][Sort]" value="0">
                                    <input type="text"   id="nba_season_highest_rebounds_value" name="ext[NbaSeasonhighestRebounds][WikiValue]" class="mceNoEditor" value="<?php echo $form->getObject()->getNbaSeasonhighestRebounds();?>" size="70"/>
                            </td>
                       </tr>
                        <tr>
                            <td width="40%" class="paramlist_key"><span class="editlinktip"><label for="appeared" style="color: #117a9c;">季赛最高(助攻)</label></span></td>
                            <td class="paramlist_value">
                                    <input type="hidden" id="nba_season_highest_assists_key" name="ext[NbaSeasonhighestAssists][WikiKey]"  value="nba_season_highest_assists" >
                                    <input type="hidden" id="nba_season_highest_assists_sort" name="ext[NbaSeasonhighestAssists][Sort]" value="0">
                                    <input type="text"   id="nba_season_highest_assists_value" name="ext[NbaSeasonhighestAssists][WikiValue]" class="mceNoEditor" value="<?php echo $form->getObject()->getNbaSeasonhighestAssists();?>" size="70"/>
                            </td>
                       </tr>
                        <tr>
                            <td width="40%" class="paramlist_key"><span class="editlinktip"><label for="appeared" style="color: red;">季赛总计(得分)</label></span></td>
                            <td class="paramlist_value">
                                    <input type="hidden" id="nba_season_all_points_key" name="ext[NbaSeasonallPoints][WikiKey]"  value="nba_season_all_points" >
                                    <input type="hidden" id="nba_season_all_points_sort" name="ext[NbaSeasonallPoints][Sort]" value="0">
                                    <input type="text"   id="nba_season_all_points_value" name="ext[NbaSeasonallPoints][WikiValue]" class="mceNoEditor" value="<?php echo $form->getObject()->getNbaSeasonallPoints();?>" size="70"/>
                            </td>
                       </tr>
                        <tr>
                            <td width="40%" class="paramlist_key"><span class="editlinktip"><label for="appeared" style="color: red;">季赛总计(篮板)</label></span></td>
                            <td class="paramlist_value">
                                    <input type="hidden" id="nba_season_all_rebounds_key" name="ext[NbaSeasonallRebounds][WikiKey]"  value="nba_season_all_rebounds" >
                                    <input type="hidden" id="nba_season_all_rebounds_sort" name="ext[NbaSeasonallRebounds][Sort]" value="0">
                                    <input type="text"   id="nba_season_all_rebounds_value" name="ext[NbaSeasonallRebounds][WikiValue]" class="mceNoEditor" value="<?php echo $form->getObject()->getNbaSeasonallRebounds();?>" size="70"/>
                            </td>
                       </tr>
                        <tr>
                            <td width="40%" class="paramlist_key"><span class="editlinktip"><label for="appeared" style="color: red;">季赛总计(助攻)</label></span></td>
                            <td class="paramlist_value">
                                    <input type="hidden" id="nba_season_all_assists_key" name="ext[NbaSeasonallAssists][WikiKey]"  value="nba_season_all_assists" >
                                    <input type="hidden" id="nba_season_all_assists_sort" name="ext[NbaSeasonallAssists][Sort]" value="0">
                                    <input type="text"   id="nba_season_all_assists_value" name="ext[NbaSeasonallAssists][WikiValue]" class="mceNoEditor" value="<?php echo $form->getObject()->getNbaSeasonallAssists();?>" size="70"/>
                            </td>
                       </tr>
                <tr>
                    <td class="key"><label for="wiki_content">内容</label></td>
                    <td>
                        <textarea name="wiki[content]" class="" style="width: 100%;"><?php echo $form->getObject()->getContent(); ?></textarea>
                    </td>
                </tr>
            </tbody>
        </table>
    </fieldset>
</div>
<div class="col width-40">
    <div class="pane-sliders" id="menu-pane">
        <div class="panel">
            <h3 id="param-page" class="title jpane-toggler-down"><span>球员数据</span></h3>
            <div class="jpane-slider content">
                <table cellspacing="1" width="100%" class="paramlist admintable">
                    <tbody  id="index">
                        <?php $rss   =  json_decode($form->getObject()->getNbaPlayerAttribute()); ?>
                        <?php if(!empty($rss)){?>
                        <?php foreach ($rss as $key => $value) {?>
                       <tr>
                           <td width="40%" class="paramlist_key"><span class="editlinktip"><label for="appeared" style="color:red;">赛季</label></span></td>
                            <td class="paramlist_value">
                                    <input type="text"   id="nba_season" name="no" class="mceNoEditor" value="<?php echo $key;?>" size="70"/>
                            </td>
                       </tr>
                        
                        <tr>
                            <td width="40%" class="paramlist_key"><span class="editlinktip"><label for="appeared">出场</label></span></td>
                            <td class="paramlist_value">
                                    <input type="hidden" id="nba_appeared_key<?php echo $key;?>" name="nba_player[<?php echo $key;?>][NbaSppeared][WikiKey]"  value="nba_appeared" >
                                    <input type="hidden" id="nba_appeared_sort<?php echo $key;?>" name="nba_player[<?php echo $key;?>][NbaSppeared][Sort]" value="0">
                                    <input type="text"   id="nba_appeared_value<?php echo $key;?>" name="nba_player[<?php echo $key;?>][NbaSppeared][WikiValue]" class="mceNoEditor" value="<?php echo $value->NbaSppeared->WikiValue;?>" size="70"/>
                            </td>
                       </tr>
                       
                        <tr>
                            <td width="40%" class="paramlist_key"><span class="editlinktip"><label for="appeared">首发</label></span></td>
                            <td class="paramlist_value">
                                    <input type="hidden" id="nba_starting_key<?php echo $key;?>" name="nba_player[<?php echo $key;?>][NbaStarting][WikiKey]"  value="nba_starting" >
                                    <input type="hidden" id="nba_starting_sort<?php echo $key;?>" name="nba_player[<?php echo $key;?>][NbaStarting][Sort]" value="0">
                                    <input type="text"   id="nba_starting_value<?php echo $key;?>" name="nba_player[<?php echo $key;?>][NbaStarting][WikiValue]" class="mceNoEditor" value="<?php echo $value->NbaStarting->WikiValue;?>" size="70"/>
                            </td>
                       </tr>

                        <tr>
                            <td width="40%" class="paramlist_key"><span class="editlinktip"><label for="time">时间</label></span></td>
                            <td class="paramlist_value">
                                    <input type="hidden" id="nba_time_key<?php echo $key;?>" name="nba_player[<?php echo $key;?>][NbaTime][WikiKey]"  value="nba_time" >
                                    <input type="hidden" id="nba_time_sort<?php echo $key;?>" name="nba_player[<?php echo $key;?>][NbaTime][Sort]" value="0">
                                    <input type="text"   id="nba_time_value<?php echo $key;?>" name="nba_player[<?php echo $key;?>][NbaTime][WikiValue]" class="mceNoEditor" value="<?php echo $value->NbaTime->WikiValue;?>" size="70"/>
                            </td>
                       </tr>
                       
                        <tr>
                            <td width="40%" class="paramlist_key"><span class="editlinktip"><label for="goal">投篮</label></span></td>
                            <td class="paramlist_value">
                                    <input type="hidden" id="nba_goal_key<?php echo $key;?>" name="nba_player[<?php echo $key;?>][NbaGoal][WikiKey]"  value="nba_goal" >
                                    <input type="hidden" id="nba_goal_sort<?php echo $key;?>" name="nba_player[<?php echo $key;?>][NbaGoal][Sort]" value="0">
                                    <input type="text"   id="nba_goal_value<?php echo $key;?>" name="nba_player[<?php echo $key;?>][NbaGoal][WikiValue]" class="mceNoEditor" value="<?php echo $value->NbaGoal->WikiValue;?>" size="70"/>
                            </td>
                       </tr>
                       
                        <tr>
                            <td width="40%" class="paramlist_key"><span class="editlinktip"><label for="goal">三分</label></span></td>
                            <td class="paramlist_value">
                                    <input type="hidden" id="nba_three_point_key<?php echo $key;?>" name="nba_player[<?php echo $key;?>][NbaThreePoint][WikiKey]"  value="nba_three_point" >
                                    <input type="hidden" id="nba_three_point_sort<?php echo $key;?>" name="nba_player[<?php echo $key;?>][NbaThreePoint][Sort]" value="0">
                                    <input type="text"   id="nba_three_point_value<?php echo $key;?>" name="nba_player[<?php echo $key;?>][NbaThreePoint][WikiValue]" class="mceNoEditor" value="<?php echo $value->NbaThreePoint->WikiValue;?>" size="70"/>
                            </td>
                       </tr>
                        <tr>
                            <td width="40%" class="paramlist_key"><span class="editlinktip"><label for="goal">罚球</label></span></td>
                            <td class="paramlist_value">
                                    <input type="hidden" id="nba_free_throw_key<?php echo $key;?>" name="nba_player[<?php echo $key;?>][NbaFreeThrow][WikiKey]"  value="nba_free_throw" >
                                    <input type="hidden" id="nba_free_throw_sort<?php echo $key;?>" name="nba_player[<?php echo $key;?>][NbaFreeThrow][Sort]" value="0">
                                    <input type="text"   id="nba_free_throw_value<?php echo $key;?>" name="nba_player[<?php echo $key;?>][NbaFreeThrow][WikiValue]" class="mceNoEditor" value="<?php echo $value->NbaFreeThrow->WikiValue;?>" size="70"/>
                            </td>
                       </tr>
                        <tr>
                            <td width="40%" class="paramlist_key"><span class="editlinktip"><label for="front_rebounds">前篮板</label></span></td>
                            <td class="paramlist_value">
                                    <input type="hidden" id="nba_front_rebounds_key<?php echo $key;?>" name="nba_player[<?php echo $key;?>][NbaFrontRebounds][WikiKey]"  value="nba_front_rebounds" >
                                    <input type="hidden" id="nba_front_rebounds_sort<?php echo $key;?>" name="nba_player[<?php echo $key;?>][NbaFrontRebounds][Sort]" value="0">
                                    <input type="text"   id="nba_front_rebounds_value<?php echo $key;?>" name="nba_player[<?php echo $key;?>][NbaFrontRebounds][WikiValue]" class="mceNoEditor" value="<?php echo $value->NbaFrontRebounds->WikiValue;?>" size="70"/>
                            </td>
                       </tr>
                        <tr>
                            <td width="40%" class="paramlist_key"><span class="editlinktip"><label for="bak_rebounds">后篮板</label></span></td>
                            <td class="paramlist_value">
                                    <input type="hidden" id="nba_bak_rebounds_key<?php echo $key;?>" name="nba_player[<?php echo $key;?>][NbaBakRebounds][WikiKey]"  value="nba_bak_rebounds" >
                                    <input type="hidden" id="nba_bak_rebounds_sort<?php echo $key;?>" name="nba_player[<?php echo $key;?>][NbaBakRebounds][Sort]" value="0">
                                    <input type="text"   id="nba_bak_rebounds_value<?php echo $key;?>" name="nba_player[<?php echo $key;?>][NbaBakRebounds][WikiValue]" class="mceNoEditor" value="<?php echo $value->NbaBakRebounds->WikiValue;?>" size="70"/>
                            </td>
                       </tr>
                        <tr>
                            <td width="40%" class="paramlist_key"><span class="editlinktip"><label for="all_rebounds">总篮板</label></span></td>
                            <td class="paramlist_value">
                                    <input type="hidden" id="nba_all_rebounds_key<?php echo $key;?>" name="nba_player[<?php echo $key;?>][NbaAllRebounds][WikiKey]"  value="nba_all_rebounds" >
                                    <input type="hidden" id="nba_all_rebounds_sort<?php echo $key;?>" name="nba_player[<?php echo $key;?>][NbaAllRebounds][Sort]" value="0">
                                    <input type="text"   id="nba_all_rebounds_value<?php echo $key;?>" name="nba_player[<?php echo $key;?>][NbaAllRebounds][WikiValue]" class="mceNoEditor" value="<?php echo $value->NbaAllRebounds->WikiValue;?>" size="70"/>
                            </td>
                       </tr>
                        <tr>
                            <td width="40%" class="paramlist_key"><span class="editlinktip"><label for="assists">助攻</label></span></td>
                            <td class="paramlist_value">
                                    <input type="hidden" id="nba_assists_key<?php echo $key;?>" name="nba_player[<?php echo $key;?>][NbaAssists][WikiKey]"  value="nba_assists" >
                                    <input type="hidden" id="nba_assists_sort<?php echo $key;?>" name="nba_player[<?php echo $key;?>][NbaAssists][Sort]" value="0">
                                    <input type="text"   id="nba_assists_value<?php echo $key;?>" name="nba_player[<?php echo $key;?>][NbaAssists][WikiValue]" class="mceNoEditor" value="<?php echo $value->NbaAssists->WikiValue;?>" size="70"/>
                            </td>
                       </tr>
                        <tr>
                            <td width="40%" class="paramlist_key"><span class="editlinktip"><label for="steals">抢断</label></span></td>
                            <td class="paramlist_value">
                                    <input type="hidden" id="nba_steals_key<?php echo $key;?>" name="nba_player[<?php echo $key;?>][NbaSteals][WikiKey]"  value="nba_steals" >
                                    <input type="hidden" id="nba_steals_sort<?php echo $key;?>" name="nba_player[<?php echo $key;?>][NbaSteals][Sort]" value="0">
                                    <input type="text"   id="nba_steals_value<?php echo $key;?>" name="nba_player[<?php echo $key;?>][NbaSteals][WikiValue]" class="mceNoEditor" value="<?php echo $value->NbaSteals->WikiValue;?>" size="70"/>
                            </td>
                       </tr>
                        <tr>
                            <td width="40%" class="paramlist_key"><span class="editlinktip"><label for="steals">盖帽</label></span></td>
                            <td class="paramlist_value">
                                    <input type="hidden" id="nba_blocked_key<?php echo $key;?>" name="nba_player[<?php echo $key;?>][NbaBlocked][WikiKey]"  value="nba_blocked" >
                                    <input type="hidden" id="nba_blocked_sort<?php echo $key;?>" name="nba_player[<?php echo $key;?>][NbaBlocked][Sort]" value="0">
                                    <input type="text"   id="nba_blocked_value<?php echo $key;?>" name="nba_player[<?php echo $key;?>][NbaBlocked][WikiValue]" class="mceNoEditor" value="<?php echo $value->NbaBlocked->WikiValue;?>" size="70"/>
                            </td>
                       </tr>
                        <tr>
                            <td width="40%" class="paramlist_key"><span class="editlinktip"><label for="steals">失误</label></span></td>
                            <td class="paramlist_value">
                                    <input type="hidden" id="nba_turnovers_key<?php echo $key;?>" name="nba_player[<?php echo $key;?>][NbaTurnovers][WikiKey]"  value="nba_turnovers" >
                                    <input type="hidden" id="nba_turnovers_sort<?php echo $key;?>" name="nba_player[<?php echo $key;?>][NbaTurnovers][Sort]" value="0">
                                    <input type="text"   id="nba_turnovers_value<?php echo $key;?>" name="nba_player[<?php echo $key;?>][NbaTurnovers][WikiValue]" class="mceNoEditor" value="<?php echo $value->NbaTurnovers->WikiValue;?>" size="70"/>
                            </td>
                       </tr>
                        <tr>
                            <td width="40%" class="paramlist_key"><span class="editlinktip"><label for="nba_personal_fouls">犯规</label></span></td>
                            <td class="paramlist_value">
                                    <input type="hidden" id="nba_personal_fouls_key<?php echo $key;?>" name="nba_player[<?php echo $key;?>][NbaPersonalFouls][WikiKey]"  value="nba_personal_fouls" >
                                    <input type="hidden" id="nba_personal_fouls_sort<?php echo $key;?>" name="nba_player[<?php echo $key;?>][NbaPersonalFouls][Sort]" value="0">
                                    <input type="text"   id="nba_personal_fouls_value<?php echo $key;?>" name="nba_player[<?php echo $key;?>][NbaPersonalFouls][WikiValue]" class="mceNoEditor" value="<?php echo $value->NbaPersonalFouls->WikiValue;?>" size="70"/>
                            </td>
                       </tr>
                        <tr>
                            <td width="40%" class="paramlist_key"><span class="editlinktip"><label for="nba_points">得分</label></span></td>
                            <td class="paramlist_value">
                                    <input type="hidden" id="nba_points_key<?php echo $key;?>" name="nba_player[<?php echo $key;?>][NbaPoints][WikiKey]"  value="nba_points" >
                                    <input type="hidden" id="nba_points_sort<?php echo $key;?>" name="nba_player[<?php echo $key;?>][NbaPoints][Sort]" value="0">
                                    <input type="text"   id="nba_points_value<?php echo $key;?>" name="nba_player[<?php echo $key;?>][NbaPoints][WikiValue]" class="mceNoEditor" value="<?php echo $value->NbaPoints->WikiValue;?>" size="70"/>
                            </td>
                       </tr>

                       <tr>
                            <td width="40%" class="paramlist_key"><span class="editlinktip"><label for="nba_team">球队</label></span></td>
                            <td class="paramlist_value">
                                    <input type="hidden" id="nba_team_key<?php echo $key;?>" name="nba_player[<?php echo $key;?>][NbaTeam][WikiKey]"  value="nba_team" >
                                    <input type="hidden" id="nba_team_sort<?php echo $key;?>" name="nba_player[<?php echo $key;?>][NbaTeam][Sort]" value="0">
                                    <input type="hidden" id="nba_team_value<?php echo $key;?>" name="nba_player[<?php echo $key;?>][NbaTeam][WikiValue]" class="mceNoEditor" value="<?php echo $value->NbaTeam->WikiValue;?>" size="70"/>
                                    <input type="text"   id="nba_team_value_show<?php echo $key;?>" name="hide" class="mceNoEditor"  size="70" onblur="get_team_id_by_title('<?php echo $key;?>')"/>
                            </td>
                       </tr>
                       <?php }?>
                       <?php }?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
   $(document).ready(function(){
        //该集标题
        <?php $rss   =  json_decode($form->getObject()->getNbaPlayerAttribute()); ?>
        <?php if(!empty($rss)){?>
                <?php foreach ($rss as $key => $value) {?>
                    nba_player_complete('<?php echo $key;?>');
                    get_team_name_by_id('<?php echo $key;?>');
                <?php }?>
        <?php }?>
    });

    function get_team_id_by_title(sort)
    {
        var title   = $("#nba_team_value_show"+sort).val();
        if(title !='') {
            $.ajax({
                url :  wikiUrl + "/ajax_get_id_by_title?title="+title,
                cache: "false",
                dataType: "text",
                success:function(data){
                    $("#nba_team_value"+sort).val(data);
                }
            });
        }
    }
    
    function get_team_name_by_id(sort)
    {
        var id   = $("#nba_team_value"+sort).val();
        if (id !='') {
            $.ajax({
                url :  wikiUrl + "/ajax_get_title_by_id?id="+id,
                cache: "false",
                dataType: "text",
                success:function(data){
                    $("#nba_team_value_show"+sort).val(data);
                }
            });
        }
    }
    
    function nba_player_complete(id){
       $('#nba_team_value_show'+id).simpleAutoComplete(wikiUrl  + '/auto_complete_nba_team',{
            autoCompleteClassName: 'autocomplete',
            autoFill: false,
            selectedClassName: 'sel',
            attrCallBack: 'rel',
            identifier: 'nba_team',
            max       : 20
        });
    }


</script>
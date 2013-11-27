<script>
    tinymc_init();
var global_var  = '';
function global_save(value){
    global_var  = value;
}

function team_image(key ,url) {
    var pic   = $("#team_image_pic");
    var value = $("#team_image_value");
    pic.attr('src', url);
    pic.show();
    value.val(key);
    tb_remove();
}
</script>
<div class="col width-60">
    <fieldset class="adminform">
        <legend>球队数据</legend>
        <table class="admintable" id="html">
            <tbody>
                <tr>
                    <td class="key"><label for="wiki_title">球队名称</label></td>
                    <td>
                        <input type="text" id="wiki_title" name="wiki[title]" value="<?php echo $form->getObject()->getTitle();?>">
                        <input type="hidden" id="wiki_style" name="wiki[style]" value="<?php echo $sf_request->getParameter('style');?>">
                    </td>
                </tr>
                <tr>
                    <td class="key"><label for="wiki_title">添加赛季</label></td>
                    <td>
                        <input type="text" id="wiki_title_no" name="no" value="">
                        <input type="button" id="wiki_style_no" name="no" value="添加" onclick="add_html_team_html();">
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
                    <td class="key"><label for="wiki_content">内容</label></td>
                    <td>
                        <textarea name="wiki[content]" class="" style="width: 100%;"><?php echo $form->getObject()->getContent(); ?></textarea>
                    </td>
                </tr>
                <?php $arr  = json_decode($form->getObject()->getNbaAttribute()); ?>
                <?php if(!empty($arr)){?>
                <?php foreach ($arr as $key => $value) {?>
                <tr>
                    <td class="key"><label for="nba_location">赛季</label></td>
                    <td>
                        <input type="text"   id="nba_season" name="no" value="<?php echo $key;?>" size="70">
                    </td>
                </tr>
                <tr>
                    <td class="key"><label for="nba_location"><font color="red">投篮(场均)</font>出手</label></td>
                    <td>
                        <input type="hidden" id="nba_fire_averaging_goal_key<?php echo $key;?>" name="nba_team[<?php echo $key;?>][NbaFireSveragingGoal][WikiKey]"  value="nba_fire_averaging_goal" >
                        <input type="hidden" id="nba_fire_averaging_goal_sort<?php echo $key;?>" name="nba_team[<?php echo $key;?>][NbaFireSveragingGoal][Sort]" value="0">
                        <input type="text"   id="nba_fire_averaging_goal_value<?php echo $key;?>" name="nba_team[<?php echo $key;?>][NbaFireSveragingGoal][WikiValue]" value="<?php echo $value->NbaFireSveragingGoal->WikiValue;?>" size="70">
                    </td>
                </tr>
                <tr>
                    <td class="key"><label for="nba_location"><font color="red">投篮(场均)</font>命中率</label></td>
                    <td>
                        <input type="hidden" id="nba_hit_averaging_goal_key<?php echo $key;?>" name="nba_team[<?php echo $key;?>][NbaHitSveragingGoal][WikiKey]"  value="nba_hit_averaging_goal" >
                        <input type="hidden" id="nba_hit_averaging_goal_sort<?php echo $key;?>" name="nba_team[<?php echo $key;?>][NbaHitSveragingGoal][Sort]" value="0">
                        <input type="text"   id="nba_hit_averaging_goal_value<?php echo $key;?>" name="nba_team[<?php echo $key;?>][NbaHitSveragingGoal][WikiValue]" value="<?php echo $value->NbaHitSveragingGoal->WikiValue;?>" size="70">
                    </td>
                </tr>
                <tr>
                    <td class="key"><label for="nba_location"><font color="red">三分球(场均)</font>出手</label></td>
                    <td>
                        <input type="hidden" id="nba_fire_averaging_three_point_key<?php echo $key;?>" name="nba_team[<?php echo $key;?>][NbaFireAveragingThreePoint][WikiKey]"  value="nba_fire_averaging_three_point" >
                        <input type="hidden" id="nba_fire_averaging_three_point_sort<?php echo $key;?>" name="nba_team[<?php echo $key;?>][NbaFireAveragingThreePoint][Sort]" value="0">
                        <input type="text"   id="nba_fire_averaging_three_point_value<?php echo $key;?>" name="nba_team[<?php echo $key;?>][NbaFireAveragingThreePoint][WikiValue]" value="<?php echo $value->NbaFireAveragingThreePoint->WikiValue;?>" size="70">
                    </td>
                </tr>
                <tr>
                    <td class="key"><label for="nba_location"><font color="red">三分球(场均)</font>命中率</label></td>
                    <td>
                        <input type="hidden" id="nba_hit_averaging_three_point_key<?php echo $key;?>" name="nba_team[<?php echo $key;?>][NbaHitAveragingThreePoint][WikiKey]"  value="nba_hit_averaging_three_point" >
                        <input type="hidden" id="nba_hit_averaging_three_point_sort<?php echo $key;?>" name="nba_team[<?php echo $key;?>][NbaHitAveragingThreePoint][Sort]" value="0">
                        <input type="text"   id="nba_hit_averaging_three_point_value<?php echo $key;?>" name="nba_team[<?php echo $key;?>][NbaHitAveragingThreePoint][WikiValue]" value="<?php echo $value->NbaHitAveragingThreePoint->WikiValue;?>" size="70">
                    </td>
                </tr>
                <tr>
                    <td class="key"><label for="nba_location"><font color="red">罚球(场均)</font>出手</label></td>
                    <td>
                        <input type="hidden" id="nba_fire_averaging_free_throw_key<?php echo $key;?>" name="nba_team[<?php echo $key;?>][NbaFireAveragingFreeThrow][WikiKey]"  value="nba_fire_averaging_free_throw" >
                        <input type="hidden" id="nba_fire_averaging_free_throw_sort<?php echo $key;?>" name="nba_team[<?php echo $key;?>][NbaFireAveragingFreeThrow][Sort]" value="0">
                        <input type="text"   id="nba_fire_averaging_free_throw_value<?php echo $key;?>" name="nba_team[<?php echo $key;?>][NbaFireAveragingFreeThrow][WikiValue]" value="<?php echo $value->NbaFireAveragingFreeThrow->WikiValue;?>" size="70">
                    </td>
                </tr>
                <tr>
                    <td class="key"><label for="nba_location"><font color="red">罚球(场均)</font>命中率</label></td>
                    <td>
                        <input type="hidden" id="nba_hit_averaging_free_throw_key<?php echo $key;?>" name="nba_team[<?php echo $key;?>][NbaHitAveragingFreeThrow][WikiKey]"  value="nba_hit_averaging_free_throw" >
                        <input type="hidden" id="nba_hit_averaging_free_throw_sort<?php echo $key;?>" name="nba_team[<?php echo $key;?>][NbaHitAveragingFreeThrow][Sort]" value="0">
                        <input type="text"   id="nba_hit_averaging_free_throw_value<?php echo $key;?>" name="nba_team[<?php echo $key;?>][NbaHitAveragingFreeThrow][WikiValue]" value="<?php echo $value->NbaHitAveragingFreeThrow->WikiValue;?>" size="70">
                    </td>
                </tr>
                <tr>
                    <td class="key"><label for="nba_location"><font color="red">篮板(场均)</font>前场</label></td>
                    <td>
                        <input type="hidden" id="nba_rebounds_averaging_fore_court_key<?php echo $key;?>" name="nba_team[<?php echo $key;?>][NbaReboundsAveragingForeCourt][WikiKey]"  value="nba_rebounds_averaging_fore_court" >
                        <input type="hidden" id="nba_rebounds_averaging_fore_court_sort<?php echo $key;?>" name="nba_team[<?php echo $key;?>][NbaReboundsAveragingForeCourt][Sort]" value="0">
                        <input type="text"   id="nba_rebounds_averaging_fore_court_value<?php echo $key;?>" name="nba_team[<?php echo $key;?>][NbaReboundsAveragingForeCourt][WikiValue]" value="<?php echo $value->NbaReboundsAveragingForeCourt->WikiValue;?>" size="70">
                    </td>
                </tr>
                <tr>
                    <td class="key"><label for="nba_location"><font color="red">篮板(场均)</font>后场</label></td>
                    <td>
                        <input type="hidden" id="nba_rebounds_averaging_back_court_key<?php echo $key;?>" name="nba_team[<?php echo $key;?>][NbaReboundsAveragingBackCourt][WikiKey]"  value="nba_rebounds_averaging_back_court" >
                        <input type="hidden" id="nba_rebounds_averaging_back_court_sort<?php echo $key;?>" name="nba_team[<?php echo $key;?>][NbaReboundsAveragingBackCourt][Sort]" value="0">
                        <input type="text"   id="nba_rebounds_averaging_back_court_value<?php echo $key;?>" name="nba_team[<?php echo $key;?>][NbaReboundsAveragingBackCourt][WikiValue]" value="<?php echo $value->NbaReboundsAveragingBackCourt->WikiValue;?>" size="70">
                    </td>
                </tr>
                <tr>
                    <td class="key"><label for="nba_location"><font color="red">篮板(场均)</font>总</label></td>
                    <td>
                        <input type="hidden" id="nba_rebounds_averaging_all_court_key<?php echo $key;?>" name="nba_team[<?php echo $key;?>][NbaReboundsAveragingAllCourt][WikiKey]"  value="nba_rebounds_averaging_all_court" >
                        <input type="hidden" id="nba_rebounds_averaging_all_court_sort<?php echo $key;?>" name="nba_team[<?php echo $key;?>][NbaReboundsAveragingAllCourt][Sort]" value="0">
                        <input type="text"   id="nba_rebounds_averaging_all_court_value<?php echo $key;?>" name="nba_team[<?php echo $key;?>][NbaReboundsAveragingAllCourt][WikiValue]" value="<?php echo $value->NbaReboundsAveragingAllCourt->WikiValue;?>" size="70">
                    </td>
                </tr>
                <tr>
                    <td class="key"><label for="nba_location"><font color="red">其他(场均)</font>助攻</label></td>
                    <td>
                        <input type="hidden" id="nba_others_averaging_assists_key<?php echo $key;?>" name="nba_team[<?php echo $key;?>][NbaOthersAveragingAssists][WikiKey]"  value="nba_others_averaging_assists" >
                        <input type="hidden" id="nba_others_averaging_assists_sort<?php echo $key;?>" name="nba_team[<?php echo $key;?>][NbaOthersAveragingAssists][Sort]" value="0">
                        <input type="text"   id="nba_others_averaging_assists_value<?php echo $key;?>" name="nba_team[<?php echo $key;?>][NbaOthersAveragingAssists][WikiValue]" value="<?php echo $value->NbaOthersAveragingAssists->WikiValue;?>" size="70">
                    </td>
                </tr>
                <tr>
                    <td class="key"><label for="nba_location"><font color="red">其他(场均)</font>失误</label></td>
                    <td>
                        <input type="hidden" id="nba_others_averaging_turnovers_key<?php echo $key;?>" name="nba_team[<?php echo $key;?>][NbaOthersAveragingTurnovers][WikiKey]"  value="nba_others_averaging_turnovers" >
                        <input type="hidden" id="nba_others_averaging_turnovers_sort<?php echo $key;?>" name="nba_team[<?php echo $key;?>][NbaOthersAveragingTurnovers][Sort]" value="0">
                        <input type="text"   id="nba_others_averaging_turnovers_value<?php echo $key;?>" name="nba_team[<?php echo $key;?>][NbaOthersAveragingTurnovers][WikiValue]" value="<?php echo $value->NbaOthersAveragingTurnovers->WikiValue;?>" size="70">
                    </td>
                </tr>
                <tr>
                    <td class="key"><label for="nba_location"><font color="red">其他(场均)</font>犯规</label></td>
                    <td>
                        <input type="hidden" id="nba_others_averaging_personal_fouls_key<?php echo $key;?>" name="nba_team[<?php echo $key;?>][NbaOthersAveragingPersonalFouls][WikiKey]"  value="nba_others_averaging_personal_fouls" >
                        <input type="hidden" id="nba_others_averaging_personal_fouls_sort<?php echo $key;?>" name="nba_team[<?php echo $key;?>][NbaOthersAveragingPersonalFouls][Sort]" value="0">
                        <input type="text"   id="nba_others_averaging_personal_fouls_value<?php echo $key;?>" name="nba_team[<?php echo $key;?>][NbaOthersAveragingPersonalFouls][WikiValue]" value="<?php echo $value->NbaOthersAveragingPersonalFouls->WikiValue;?>" size="70">
                    </td>
                </tr>
                <tr>
                    <td class="key"><label for="nba_location"><font color="red">其他(场均)</font>得分</label></td>
                    <td>
                        <input type="hidden" id="nba_others_averaging_points_key<?php echo $key;?>" name="nba_team[<?php echo $key;?>][NbaOthersAveragingPoints][WikiKey]"  value="nba_others_averaging_points" >
                        <input type="hidden" id="nba_others_averaging_points_sort<?php echo $key;?>" name="nba_team[<?php echo $key;?>][NbaOthersAveragingPoints][Sort]" value="0">
                        <input type="text"   id="nba_others_averaging_points_value<?php echo $key;?>" name="nba_team[<?php echo $key;?>][NbaOthersAveragingPoints][WikiValue]" value="<?php echo $value->NbaOthersAveragingPoints->WikiValue;?>" size="70">
                    </td>
                </tr>
                <?php }?>
                <?php }?>
            </tbody>
        </table>
    </fieldset>
</div>
<div class="col width-40">
    <div class="pane-sliders" id="menu-pane">
        <div class="panel">
            <h3 id="param-page" class="title jpane-toggler-down"><span>基本资料</span></h3>
            <div class="jpane-slider content">
                <table cellspacing="1" width="100%" class="paramlist admintable">
                    <tbody  id="index">
                       <tr>
                            <td width="40%" class="paramlist_key"><span class="editlinktip"><label for="stills">队标</label></span></td>
                            <td class="paramlist_value">
                                    <input type="hidden" id="team_image_key" name="ext[TeamImage][WikiKey]"  value="team_image" >
                                    <input type="hidden" id="team_image_sort" name="ext[TeamImage][Sort]" value="0">
                                    <input type="hidden" id="team_image_value" name="ext[TeamImage][WikiValue]" class="mceNoEditor" value="<?php echo $form->getObject()->getTeamImage();?>"/>
                                    <a href="<?php echo url_for('media/link')?>?function_name=team_image&height=600&width=600&TB_iframe=false" class="thickbox" onclick="global_save('team_image');">上传队标</a>
                                    <br/>
                                    <?php $pic  =  file_url($form->getObject()->getTeamImage());
                                    if(!$pic){
                                        $show   = 'none';
                                    }
                                    ?>
                                    <img id="team_image_pic" src="<?php echo $pic;?>" alt="加载中" style="display: <?php echo $show;?>"/>
                            </td>
                       </tr>

                       <tr>
                            <td width="40%" class="paramlist_key"><span class="editlinktip"><label for="stills">季赛战绩(胜)</label></span></td>
                            <td class="paramlist_value">
                                    <input type="hidden" id="season_record_win_key" name="ext[SeasonRecordWin][WikiKey]"  value="season_record_win" >
                                    <input type="hidden" id="season_record_win_sort" name="ext[SeasonRecordWin][Sort]" value="0">
                                    <input type="text"   id="season_record_win_value" name="ext[SeasonRecordWin][WikiValue]" class="mceNoEditor" value="<?php echo $form->getObject()->getSeasonRecordWin();?>"/>
                            </td>
                       </tr>

                       <tr>
                            <td width="40%" class="paramlist_key"><span class="editlinktip"><label for="stills">季赛战绩(负)</label></span></td>
                            <td class="paramlist_value">
                                    <input type="hidden" id="season_record_fail_key" name="ext[SeasonRecordFail][WikiKey]"  value="season_record_fail" >
                                    <input type="hidden" id="season_record_fail_sort" name="ext[SeasonRecordFail][Sort]" value="0">
                                    <input type="text"   id="season_record_fail_value" name="ext[SeasonRecordFail][WikiValue]" class="mceNoEditor" value="<?php echo $form->getObject()->getSeasonRecordWin();?>"/>
                            </td>
                       </tr>

                       <tr>
                            <td width="40%" class="paramlist_key"><span class="editlinktip"><label for="stills">分区</label></span></td>
                            <td class="paramlist_value">
                                    <input type="hidden" id="nba_partition_key" name="ext[NbaPartition][WikiKey]"  value="nba_partition" >
                                    <input type="hidden" id="nba_partition_sort" name="ext[NbaPartition][Sort]" value="0">
                                    <input type="hidden" id="nba_partition_value" name="ext[NbaPartition][WikiValue]" class="mceNoEditor" value="<?php echo $form->getObject()->getNbaPartition();?>"/>
                                    <input type="text"   id="nba_partition_value_show" name="no" class="mceNoEditor" onblur="get_team_id_by_title();"/>
                            </td>
                       </tr>

                       <tr>
                            <td width="40%" class="paramlist_key"><span class="editlinktip"><label for="stills">主教练</label></span></td>
                            <td class="paramlist_value">
                                    <input type="hidden" id="chief_coach_key" name="ext[ChiefCoach][WikiKey]"  value="chief_coach" >
                                    <input type="hidden" id="chief_coach_sort" name="ext[ChiefCoach][Sort]" value="0">
                                    <input type="text"   id="chief_coach_value" name="ext[ChiefCoach][WikiValue]" class="mceNoEditor" value="<?php echo $form->getObject()->getChiefCoach();?>"/>
                            </td>
                       </tr>

                       <tr>
                            <td width="40%" class="paramlist_key"><span class="editlinktip"><label for="nba_year">进入NBA(年份)</label></span></td>
                            <td class="paramlist_value">
                                    <input type="hidden" id="nba_year_key" name="ext[NbaYear][WikiKey]"  value="nba_year" >
                                    <input type="hidden" id="nba_year_sort" name="ext[NbaYear][Sort]" value="0">
                                    <input type="text"   id="nba_year_value" name="ext[NbaYear][WikiValue]" class="mceNoEditor" value="<?php echo $form->getObject()->getNbaYear();?>"/>
                            </td>
                       </tr>

                       <tr>
                            <td width="40%" class="paramlist_key"><span class="editlinktip"><label for="history_won">历史夺冠(次)</label></span></td>
                            <td class="paramlist_value">
                                    <input type="hidden" id="history_won_key" name="ext[HistoryWon][WikiKey]"  value="history_won" >
                                    <input type="hidden" id="history_won_sort" name="ext[HistoryWon][Sort]" value="0">
                                    <input type="text"   id="history_won_value" name="ext[HistoryWon][WikiValue]" class="mceNoEditor" value="<?php echo $form->getObject()->getHistoryWon();?>"/>
                            </td>
                       </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
       $(document).ready(function(){
        //该集标题
        $('#nba_partition_value_show').simpleAutoComplete(wikiUrl  + '/auto_complete_nba_team',{
            autoCompleteClassName: 'autocomplete',
            autoFill: false,
            selectedClassName: 'sel',
            attrCallBack: 'rel',
            identifier: 'nba_partition',
            max       : 20
        });
        get_team_name_by_id();
    });

    function get_team_id_by_title()
    {
        var title   = $("#nba_partition_value_show").val();
        if(title !='') {
            $.ajax({
                url :  wikiUrl + "/ajax_get_id_by_title?title="+title,
                cache: "false",
                dataType: "text",
                success:function(data){
                    $("#nba_partition_value").val(data);
                }
            });
        }
    }

    function get_team_name_by_id()
    {
        var id   = $("#nba_partition_value").val();
        if (id !='') {
            $.ajax({
                url :  wikiUrl + "/ajax_get_title_by_id?id="+id,
                cache: "false",
                dataType: "text",
                success:function(data){
                    $("#nba_partition_value_show").val(data);
                }
            });
        }
    }
</script>
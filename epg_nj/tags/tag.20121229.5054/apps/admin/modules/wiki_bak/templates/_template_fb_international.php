<script>
tinyMCE.init({
    theme : "advanced", //总共两种模板：advanced和simple
    theme_advanced_toolbar_location : "top", //工具栏默认是在底部，调到顶部的话要加入这个句配置。
    theme_advanced_toolbar_align : "left", //工具栏按钮默认是全局居中，要让按钮左对齐要加入这句配置。
    theme_advanced_statusbar_location : "bottom", //默认是不显示状态栏的（功能和DW的状态栏一样），加入此句子可以调出状态栏并且显示在编辑器下方。
    theme_advanced_resizing : true, //可以动态调整编辑器大小（按钮在编辑器右下方）
    mode : "textareas",
    skin: "default" //这是office风格，挺清爽的。

});

var do_action   = '';

function do_action_save(str){
    do_action   = str;
}
function img_file_action(key, url)
{
    $("#value_"+do_action).val(key);
    $("#"+do_action+"_show").attr('src',url);
    $("#"+do_action+"_show").show();
    tb_remove();
}

</script>
<div class="col width-60">
    <fieldset class="adminform">
        <legend>Wiki【足球球队】</legend>
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
                    <td class="key"><label for="ename">走势</label></td>
                    <td>
                        <input type="hidden" id="id_trend"    name="ext[Trend][Sort]" value="0">
                        <input type="hidden" id="key_trend"   name="ext[Trend][WikiKey]" value="trend">
                        <select name="ext[Trend][WikiValue]" id="value_trend">
                            <option value="<?php echo $form->getObject()->getTrend();?>"><?php echo $form->getObject()->getTrend();?></option>
                            <option value="↗">↗</option>
                            <option value="↘">↘</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="key"><label for="ename">英文名</label></td>
                    <td>
                        <input type="hidden" id="id_ename" name="ext[Ename][Sort]" value="0">
                        <input type="hidden" id="key_ename" name="ext[Ename][WikiKey]" value="ename">
                        <input type="text"   id="value_ename" name="ext[Ename][WikiValue]" value="<?php echo $form->getObject()->getEname();?>"/>
                    </td>
                </tr>

                <tr>
                    <td class="key"><label for="stadium">主场</label></td>
                    <td>
                        <input type="hidden" id="id_stadium" name="ext[Stadium][Sort]" value="0">
                        <input type="hidden" id="key_stadium" name="ext[Stadium][WikiKey]" value="stadium">
                        <input type="text"   id="value_stadium" name="ext[Stadium][WikiValue]" value="<?php echo $form->getObject()->getStadium();?>"/>
                    </td>
                </tr>
                <tr>
                    <td class="key"><label for="time_was">成立时间</label></td>
                    <td>
                        <input type="hidden" id="id_time_was" name="ext[TimeWas][Sort]" value="0">
                        <input type="hidden" id="key_time_was" name="ext[TimeWas][WikiKey]" value="time_was">
                        <input type="text"   id="value_time_was" name="ext[TimeWas][WikiValue]" value="<?php echo $form->getObject()->getTimeWas();?>"/>
                    </td>
                </tr>
                <tr>
                    <td class="key"><label for="team_president">球队主席</label></td>
                    <td>
                        <input type="hidden" id="id_team_president" name="ext[TeamPresident][Sort]" value="0">
                        <input type="hidden" id="key_team_president" name="ext[TeamPresident][WikiKey]" value="team_president">
                        <input type="text"   id="value_team_president" name="ext[TeamPresident][WikiValue]" value="<?php echo $form->getObject()->getTeamPresident();?>"/>
                    </td>
                </tr>
                <tr>
                    <td class="key"><label for="history_won">历史夺冠</label></td>
                    <td>
                        <input type="hidden" id="id_history_won" name="ext[HistoryWon][Sort]" value="0">
                        <input type="hidden" id="key_history_won" name="ext[HistoryWon][WikiKey]" value="history_won">
                        <input type="text"   id="value_history_won" name="ext[HistoryWon][WikiValue]" value="<?php echo $form->getObject()->getHistoryWon();?>"/>
                    </td>
                </tr>
                <tr>
                    <td class="key"><label for="chief_coach">主教练</label></td>
                    <td>
                        <input type="hidden" id="id_chief_coach" name="ext[ChiefCoach][Sort]" value="0">
                        <input type="hidden" id="key_chief_coach" name="ext[ChiefCoach][WikiKey]" value="chief_coach">
                        <input type="text"   id="value_chief_coach" name="ext[ChiefCoach][WikiValue]" value="<?php echo $form->getObject()->getChiefCoach();?>"/>
                    </td>
                </tr>
                <tr>
                    <td class="key"><label for="age">年龄</label></td>
                    <td>
                        <input type="hidden" id="id_age" name="ext[Age][Sort]" value="0">
                        <input type="hidden" id="key_age" name="ext[Age][WikiKey]" value="age">
                        <input type="text"   id="value_age" name="ext[Age][WikiValue]" value="<?php echo $form->getObject()->getAge();?>"/>
                    </td>
                </tr>
                <tr>
                    <td class="key"><label for="coaching_time">执教时间</label></td>
                    <td>
                        <input type="hidden" id="id_coaching_time" name="ext[CoachingTime][Sort]" value="0">
                        <input type="hidden" id="key_coaching_time" name="ext[CoachingTime][WikiKey]" value="coaching_time">
                        <input type="text"   id="value_coaching_time" name="ext[CoachingTime][WikiValue]" value="<?php echo $form->getObject()->getCoachingTime();?>"/>
                    </td>
                </tr>
                <tr>
                    <td class="key"><label for="coaching_time">联赛类型</label></td>
                    <td>
                        <input type="hidden" id="id_league_type" name="ext[LeagueType][Sort]" value="0">
                        <input type="hidden" id="key_league_type" name="ext[LeagueType][WikiKey]" value="league_type">
                        <input type="hidden" id="value_league_type" name="ext[LeagueType][WikiValue]" value="<?php echo $form->getObject()->getLeagueType();?>"/>
                        <input type="text"   id="value_league_type_show" name="no" onblur="get_team_id_by_title();"/>
                    </td>
                </tr>
                <tr>
                    <td class="key"><label for="coaching_time">相关图片</label></td>
                    <td>
                        <input type="hidden" id="id_coach_image" name="ext[CoachImage][Sort]" value="0">
                        <input type="hidden" id="key_coach_image" name="ext[CoachImage][WikiKey]" value="coach_image">
                        <input type="hidden" id="value_coach_image" name="ext[CoachImage][WikiValue]" value="<?php echo $form->getObject()->getCoachImage();?>"/>
                        
                        <input type="hidden" id="id_team_image" name="ext[TeamImage][Sort]" value="0">
                        <input type="hidden" id="key_team_image" name="ext[TeamImage][WikiKey]" value="team_image">
                        <input type="hidden" id="value_team_image" name="ext[TeamImage][WikiValue]" value="<?php echo $form->getObject()->getTeamImage();?>"/>
                        
                        <input type="hidden" id="id_home_clothing" name="ext[HomeClothing][Sort]" value="0">
                        <input type="hidden" id="key_home_clothing" name="ext[HomeClothing][WikiKey]" value="home_clothing">
                        <input type="hidden" id="value_home_clothing" name="ext[HomeClothing][WikiValue]" value="<?php echo $form->getObject()->getHomeClothing();?>"/>

                        <input type="hidden" id="id_road_clothing" name="ext[RoadClothing][Sort]" value="0">
                        <input type="hidden" id="key_road_clothing" name="ext[RoadClothing][WikiKey]" value="road_clothing">
                        <input type="hidden" id="value_road_clothing" name="ext[RoadClothing][WikiValue]" value="<?php echo $form->getObject()->getRoadClothing();?>"/>
                        <table>
                            <?php
                                $b_1    = file_url($form->getObject()->getTeamImage());
                                $b_2    = file_url($form->getObject()->getCoachImage());
                                $b_3    = file_url($form->getObject()->getHomeClothing());
                                $b_4    = file_url($form->getObject()->getRoadClothing());
                                if(!$b_1){
                                    $a_1    = 'none';
                                }
                                if(!$b_2){
                                    $a_2    = 'none';
                                }
                                if(!$b_3){
                                    $a_3    = 'none';
                                }
                                if(!$b_4){
                                    $a_4    = 'none';
                                }


                            ?>
                            <tr>
                                <td><img id="team_image_show"    src="<?php echo $b_1;?>" alt="" style="display: <?php echo $a_1;?>"/></td>
                                <td><img id="coach_image_show"   src="<?php echo $b_2;?>" alt="" style="display: <?php echo $a_2;?>"/></td>
                                <td><img id="home_clothing_show" src="<?php echo $b_3;?>" alt="" style="display: <?php echo $a_3;?>"/></td>
                                <td><img id="road_clothing_show" src="<?php echo $b_4;?>" alt="" style="display: <?php echo $a_4;?>"/></td>
                            </tr>
                            <tr>
                                <td><a href="<?php echo url_for('media/link')?>?function_name=img_file_action&height=600&width=600&TB_iframe=false" onclick="do_action_save('team_image');" class="thickbox">上传队标</a></td>
                                <td><a href="<?php echo url_for('media/link')?>?function_name=img_file_action&height=600&width=600&TB_iframe=false" onclick="do_action_save('coach_image');" class="thickbox">上传教练头像</a></td>
                                <td><a href="<?php echo url_for('media/link')?>?function_name=img_file_action&height=600&width=600&TB_iframe=false" onclick="do_action_save('home_clothing');" class="thickbox">主场队服</a></td>
                                <td><a href="<?php echo url_for('media/link')?>?function_name=img_file_action&height=600&width=600&TB_iframe=false" onclick="do_action_save('road_clothing');" class="thickbox">客场队服</a></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <!--tr>
                    <td class="key"><label for="wiki_content">球队简介</label></td>
                    <td>
                        <textarea name="wiki[content]" class="mceNoEditor" style="width: 100%;"><?php echo $form->getObject()->getContent(); ?></textarea>
                    </td>
                </tr-->
            </tbody>
        </table>
    </fieldset>
</div>
<div class="col width-40">
    <div class="pane-sliders" id="menu-pane">
        <div class="panel">
            <h3 id="param-page" class="title jpane-toggler-down"><span>球队数据</span>&nbsp;&nbsp;<a href="javascript:add_prompt();">添加</a>&nbsp;&nbsp;<a href="javascript:delete_prompt();">删除</a></h3>
            <div class="jpane-slider content">
                <table cellspacing="1" width="100%" class="paramlist admintable">
                    <tbody  id="index">
                        <?php $arr  = $form->getObject()->getFbInternational();?>
                        <?php if(!empty($arr)) { ?>
                        <?php foreach (json_decode($arr) as $key => $val) {?>
                       <tr class="fb_international<?php echo $key;?>">
                            <td width="40%" class="paramlist_key">赛季战绩:</td>
                            <td class="paramlist_value" width="70%">
                                <input type="text" id="value_<?php echo $key;?>_home_no" name="no" value="<?php echo $key;?>" size="50" disabled="true">
                            </td>
                        </tr>
                        <tr class="fb_international<?php echo $key;?>">
                            <td width="40%" class="paramlist_key"  style="color: red;">(主场) 已胜:</td>
                            <td class="paramlist_value" width="70%">
                                    <input type="text" id="value_<?php echo $key;?>_home_win_all" name="fb_international[<?php echo $key;?>][home][win_all]" value="<?php echo $val->home->win_all;?>" size="50">
                            </td>
                        </tr>
                        <tr class="fb_international<?php echo $key;?>">
                            <td width="40%" class="paramlist_key">(主场) 胜:</td>
                            <td class="paramlist_value" width="70%">
                                    <input type="text" id="value_<?php echo $key;?>_home_win" name="fb_international[<?php echo $key;?>][home][win]" value="<?php echo $val->home->win;?>" size="50">
                            </td>
                        </tr>
                        <tr class="fb_international<?php echo $key;?>">
                            <td width="40%" class="paramlist_key">(主场) 平:</td>
                            <td class="paramlist_value" width="70%">
                                    <input type="text" id="value_<?php echo $key;?>_home_deuce" name="fb_international[<?php echo $key;?>][home][deuce]" value="<?php echo $val->home->deuce;?>" size="50">
                            </td>
                        </tr>
                        <tr class="fb_international<?php echo $key;?>">
                            <td width="40%" class="paramlist_key">(主场) 负:</td>
                            <td class="paramlist_value" width="70%">
                                    <input type="text" id="value_<?php echo $key;?>_home_lose" name="fb_international[<?php echo $key;?>][home][lose]" value="<?php echo $val->home->lose;?>" size="50">
                            </td>
                        </tr>
                        <tr class="fb_international<?php echo $key;?>">
                            <td width="40%" class="paramlist_key">(主场) 进球:</td>
                            <td class="paramlist_value" width="70%">
                                    <input type="text" id="value_<?php echo $key;?>_home_ball_get" name="fb_international[<?php echo $key;?>][home][ball_get]" value="<?php echo $val->home->ball_get;?>" size="50">
                            </td>
                        </tr>
                        <tr class="fb_international<?php echo $key;?>">
                            <td width="40%" class="paramlist_key">(主场) 失球:</td>
                            <td class="paramlist_value" width="70%">
                                    <input type="text" id="value_<?php echo $key;?>_home_ball_lose" name="fb_international[<?php echo $key;?>][home][ball_lose]" value="<?php echo $val->home->ball_lose;?>" size="50">
                            </td>
                        </tr>
                        <tr class="fb_international<?php echo $key;?>">
                            <td width="40%" class="paramlist_key">(主场) 近五轮战绩(1胜,0平,-1负)</td>
                            <td class="paramlist_value" width="70%">
                                    <input type="text" id="value_<?php echo $key;?>_home_results" name="fb_international[<?php echo $key;?>][home][results]" value="<?php echo $val->home->results;?>" size="50">
                            </td>
                        </tr>
                        <tr class="fb_international<?php echo $key;?>">
                            <td width="40%" class="paramlist_key"  style="color: red;">(客场) 已胜:</td>
                            <td class="paramlist_value" width="70%">
                                    <input type="text" id="value_<?php echo $key;?>_home_win_all" name="fb_international[<?php echo $key;?>][road][win_all]" value="<?php echo $val->road->win_all;?>" size="50">
                            </td>
                        </tr>
                        <tr class="fb_international<?php echo $key;?>">
                            <td width="40%" class="paramlist_key">(客场) 胜:</td>
                            <td class="paramlist_value" width="70%">
                                    <input type="text" id="value_<?php echo $key;?>_road_win" name="fb_international[<?php echo $key;?>][road][win]" value="<?php echo $val->road->win_all;?>" size="50">
                            </td>
                        </tr>
                        <tr class="fb_international<?php echo $key;?>">
                            <td width="40%" class="paramlist_key">(客场) 平:</td>
                            <td class="paramlist_value" width="70%">
                                    <input type="text" id="value_<?php echo $key;?>_road_deuce" name="fb_international[<?php echo $key;?>][road][deuce]" value="<?php echo $val->road->deuce;?>" size="50">
                            </td>
                        </tr>
                        <tr class="fb_international<?php echo $key;?>">
                            <td width="40%" class="paramlist_key">(客场) 负:</td>
                            <td class="paramlist_value" width="70%">
                                    <input type="text" id="value_<?php echo $key;?>_road_lose" name="fb_international[<?php echo $key;?>][road][lose]" value="<?php echo $val->road->lose;?>" size="50">
                            </td>
                        </tr>
                        <tr class="fb_international<?php echo $key;?>">
                            <td width="40%" class="paramlist_key">(客场) 进球:</td>
                            <td class="paramlist_value" width="70%">
                                    <input type="text" id="value_<?php echo $key;?>_road_ball_get" name="fb_international[<?php echo $key;?>][road][ball_get]" value="<?php echo $val->road->ball_get;?>" size="50">
                            </td>
                        </tr>
                        <tr class="fb_international<?php echo $key;?>">
                            <td width="40%" class="paramlist_key">(客场) 失球:</td>
                            <td class="paramlist_value" width="70%">
                                    <input type="text" id="value_<?php echo $key;?>_road_ball_lose" name="fb_international[<?php echo $key;?>][road][ball_lose]" value="<?php echo $val->road->ball_lose;?>" size="50">
                            </td>
                        </tr>
                        <tr class="fb_international<?php echo $key;?>">
                            <td width="40%" class="paramlist_key">(客场) 近五轮战绩(1胜,0平,-1负)</td>
                            <td class="paramlist_value" width="70%">
                                    <input type="text" id="value_<?php echo $key;?>_road_results" name="fb_international[<?php echo $key;?>][road][results]" value="<?php echo $val->road->results;?>" size="50">
                            </td>
                        </tr>
                        <?php } ?>
                        <?php } ?>
                    </tbody></table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        nba_league_type_complete();
        get_team_name_by_id();
    });
    function get_team_id_by_title()
    {
        var title   = $("#value_league_type_show").val();
        if(title !='') {
            $.ajax({
                url :  wikiUrl + "/ajax_get_id_by_title?title="+title,
                cache: "false",
                dataType: "text",
                success:function(data){
                    $("#value_league_type").val(data);
                }
            });
        }
    }

    function get_team_name_by_id()
    {
        var id   = $("#value_league_type").val();
        if (id !='') {
            $.ajax({
                url :  wikiUrl + "/ajax_get_title_by_id?id="+id,
                cache: "false",
                dataType: "text",
                success:function(data){
                    $("#value_league_type_show").val(data);
                }
            });
        }
    }
   function nba_league_type_complete(){
       $('#value_league_type_show').simpleAutoComplete(wikiUrl  + '/auto_complete_nba_team',{
            autoCompleteClassName: 'autocomplete',
            autoFill: false,
            selectedClassName: 'sel',
            attrCallBack: 'rel',
            identifier: 'football',
            max       : 20
        });
    }

    function add_prompt()
    {
        var date    = window.prompt('请输入球员赛季，格式:2010-2011');
        if(date) {
            var html    = get_fb_international_right_html(date);
            var index   = $("#index");
            var content = index.html();
            index.html(html+content);
            $("#value_"+date+"_home_win_all").focus();
        }
    }

    function delete_prompt()
    {
        var date    = window.prompt('请输入球员赛季，格式:2010-2011');
        if(date) {
            $("tr.fb_international"+date).remove();
        }
    }
</script>
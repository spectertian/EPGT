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

<script type="text/javascript" src="<?php echo javascript_path('wiki_fb_player.js');?>"></script>
<div class="col width-60">
    <fieldset class="adminform">
        <legend>Wiki【足球球员信息】</legend>
        <table class="admintable" id="html">
            <tbody>
                <tr>
                    <td class="key"><label for="wiki_title">名称</label></td>
                    <td>
                        <input type="text" id="wiki_title" name="wiki[title]" value="<?php echo $form->getObject()->getTitle();?>">
                        <input type="hidden" id="wiki_style" name="wiki[style]" value="<?php echo $sf_request->getParameter('style');?>">
                    </td>
                    <td class="key"><label for="wiki_content">球队</label></td>
                    <td>
                        <input type="hidden" id="id_nba_team" name="ext[NbaTeam][Sort]" value="0">
                        <input type="hidden" id="key_nba_team" name="ext[NbaTeam][WikiKey]" value="nba_team">
                        <input type="hidden" id="value_nba_team" name="ext[NbaTeam][WikiValue]" value="<?php echo $form->getObject()->getNbaTeam(); ?>">
                        <input type="text"   id="value_nba_team_show" name="no" value="" onblur="get_team_id_by_title();">
                    </td>
                    <td class="key"><label for="wiki_content">年龄</label></td>
                    <td>
                        <input type="hidden" id="id_age" name="ext[Age][Sort]" value="0">
                        <input type="hidden" id="key_age" name="ext[Age][WikiKey]" value="nba_team">
                        <input type="text" id="value_age" name="ext[Age][WikiValue]" value="<?php echo $form->getObject()->getAge(); ?>">
                    </td>
                </tr>
                
                <tr>
                    <td class="key"><label for="nba_birthday">出生日期</label></td>
                    <td>
                        <input type="hidden" id="id_nba_birthday" name="ext[NbaBirthday][Sort]" value="0">
                        <input type="hidden" id="key_nba_birthday" name="ext[NbaBirthday][WikiKey]" value="nba_birthday">
                        <input type="text"   id="value_nba_birthday" name="ext[NbaBirthday][WikiValue]" value="<?php echo $form->getObject()->getNbaBirthday(); ?>">
                    </td>
                    <td class="key"><label for="nba_height">身高</label></td>
                    <td>
                        <input type="hidden" id="id_nba_height" name="ext[NbaHeight][Sort]" value="0">
                        <input type="hidden" id="key_nba_height" name="ext[NbaHeight][WikiKey]" value="nba_height">
                        <input type="text"   id="value_nba_height" name="ext[NbaHeight][WikiValue]" value="<?php echo $form->getObject()->getNbaHeight(); ?>">
                    </td>
                    <td class="key"><label for="nationality">国籍</label></td>
                    <td>
                        <input type="hidden" id="id_nationality" name="ext[Nationality][Sort]" value="0">
                        <input type="hidden" id="key_nationality" name="ext[Nationality][WikiKey]" value="nationality">
                        <input type="text"   id="value_nationality" name="ext[Nationality][WikiValue]" value="<?php echo $form->getObject()->getNationality(); ?>">
                    </td>
                </tr>
                <tr>
                    <td class="key"><label for="wiki_title">出生地</label></td>
                    <td>
                        <input type="hidden" id="id_nba_birthland" name="ext[NbaBirthland][Sort]" value="0">
                        <input type="hidden" id="key_nba_birthland" name="ext[NbaBirthland][WikiKey]" value="nba_birthland">
                        <input type="text"   id="value_nba_birthland" name="ext[NbaBirthland][WikiValue]" value="<?php echo $form->getObject()->getNbaBirthland(); ?>">
                    </td>
                    <td class="key"><label for="ename">英文名</label></td>
                    <td>
                        <input type="hidden" id="id_ename" name="ext[Ename][Sort]" value="0">
                        <input type="hidden" id="key_ename" name="ext[Ename][WikiKey]" value="ename">
                        <input type="text"   id="value_ename" name="ext[Ename][WikiValue]" value="<?php echo $form->getObject()->getEname(); ?>">
                    </td>
                    <td class="key"><label for="wiki_content">头像</label><a href="<?php echo url_for('media/link')?>?function_name=img_file_action&height=600&width=600&TB_iframe=false" onclick="do_action_save('nba_player_img');" class="thickbox">上传</a></td>
                    <td>
                        <input type="hidden" id="id_nba_player_img" name="ext[NbaPlayerImg][Sort]" value="0">
                        <input type="hidden" id="key_nba_player_img" name="ext[NbaPlayerImg][WikiKey]" value="nba_player_img">
                        <input type="hidden" id="value_nba_player_img" name="ext[NbaPlayerImg][WikiValue]" value="<?php echo $form->getObject()->getNbaPlayerImg(); ?>">
                        <?php
                            $pic    = file_url($form->getObject()->getNbaPlayerImg());
                            if(!$pic){
                                $show   = 'none';
                            }
                        ?>
                        <img id="nba_player_img_show" alt="加载" src="<?php  echo $pic; ?>" style="display: <?php echo $show;?>"/>
                    </td>
                </tr>
            </tbody>
        </table>
    </fieldset>
    <fieldset class="adminform">
        <legend>球员个人履历&nbsp;&nbsp;<a href="javascript:resume_prompt();"><font color="red">添加</font></a>&nbsp;&nbsp;<a href="javascript:resume_delete();"><font color="red">删除</font></a></legend>
        <table class="admintable" id="html_resume">
            <tbody  id="resume">
                <?php $arr  = $form->getObject()->getFbResume();?>
                <?php if(!empty($arr)) {?>
                <?php $arr  = json_decode($arr);?>
                <?php foreach ($arr as $key => $value) {?>
                <tr class="resume_<?php echo $key;?>">
                    <td class="key"><label for="nba_season">赛季</label></td>
                    <td>
                        <input type="text"   id="resum_<?php echo $key;?>_value_nba_season" name="fb_resume[<?php echo $key;?>][nba_season]" value="<?php echo $key;?>" disabled="true">
                    </td>
                    <td class="key"><label for="ball_get">出场</label></td>
                    <td>
                        <input type="text"   id="resum_<?php echo $key;?>_value_nba_sppeared" name="fb_resume[<?php echo $key;?>][nba_sppeareds]" value="<?php echo $value->nba_sppeareds;?>">
                    </td>
                    <td class="key"><label for="ball_get">进球</label></td>
                    <td>
                        <input type="text" id="resum_<?php echo $key;?>_value_ball_get" name="fb_resume[<?php echo $key;?>][ball_get]" value="<?php echo $value->ball_get;?>">
                    </td>
                </tr>
                <tr class="resume_<?php echo $key;?>">
                    <td class="key"><label for="nba_season">球队</label></td>
                    <td>
                        <input type="text" id="resum_<?php echo $key;?>_value_team" name="fb_resume[<?php echo $key;?>][team]" value="<?php echo $value->team;?>">
                    </td>
                    <td class="key"><label for="ball_get">联赛名称</label></td>
                    <td>
                        <input type="text"  id="resum_<?php echo $key;?>_value_league_name" name="fb_resume[<?php echo $key;?>][league_name]" value="<?php echo $value->league_name;?>">
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
            <h3 id="param-page" class="title jpane-toggler-down"><span>赛季技术统计</span>&nbsp;&nbsp;<a href="javascript:statistics_prompt();"><font color="red">添加</font></a>&nbsp;&nbsp;<a href="javascript:statistics_delete();"><font color="red">删除</font></a></h3>
            <div class="jpane-slider content">
                <table cellspacing="1" width="100%" class="paramlist admintable">
                    <tbody  id="index">
                        <?php $statistics   = $form->getObject()->getStatistics();?>
                        <?php if(!empty($statistics)){?>
                        <?php $statistics   = json_decode($statistics);?>
                        <?php foreach ($statistics as $key => $value) {?>
                        <tr  class="statistics_<?php echo $key;?>">
                            <td width="40%" class="paramlist_key">号码:</td>
                            <td class="paramlist_value" width="40%">
                                <input type="text" id="statistics_value_<?php echo $key;?>_auto_id" name="statistics[<?php echo $key;?>][auto_id]" value="<?php echo $value->auto_id;?>" size="25">
                            </td>
                            <td width="40%" class="paramlist_key">位置:</td>
                            <td class="paramlist_value" width="40%">
                                <input type="text"   id="statistics_value_<?php echo $key;?>_nba_location" name="statistics[<?php echo $key;?>][nba_location]" value="<?php echo $value->nba_location;?>" size="25">
                            </td>
                        </tr>
                        <tr class="statistics_<?php echo $key;?>">
                            <td width="40%" class="paramlist_key">首发:</td>
                            <td class="paramlist_value" width="40%">
                                <input type="text"   id="statistics_value_<?php echo $key;?>_nba_starting" name="statistics[<?php echo $key;?>][nba_starting]" value="<?php echo $value->nba_starting;?>" size="25">
                            </td>
                            <td width="40%" class="paramlist_key">均场:</td>
                            <td class="paramlist_value" width="40%">
                                <input type="text"   id="statistics_value_<?php echo $key;?>_averaging" name="statistics[<?php echo $key;?>][averaging]" value="<?php echo $value->averaging;?>" size="25">
                            </td>
                        </tr>
                        <tr class="statistics_<?php echo $key;?>">
                            <td width="40%" class="paramlist_key">进球:</td>
                            <td class="paramlist_value" width="40%">
                                <input type="text"   id="statistics_value_<?php echo $key;?>_ball_get" name="statistics[<?php echo $key;?>][ball_get]" value="<?php echo $value->ball_get;?>" size="25">
                            </td>
                            <td width="40%" class="paramlist_key">助攻:</td>
                            <td class="paramlist_value" width="40%">
                                <input type="text"   id="statistics_value_<?php echo $key;?>_assists" name="statistics[<?php echo $key;?>][assists]" value="<?php echo $value->assists;?>" size="25">
                            </td>
                        </tr>
                        <tr class="statistics_<?php echo $key;?>">
                            <td width="40%" class="paramlist_key">射门:</td>
                            <td class="paramlist_value" width="40%">
                                <input type="text"   id="statistics_value_<?php echo $key;?>_shoot" name="statistics[<?php echo $key;?>][shoot]" value="<?php echo $value->shoot;?>" size="25">
                            </td>
                            <td width="40%" class="paramlist_key">突破:</td>
                            <td class="paramlist_value" width="40%">
                                <input type="text"   id="statistics_value_<?php echo $key;?>_break_through" name="statistics[<?php echo $key;?>][break_through]" value="<?php echo $value->break_through;?>" size="25">
                            </td>
                        </tr>
                        <tr class="statistics_<?php echo $key;?>">
                            <td width="40%" class="paramlist_key">角球:</td>
                            <td class="paramlist_value" width="40%">
                                <input type="text"   id="statistics_value_<?php echo $key;?>_corner" name="statistics[<?php echo $key;?>][corner]" value="<?php echo $value->corner;?>" size="25">
                            </td>
                            <td width="40%" class="paramlist_key">越位:</td>
                            <td class="paramlist_value" width="40%">
                                <input type="text"   id="statistics_value_<?php echo $key;?>_break_through" name="statistics[<?php echo $key;?>][off_side]" value="<?php echo $value->off_side;?>" size="25">
                            </td>
                        </tr>
                        <tr class="statistics_<?php echo $key;?>">
                            <td width="40%" class="paramlist_key">犯规:</td>
                            <td class="paramlist_value" width="40%">
                                <input type="text"   id="statistics_value_<?php echo $key;?>_foul" name="statistics[<?php echo $key;?>][foul]" value="<?php echo $value->foul;?>" size="25">
                            </td>
                            <td width="40%" class="paramlist_key">被侵犯:</td>
                            <td class="paramlist_value" width="40%">
                                <input type="text"   id="statistics_value_<?php echo $key;?>_foul_in" name="statistics[<?php echo $key;?>][foul_in]" value="<?php echo $value->foul_in;?>" size="25">
                            </td>
                        </tr>
                        <tr class="statistics_<?php echo $key;?>">
                            <td width="40%" class="paramlist_key">红牌:</td>
                            <td class="paramlist_value" width="40%">
                                <input type="text"   id="statistics_value_<?php echo $key;?>_red_brand" name="statistics[<?php echo $key;?>][red_brand]" value="<?php echo $value->red_brand;?>" size="25">
                            </td>
                            <td width="40%" class="paramlist_key">黄牌:</td>
                            <td class="paramlist_value" width="40%">
                                <input type="text"   id="statistics_value_<?php echo $key;?>_yellow_brand" name="statistics[<?php echo $key;?>][yellow_brand]" value="<?php echo $value->yellow_brand;?>" size="25">
                            </td>
                        </tr>
                        <tr class="statistics_<?php echo $key;?>">
                            <td width="40%" class="paramlist_key">扑救:</td>
                            <td class="paramlist_value" width="40%">
                                <input type="text"   id="statistics_value_<?php echo $key;?>_save" name="statistics[<?php echo $key;?>][save]" value="<?php echo $value->save;?>" size="25">
                            </td>
                            <td width="40%" class="paramlist_key">赛季:</td>
                            <td class="paramlist_value" width="40%">
                                <input type="text"   id="statistics_value_<?php echo $key;?>_save_sesan" name="no" value="<?php echo $key;?>" size="25" disabled="true">
                            </td>
                        </tr>
                        <?php }?>
                        <?php }?>
                        
                        
                    </tbody>
                </table>
            </div>
            
            <h3 id="param-page" class="title jpane-toggler-down"><span>个人技术统计</span>&nbsp;&nbsp;<a href="javascript:statistics_man_prompt();"><font color="red">添加</font></a>&nbsp;&nbsp;<a href="javascript:statistics_man_delete();"><font color="red">删除</font></a></h3>
             <div class="jpane-slider content">
                <table cellspacing="1" width="100%" class="paramlist admintable">
                    <tbody  id="index2">
                        <?php $statistics_man   = $form->getObject()->getStatisticsMan();?>
                        <?php if(!empty($statistics)){?>
                        <?php $statistics_man   = json_decode($statistics_man);?>
                        <?php foreach ($statistics_man as $key => $value) {?>
                        <tr  class="statistics_man_<?php echo $key;?>">
                            <td width="40%" class="paramlist_key">时间:</td>
                            <td class="paramlist_value" width="40%">
                                <input type="text" id="statistics_man_value_<?php echo $key;?>_auto_id" name="statistics[<?php echo $key;?>][auto_id]" value="<?php echo $value->nba_time;?>" size="25">
                            </td>
                            <td width="40%" class="paramlist_key">类型:</td>
                            <td class="paramlist_value" width="40%">
                                <input type="text"   id="statistics_man_value_<?php echo $key;?>_nba_location" name="statistics[<?php echo $key;?>][nba_location]" value="<?php echo $value->league_type;?>" size="25">
                            </td>
                        </tr>
                        <tr class="statistics_man_<?php echo $key;?>">
                            <td width="40%" class="paramlist_key">首发:</td>
                            <td class="paramlist_value" width="40%">
                                <input type="text"   id="statistics_man_value_<?php echo $key;?>_nba_starting" name="statistics[<?php echo $key;?>][nba_starting]" value="<?php echo $value->nba_starting;?>" size="25">
                            </td>
                            <td width="40%" class="paramlist_key">出场:</td>
                            <td class="paramlist_value" width="40%">
                                <input type="text"   id="statistics_man_value_<?php echo $key;?>_averaging" name="statistics[<?php echo $key;?>][averaging]" value="<?php echo $value->nba_sppeared;?>" size="25">
                            </td>
                        </tr>
                        <tr class="statistics_man_<?php echo $key;?>">
                            <td width="40%" class="paramlist_key">进球:</td>
                            <td class="paramlist_value" width="40%">
                                <input type="text"   id="statistics_man_value_<?php echo $key;?>_ball_get" name="statistics[<?php echo $key;?>][ball_get]" value="<?php echo $value->ball_get;?>" size="25">
                            </td>
                            <td width="40%" class="paramlist_key">助攻:</td>
                            <td class="paramlist_value" width="40%">
                                <input type="text"   id="statistics_man_value_<?php echo $key;?>_assists" name="statistics[<?php echo $key;?>][assists]" value="<?php echo $value->assists;?>" size="25">
                            </td>
                        </tr>
                        <tr class="statistics_man_<?php echo $key;?>">
                            <td width="40%" class="paramlist_key">射门:</td>
                            <td class="paramlist_value" width="40%">
                                <input type="text"   id="statistics_man_value_<?php echo $key;?>_shoot" name="statistics[<?php echo $key;?>][shoot]" value="<?php echo $value->shoot;?>" size="25">
                            </td>
                            <td width="40%" class="paramlist_key">突破:</td>
                            <td class="paramlist_value" width="40%">
                                <input type="text"   id="statistics_man_value_<?php echo $key;?>_break_through" name="statistics[<?php echo $key;?>][break_through]" value="<?php echo $value->break_through;?>" size="25">
                            </td>
                        </tr>
                        <tr class="statistics_man_<?php echo $key;?>">
                            <td width="40%" class="paramlist_key">角球:</td>
                            <td class="paramlist_value" width="40%">
                                <input type="text"   id="statistics_man_value_<?php echo $key;?>_corner" name="statistics[<?php echo $key;?>][corner]" value="<?php echo $value->corner;?>" size="25">
                            </td>
                            <td width="40%" class="paramlist_key">越位:</td>
                            <td class="paramlist_value" width="40%">
                                <input type="text"   id="statistics_man_value_<?php echo $key;?>_break_through" name="statistics[<?php echo $key;?>][off_side]" value="<?php echo $value->off_side;?>" size="25">
                            </td>
                        </tr>
                        <tr class="statistics_man_<?php echo $key;?>">
                            <td width="40%" class="paramlist_key">犯规:</td>
                            <td class="paramlist_value" width="40%">
                                <input type="text"   id="statistics_man_value_<?php echo $key;?>_foul" name="statistics[<?php echo $key;?>][foul]" value="<?php echo $value->foul;?>" size="25">
                            </td>
                            <td width="40%" class="paramlist_key">被侵犯:</td>
                            <td class="paramlist_value" width="40%">
                                <input type="text"   id="statistics_man_value_<?php echo $key;?>_foul_in" name="statistics[<?php echo $key;?>][foul_in]" value="<?php echo $value->foul_in;?>" size="25">
                            </td>
                        </tr>
                        <tr class="statistics_man_<?php echo $key;?>">
                            <td width="40%" class="paramlist_key">红牌:</td>
                            <td class="paramlist_value" width="40%">
                                <input type="text"   id="statistics_man_value_<?php echo $key;?>_red_brand" name="statistics[<?php echo $key;?>][red_brand]" value="<?php echo $value->red_brand;?>" size="25">
                            </td>
                            <td width="40%" class="paramlist_key">黄牌:</td>
                            <td class="paramlist_value" width="40%">
                                <input type="text"   id="statistics_man_value_<?php echo $key;?>_yellow_brand" name="statistics[<?php echo $key;?>][yellow_brand]" value="<?php echo $value->yellow_brand;?>" size="25">
                            </td>
                        </tr>
                        <tr class="statistics_man_<?php echo $key;?>">
                            <td width="40%" class="paramlist_key">扑救:</td>
                            <td class="paramlist_value" width="40%">
                                <input type="text"   id="statistics_man_value_<?php echo $key;?>_save" name="statistics[<?php echo $key;?>][save]" value="<?php echo $value->save;?>" size="25">
                            </td>
                            <td width="40%" class="paramlist_key">赛季:</td>
                            <td class="paramlist_value" width="40%">
                                <input type="text"   id="statistics_man_value_<?php echo $key;?>_save_sesan" name="no" value="<?php echo $key;?>" size="25" disabled="true">
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
        global_complete('value_nba_team_show', 'fb_international', 'auto_complete_nba_team')
        get_team_name_by_id();
        <?php if(!empty($arr)){?>
            <?php foreach ($arr as $key => $value) {?>
                global_complete('resum_<?php echo $key;?>_value_team','fb_international', 'auto_complete_nba_team');
                global_complete('resum_<?php echo $key;?>_value_league_name','football', 'auto_complete_nba_team');
            <?php }?>
        <?php }?>
    });
</script>
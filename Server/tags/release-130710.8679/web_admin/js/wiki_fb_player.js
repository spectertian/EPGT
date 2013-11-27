/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

    function get_team_id_by_title()
    {
        var title   = $("#value_nba_team_show").val();
        if(title !='') {
            $.ajax({
                url :  wikiUrl + "/ajax_get_id_by_title?title="+title,
                cache: "false",
                dataType: "text",
                success:function(data){
                    if(data!=''){
                        $("#value_nba_team").val(data);
                    }
                }
            });
        }
    }

    function get_team_name_by_id()
    {
        var id   = $("#value_nba_team").val();
        if (id !='') {
            $.ajax({
                url :  wikiUrl + "/ajax_get_title_by_id?id="+id,
                cache: "false",
                dataType: "text",
                success:function(data){
                    $("#value_nba_team_show").val(data);
                }
            });
        }
    }

    //球员个人履历弹窗
    function resume_prompt()
    {
        var date    = window.prompt('请输入赛季，格式:2010-2011');
        if(date){
            var id      = "resum_"+date+"_value_league_name";
            if($("#"+id).length > 0){
                alert('该赛季球员履历已存在!');
                return;
            }
            var resume  = $("#resume");
            var html    = get_resume_html(date);
            var content = resume.html();
            resume.html(html+content);
            $("#resum_"+date+"_value_nba_sppeared").focus();
            //自动完成
            action_complete('resum_'+date+'_value_team','fb_international');
            action_complete('resum_'+date+'_value_league_name','football');
        }
    }

    function resume_delete()
    {
        var cls    = window.prompt('请输入赛季，格式:2010-2011');
        if(cls){
            $("tr.resume_"+cls).remove();
        }

    }

    //球员个人履历
    function get_resume_html(date){
        var str = '';
        str +="              <tr class=\"resume_"+date+"\">";
        str +="                 <td class=\"key\"><label for=\"nba_season\">赛季<\/label><\/td>";
        str +="                    <td>";
        str +="                        <input type=\"text\"   id=\"resum_"+date+"_value_nba_season\" name=\"resume["+date+"][nba_season]\" value=\""+date+"\" disabled=\"true\">";
        str +="                    <\/td>";
        str +="                    <td class=\"key\"><label for=\"ball_get\">出场<\/label><\/td>";
        str +="                    <td>";
        str +="                        <input type=\"text\"   id=\"resum_"+date+"_value_nba_sppeared\" name=\"fb_resume["+date+"][nba_sppeareds]\" value=\"\">";
        str +="                    <\/td>";
        str +="                    <td class=\"key\"><label for=\"ball_get\">进球<\/label><\/td>";
        str +="                    <td>";
        str +="                        <input type=\"text\" id=\"resum_"+date+"_value_ball_get\" name=\"fb_resume["+date+"][ball_get]\" value=\"\">";
        str +="                    <\/td>";
        str +="                <\/tr>";
        str +="                <tr class=\"resume_"+date+"\">";
        str +="                    <td class=\"key\"><label for=\"nba_season\">球队<\/label><\/td>";
        str +="                    <td>";
        str +="                        <input type=\"text\" id=\"resum_"+date+"_value_team\" name=\"fb_resume["+date+"][team]\" value=\"\">";
        str +="                    <\/td>";
        str +="                    <td class=\"key\"><label for=\"ball_get\">联赛名称<\/label><\/td>";
        str +="                    <td>";
        str +="                        <input type=\"text\"  id=\"resum_"+date+"_value_league_name\" name=\"fb_resume["+date+"][league_name]\">";
        str +="                    <\/td>";
        str +="                <\/tr>";
        return str;
    }

    function resume_complete(id){
        action_complete(id,'fb_international');
    }
    function action_complete(id,data){
       $('#'+id).simpleAutoComplete(wikiUrl  + '/auto_complete_nba_team',{
            autoCompleteClassName: 'autocomplete',
            autoFill: false,
            selectedClassName: 'sel',
            attrCallBack: 'rel',
            identifier: data,
            max       : 20
        });
    }

    function statistics_prompt()
    {
        var date    = window.prompt('请输入球员赛季，格式:2010-2011');
        if(date) {
            var ele = $(".statistics_"+date);
            if(ele.length >0){
                alert('赛季已存在!');
                return ;
            }
            var index   = $("#index");
            var content = index.html();
            var str     = get_statistics_html(date);
            index.html(str + content);
            $("#statistics_value_"+date+"_auto_id").focus();
        }
    }

    function statistics_delete(){
        var date    = window.prompt('请输入球员赛季，格式:2010-2011');
        if(date) {
            $("tr.statistics_"+date).remove();
        }
    }

    function get_statistics_html(id){
        var str= '';
        str +="                        <tr class=\"statistics_"+id+"\">";
        str +="                            <td width=\"40%\" class=\"paramlist_key\">号码:<\/td>";
        str +="                            <td class=\"paramlist_value\" width=\"40%\">";
        str +="                                <input type=\"text\" id=\"statistics_value_"+id+"_auto_id\" name=\"statistics["+id+"][auto_id]\" value=\"\" size=\"25\">";
        str +="                            <\/td>";
        str +="                            <td width=\"40%\" class=\"paramlist_key\">位置:<\/td>";
        str +="                            <td class=\"paramlist_value\" width=\"40%\">";
        str +="                                <input type=\"text\"   id=\"statistics_value_"+id+"_nba_location\" name=\"statistics["+id+"][nba_location]\" value=\"\" size=\"25\">";
        str +="                            <\/td>";
        str +="                        <\/tr>";
        str +="                        <tr class=\"statistics_"+id+"\">";
        str +="                            <td width=\"40%\" class=\"paramlist_key\">首发:<\/td>";
        str +="                            <td class=\"paramlist_value\" width=\"40%\">";
        str +="                                <input type=\"text\"   id=\"statistics_value_"+id+"_nba_starting\" name=\"statistics["+id+"][nba_starting]\" value=\"\" size=\"25\">";
        str +="                            <\/td>";
        str +="                            <td width=\"40%\" class=\"paramlist_key\">均场:<\/td>";
        str +="                            <td class=\"paramlist_value\" width=\"40%\">";
        str +="                                <input type=\"text\"   id=\"statistics_value_"+id+"_averaging\" name=\"statistics["+id+"][averaging]\" value=\"\" size=\"25\">";
        str +="                            <\/td>";
        str +="                        <\/tr>";
        str +="                        <tr class=\"statistics_"+id+"\">";
        str +="                            <td width=\"40%\" class=\"paramlist_key\">进球:<\/td>";
        str +="                            <td class=\"paramlist_value\" width=\"40%\">";
        str +="                                <input type=\"text\"   id=\"statistics_value_"+id+"_ball_get\" name=\"statistics["+id+"][ball_get]\" value=\"\" size=\"25\">";
        str +="                            <\/td>";
        str +="                            <td width=\"40%\" class=\"paramlist_key\">助攻:<\/td>";
        str +="                            <td class=\"paramlist_value\" width=\"40%\">";
        str +="                                <input type=\"text\"   id=\"statistics_value_"+id+"_assists\" name=\"statistics["+id+"][assists]\" value=\"\" size=\"25\">";
        str +="                            <\/td>";
        str +="                        <\/tr>";
        str +="                        <tr class=\"statistics_"+id+"\">";
        str +="                            <td width=\"40%\" class=\"paramlist_key\">射门:<\/td>";
        str +="                            <td class=\"paramlist_value\" width=\"40%\">";
        str +="                                <input type=\"text\"   id=\"statistics_value_"+id+"_shoot\" name=\"statistics["+id+"][shoot]\" value=\"\" size=\"25\">";
        str +="                            <\/td>";
        str +="                            <td width=\"40%\" class=\"paramlist_key\">突破:<\/td>";
        str +="                            <td class=\"paramlist_value\" width=\"40%\">";
        str +="                                <input type=\"text\"   id=\"statistics_value_"+id+"_break_through\" name=\"statistics["+id+"][break_through]\" value=\"\" size=\"25\">";
        str +="                            <\/td>";
        str +="                        <\/tr>";
        str +="                        <tr class=\"statistics_"+id+"\">";
        str +="                            <td width=\"40%\" class=\"paramlist_key\">角球:<\/td>";
        str +="                            <td class=\"paramlist_value\" width=\"40%\">";
        str +="                                <input type=\"text\"   id=\"statistics_value_"+id+"_corner\" name=\"statistics["+id+"][corner]\" value=\"\" size=\"25\">";
        str +="                            <\/td>";
        str +="                            <td width=\"40%\" class=\"paramlist_key\">越位:<\/td>";
        str +="                            <td class=\"paramlist_value\" width=\"40%\">";
        str +="                                <input type=\"text\"   id=\"statistics_value_"+id+"_break_through\" name=\"statistics["+id+"][off_side]\" value=\"\" size=\"25\">";
        str +="                            <\/td>";
        str +="                        <\/tr>";
        str +="                        <tr class=\"statistics_"+id+"\">";
        str +="                            <td width=\"40%\" class=\"paramlist_key\">犯规:<\/td>";
        str +="                            <td class=\"paramlist_value\" width=\"40%\">";
        str +="                                <input type=\"text\"   id=\"statistics_value_"+id+"_foul\" name=\"statistics["+id+"][foul]\" value=\"\" size=\"25\">";
        str +="                            <\/td>";
        str +="                            <td width=\"40%\" class=\"paramlist_key\">被侵犯:<\/td>";
        str +="                            <td class=\"paramlist_value\" width=\"40%\">";
        str +="                                <input type=\"text\"   id=\"statistics_value_"+id+"_foul_in\" name=\"statistics["+id+"][foul_in]\" value=\"\" size=\"25\">";
        str +="                            <\/td>";
        str +="                        <\/tr>";
        str +="                        <tr class=\"statistics_"+id+"\">";
        str +="                            <td width=\"40%\" class=\"paramlist_key\">红牌:<\/td>";
        str +="                            <td class=\"paramlist_value\" width=\"40%\">";
        str +="                                <input type=\"text\"   id=\"statistics_value_"+id+"_red_brand\" name=\"statistics["+id+"][red_brand]\" value=\"\" size=\"25\">";
        str +="                            <\/td>";
        str +="                            <td width=\"40%\" class=\"paramlist_key\">黄牌:<\/td>";
        str +="                            <td class=\"paramlist_value\" width=\"40%\">";
        str +="                                <input type=\"text\"   id=\"statistics_value_"+id+"_yellow_brand\" name=\"statistics["+id+"][yellow_brand]\" value=\"\" size=\"25\">";
        str +="                            <\/td>";
        str +="                        <\/tr>";
        str +="                        <tr class=\"statistics_"+id+"\">";
        str +="                            <td width=\"40%\" class=\"paramlist_key\">扑救:<\/td>";
        str +="                            <td class=\"paramlist_value\" width=\"40%\">";
        str +="                                <input type=\"text\"   id=\"statistics_value_"+id+"_save\" name=\"statistics["+id+"][save]\" value=\"\" size=\"25\">";
        str +="                            <\/td>";
        str +="                            <td width=\"40%\" class=\"paramlist_key\">赛季:<\/td>";
        str +="                            <td class=\"paramlist_value\" width=\"40%\">";
        str +="                                <input type=\"text\"   id=\"statistics_value_"+id+"_save_sesan\" name=\"no\" value=\""+id+"\" size=\"25\" disabled=\"true\">";
        str +="                            <\/td>";
        str +="                        <\/tr>";
        return str;
    }

    function get_statistics_man_html(id){
        var str= '';
        str +="                        <tr class=\"statistics_man_"+id+"\">";
        str +="                            <td width=\"40%\" class=\"paramlist_key\">时间:<\/td>";
        str +="                            <td class=\"paramlist_value\" width=\"40%\">";
        str +="                                <input type=\"text\" id=\"statistics_man_value_"+id+"_auto_id\" name=\"statistics_man["+id+"][nba_time]\" value=\"\" size=\"25\">";
        str +="                            <\/td>";
        str +="                            <td width=\"40%\" class=\"paramlist_key\">类型:<\/td>";
        str +="                            <td class=\"paramlist_value\" width=\"40%\">";
        str +="                                <input type=\"text\"   id=\"statistics_man_value_"+id+"_league_type\" name=\"statistics_man["+id+"][league_type]\" value=\"\" size=\"25\">";
        str +="                            <\/td>";
        str +="                        <\/tr>";
        str +="                        <tr class=\"statistics_man_"+id+"\">";
        str +="                            <td width=\"40%\" class=\"paramlist_key\">首发:<\/td>";
        str +="                            <td class=\"paramlist_value\" width=\"40%\">";
        str +="                                <input type=\"text\"   id=\"statistics_man_value_"+id+"_nba_starting\" name=\"statistics_man["+id+"][nba_starting]\" value=\"\" size=\"25\">";
        str +="                            <\/td>";
        str +="                            <td width=\"40%\" class=\"paramlist_key\">出场:<\/td>";
        str +="                            <td class=\"paramlist_value\" width=\"40%\">";
        str +="                                <input type=\"text\"   id=\"statistics_man_value_"+id+"_nba_sppeared\" name=\"statistics_man["+id+"][nba_sppeared]\" value=\"\" size=\"25\">";
        str +="                            <\/td>";
        str +="                        <\/tr>";
        str +="                        <tr class=\"statistics_man_"+id+"\">";
        str +="                            <td width=\"40%\" class=\"paramlist_key\">进球:<\/td>";
        str +="                            <td class=\"paramlist_value\" width=\"40%\">";
        str +="                                <input type=\"text\"   id=\"statistics_man_value_"+id+"_ball_get\" name=\"statistics_man["+id+"][ball_get]\" value=\"\" size=\"25\">";
        str +="                            <\/td>";
        str +="                            <td width=\"40%\" class=\"paramlist_key\">助攻:<\/td>";
        str +="                            <td class=\"paramlist_value\" width=\"40%\">";
        str +="                                <input type=\"text\"   id=\"statistics_man_value_"+id+"_assists\" name=\"statistics_man["+id+"][assists]\" value=\"\" size=\"25\">";
        str +="                            <\/td>";
        str +="                        <\/tr>";
        str +="                        <tr class=\"statistics_man_"+id+"\">";
        str +="                            <td width=\"40%\" class=\"paramlist_key\">射门:<\/td>";
        str +="                            <td class=\"paramlist_value\" width=\"40%\">";
        str +="                                <input type=\"text\"   id=\"statistics_man_value_"+id+"_shoot\" name=\"statistics_man["+id+"][shoot]\" value=\"\" size=\"25\">";
        str +="                            <\/td>";
        str +="                            <td width=\"40%\" class=\"paramlist_key\">突破:<\/td>";
        str +="                            <td class=\"paramlist_value\" width=\"40%\">";
        str +="                                <input type=\"text\"   id=\"statistics_man_value_"+id+"_break_through\" name=\"statistics_man["+id+"][break_through]\" value=\"\" size=\"25\">";
        str +="                            <\/td>";
        str +="                        <\/tr>";
        str +="                        <tr class=\"statistics_man_"+id+"\">";
        str +="                            <td width=\"40%\" class=\"paramlist_key\">角球:<\/td>";
        str +="                            <td class=\"paramlist_value\" width=\"40%\">";
        str +="                                <input type=\"text\"   id=\"statistics_man_value_"+id+"_corner\" name=\"statistics_man["+id+"][corner]\" value=\"\" size=\"25\">";
        str +="                            <\/td>";
        str +="                            <td width=\"40%\" class=\"paramlist_key\">越位:<\/td>";
        str +="                            <td class=\"paramlist_value\" width=\"40%\">";
        str +="                                <input type=\"text\"   id=\"statistics_man_value_"+id+"_break_through\" name=\"statistics_man["+id+"][off_side]\" value=\"\" size=\"25\">";
        str +="                            <\/td>";
        str +="                        <\/tr>";
        str +="                        <tr class=\"statistics_man_"+id+"\">";
        str +="                            <td width=\"40%\" class=\"paramlist_key\">犯规:<\/td>";
        str +="                            <td class=\"paramlist_value\" width=\"40%\">";
        str +="                                <input type=\"text\"   id=\"statistics_man_value_"+id+"_foul\" name=\"statistics_man["+id+"][foul]\" value=\"\" size=\"25\">";
        str +="                            <\/td>";
        str +="                            <td width=\"40%\" class=\"paramlist_key\">被侵犯:<\/td>";
        str +="                            <td class=\"paramlist_value\" width=\"40%\">";
        str +="                                <input type=\"text\"   id=\"statistics_man_value_"+id+"_foul_in\" name=\"statistics_man["+id+"][foul_in]\" value=\"\" size=\"25\">";
        str +="                            <\/td>";
        str +="                        <\/tr>";
        str +="                        <tr class=\"statistics_man_"+id+"\">";
        str +="                            <td width=\"40%\" class=\"paramlist_key\">红牌:<\/td>";
        str +="                            <td class=\"paramlist_value\" width=\"40%\">";
        str +="                                <input type=\"text\"   id=\"statistics_man_value_"+id+"_red_brand\" name=\"statistics_man["+id+"][red_brand]\" value=\"\" size=\"25\">";
        str +="                            <\/td>";
        str +="                            <td width=\"40%\" class=\"paramlist_key\">黄牌:<\/td>";
        str +="                            <td class=\"paramlist_value\" width=\"40%\">";
        str +="                                <input type=\"text\"   id=\"statistics_man_value_"+id+"_yellow_brand\" name=\"statistics_man["+id+"][yellow_brand]\" value=\"\" size=\"25\">";
        str +="                            <\/td>";
        str +="                        <\/tr>";
        str +="                        <tr class=\"statistics_man_"+id+"\">";
        str +="                            <td width=\"40%\" class=\"paramlist_key\">扑救:<\/td>";
        str +="                            <td class=\"paramlist_value\" width=\"40%\">";
        str +="                                <input type=\"text\"   id=\"statistics_man_value_"+id+"_save\" name=\"statistics_man["+id+"][save]\" value=\"\" size=\"25\">";
        str +="                            <\/td>";
        str +="                            <td width=\"40%\" class=\"paramlist_key\">赛季:<\/td>";
        str +="                            <td class=\"paramlist_value\" width=\"40%\">";
        str +="                                <input type=\"text\"   id=\"statistics_man_value_"+id+"_save_sesan\" name=\"no\" value=\""+id+"\" size=\"25\" disabled=\"true\">";
        str +="                            <\/td>";
        str +="                        <\/tr>";
        return str;
    }

    function statistics_man_prompt()
    {
        var data    = window.prompt('请输入赛季，格式:2010-2011');
        if(data){
            var str     = get_statistics_man_html(data);
            var index2  = $("#index2");
            var content = index2.html();
            index2.html(str + content);
        }
    }
   function statistics_man_delete(){
        var date    = window.prompt('请输入球员赛季，格式:2010-2011');
        if(date) {
            $("tr.statistics_man_"+date).remove();
        }
    }


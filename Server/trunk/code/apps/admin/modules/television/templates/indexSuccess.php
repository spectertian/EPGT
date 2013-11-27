<style type="text/css">
.time INPUT,.date INPUT,.sf_admin_list_td_name INPUT{
    width:100%;
    text-align:center;
}
.sf_admin_list_td_wiki INPUT,.tags INPUT{
    width:100%;
}
.sf_admin_list_th_name INPUT,.sf_admin_list_th_wiki INPUT{
    width:90%;
    text-align:center;
}
.sf_admin_list_th_actions .noEdit .actions .save .edit DIV,.sf_admin_list_th_publish,.sf_admin_date,.sf_admin_list_th_time,.time,.sf_admin_list_th_channel,.channel{
    text-align:center;
}
</style>
<script type="text/javascript">

function makeUpDate(element,program){
    var tr = (element.attr("tagName") == 'TR') ? element : element.parents("tr");
    var td = null;
    tr.find("TD").each(function(x){
        var td = $(this);
        var element = td.find("INPUT,SELECT");
        if(!(td.hasClass('noEdit'))){
            var element_name = element.attr('name');
            var element_value =  element.attr('value');
        }
        if(td.hasClass('checkbox') && td.hasClass('noEdit')){
            program['id'] = td.find("INPUT,SELECT").val();
            return true;
        }
        if(td.hasClass("wiki")){
            program['wiki'] =  element_value;
            program['wiki_id'] = (element.attr('value').length != 0) ? element.attr('rel') : '' ;
        }
        program[element_name] = element_value;
    });
    return program;
}

//function selectToImg(td,value,img_src){                    //由SELECT转换为图片格式
//    var rel = eval("("+td.attr('rel')+")");
//    if(value == 1){
//        rel.options[0].selected = '"selected"' ;
//        rel.options[1].selected = false;
//    }else{
//        rel.options[0].selected = false;
//        rel.options[1].selected = '"selected"' ;
//    }
//    var select_rel = '{ "options":[ {text:"是",value:1,selected:'+ rel.options[0].selected +'},{text:"否",value:0,selected:'+ rel.options[1].selected +'} ] }';
//    td.attr('rel',select_rel);
//    var img = $("<img />").attr('src',img_src[value]);
//    return img;
//}

function selectToText(td,value) {
    var select = eval("("+td.attr("rel")+")");
    var text = select.options[value].text;
    td.attr("selected",value);
    return text;
}

//更改 ACTIONS
function textToActions(action,is_new){
	var first_A = $("<a></a>");
	var second_A = $("<a></a>");
	var div = $("<div></div>");

	if(action == 'edit'){
		first_A.text('编辑 ').attr({'href':'#'}).unbind('click').bind('click',function(){
                        changeTR($(this),new_tr_label,false);
			return false;
		});
		second_A.text(" 删除 ").attr({'href':'#'}).unbind('click').bind('click',function(){
                        program_delete($(this),1);
			return false;
		});
	}
	if(action == 'save'){
		first_A.text(' 保存 ').attr({'href':'#'}).unbind('click').bind('click',function(){
                        var current_element = $(this);
                        program = makeUpDate($(this),program);
                        //提交至服务端处理
                        $.post('<?php echo url_for('television/Save') ?>',program, function(data,textStatus){
                            if(textStatus == 'success')
                            {
                                data = eval(data);
                                if(data.television_id)
                                {
                                    saveTR(current_element,true,is_new,data.television_id);
                                }else{
                                    //console.log('ERROR:信息保存失败！服务端错误！');
                                }
                            }
                        },'json');
			return false;
		});
		second_A.text(" 取消 ").attr({'href':'#'}).unbind('click').bind('click',function(){
			if(is_new){
				$(this).parents("TR").remove();
				return true;
			}
                        saveTR($(this),false,false);
                        return false;
		});
	}
	div.append(first_A).append(second_A);
	return div;
}

//保存TR,还原与更新合并
function saveTR(element,is_post,is_new,program_id)
{
    var tr = (element.attr("tagName") == 'TR') ? element : element.parents("tr");
    var td = null;
    var action = 'edit';
    var current_action  = 'save';
    tr.attr("edit",0); //还原编辑状态
    tr.find("TD").each(function(x){
        td = $(this);
        var child_node = td.find("INPUT,SELECT");
        var value = child_node.val();
        var backValue = td.attr("backValue");  //获取备份的值
        if(child_node.length == 0 || td.hasClass('checkbox') ){
            if(td.hasClass('actions')){
                td.removeClass(current_action).addClass(action);
                var div = textToActions(action,false);
                insertElement(td,div,false);
            }
            if( is_new && td.hasClass('checkbox') ){  //更改ID为当前的值
                child_node.attr('value',program_id);
            }
            return true;
        }
        if(is_post){
            value = value.replace(/[\s]+/ig,'');
        }else{
            value = backValue.replace(/[\s]+/ig,'');
        }
        if(td.hasClass('select')){   //SELECT 转换 TEXT
            var text = selectToText(td,value);
            insertElement(td,text,false);
            return true;
        }
        td.empty().text(value);
    });
}

//将TD中元素的值记录到相应TD的REL中
function bakValue(off,td){
    if(!off){
        var value = ( td.find('INPUT,SELECT').val() || td.text() );
        if( td.hasClass("select") ) {
            value = td.attr("selected");
        }
        value = value.replace(/[\s]+/ig,'');
        td.attr('backValue',value);
        return td;
    }
    return false;
}

//核心函数，主要负责创建和更改TR
function changeTR(element,new_tr_label,is_new){
    var tr = null;
    var _tr = null;
    //判断是否为新TR
    if(is_new == true){
        tr = $("<tr></tr>").addClass(new_tr_label).attr('edit',1);
        _tr = element.find("THEAD > TR > TH");
    }else{
        tr = (element.attr("tagName") == 'TR') ? element : element.parents("tr");
        _tr = tr.find("TD");
    }
    var edit = tr.attr("edit",1); //设置TR为编辑状态
    _tr.each(function(x){
        var _element = $(this);
        var td = (_element.attr('tagName') == 'TD') ? _element : $("<td></td>");
        var name = _element.attr('name');
        var type = null;
        type = ( _element.hasClass('time') )                        //判断当前元素类型,用于对普通INPUT的加工
               ? 'time'
               : ( _element.hasClass('date')  ? 'date' : null );
        var value = _element.text().replace(/[\s]+/ig,'');          //获取当前过滤完空格之后的 TD > TEXT 值
        var classes = _element.attr('class');                       //获取当前元素的所有CLASS
        if(_element.hasClass('noEdit')){                            //处理不需要生成文本框的元素
            if( _element.hasClass('autoID') ){
               var textNode = textToID(_element,is_new);
               td = insertElement(td,textNode,is_new,classes);
            }
            if( _element.hasClass('checkbox') ){
                value = (is_new) ? value : td.find("INPUT").val();
                var checkbox = textToCheckbox(name,value,is_new);
                td = insertElement(td,checkbox,is_new,classes);
            }
            if(_element.hasClass('actions')){                       //编辑状态转入以及新建状态的TR，ACTIONS都应该为SAVE状态
                    var action = 'save';                            //更改状态，当前状态为EDIT
                    td.attr('class',classes);
                    td.removeClass('edit').addClass(action);
                    var div = textToActions(action,is_new);
                    td = insertElement(td,div,is_new);
            }
            if(_element.hasClass('channel') && is_new){
                var text = _element.attr("rel");
                td = insertElement(td,text,is_new,classes);
            }
            tr = insertTdToTr(tr,td,is_new);
            bakValue(is_new,td);                                    //备份原有数据
            return true;                                            //跳过本次循环
        }
        if(_element.hasClass('select')){                            //检查是否有SELECT标识的元素存在
            var select_options = _element.attr('rel');
            var select = textToSelect(td,name,select_options,is_new);
            td = insertElement(td,select,is_new,classes);
            td.attr("rel",select_options);
            tr = insertTdToTr(tr,td,is_new);
            bakValue(is_new,td);                                    //备份原有数据
            return true;                                            //跳过此次循环
        }
        var input = textToInput(name,value,is_new,type);
        if(_element.hasClass('wiki')){
            input.simpleAutoComplete('<?php echo url_for('program/loadWiki') ?>',{
                autoCompleteClassName: 'autocomplete',
                autoFill: false,
                attrCallBack:'rel',
                selectedClassName: 'sel',
                identifier: 'name',
                max       : 20
            },function(data){
                var data = eval("("+data+")");
                var id = data.id;
                var title = data.title;
                input.parents("TR").find("TD:eq(2) > INPUT").val(title);
                input.parent("TD").attr('rel',id);
                input.attr('rel',id);
            });
            input.attr('rel',td.attr('rel'));
        }

        td = insertElement(td,input,is_new,classes);
        tr = insertTdToTr(tr,td,is_new);
        bakValue(is_new,td);                                        //备份原有数据
    });
    insertTrToTable(tr,element,is_new);
}

function insertTrToTable(tr,table,is_new){                      //TR插入TABLE事件，判断当前TR是否为新TR
    return ((is_new) ? table.prepend(tr) : false);
}
//处理TD插入TR事件，判断是否为新建TD
function insertTdToTr(tr,td,is_new){
    return ((is_new) ? tr.append(td) : false);
}
//生成 CHECKBOX
function textToCheckbox(name,value,is_new){
    var text = (is_new) ? '' : value;
    var checkbox = $("<input />").attr({'type':'checkbox','name':name,'value':text});
    return checkbox;
}
//生成普通的INPUT框
function textToInput(name,value,is_new,type){
    value = ((is_new == true) && ( type == null )) ? '' : value;
    var date = '<?php echo $sf_user->getAttribute('date') ?>';
    var is_now = (is_new == true) ? true : false;
    var input = $("<input />").attr({'type':'text','name':name,'value':value});
    input = ( type == 'time' )
            ? input.DateTimeMask({masktype :'4', isnow :is_now,isnull:false})
            : (
                ( type == 'date' )
                ? input.DateTimeMask({is_null:false,isnow:false}).val(date)
                : input
            );
    return input;
}
//向TD中插入元素,并设置TD属性
function insertElement(td,element,is_new,classes){
    if(is_new){
        td.attr('class',classes);
    }
    td.empty().append(element);
    return td;
}
//将TD中的内容转换为SELECT下拉框
function textToSelect(td,name,value,is_new){
    value = eval("("+value+")");
    var options = value.options;
    var select = $("<select></select>").attr({'name':name});
    var option = null;
    var selected = td.attr("selected");
    var i;
    for(i in options){
        option = $("<option></option>").attr({'value':options[i].value}).text(options[i].text);
        select.append(option);
    }
    select.val(selected);
    return select;
}
//根据IS_NEW参数动态更改列表ID的值
function textToID(element,is_new){
    var autoID = (is_new) ? eval(element.parents("TABLE").find("TBODY > TR ").length + 1) : element.text();   //如果为EDIT 模式，原有ID不变
    var textNode = document.createTextNode(autoID);
    return textNode;
}

//JSON POST
var program = { 'id': 0 ,
                'name' : '',
                'channel_id' : <?php echo $sf_user->getAttribute('channel_id') ?>,
                'publish' : '',
                'time' : '',
                'date' : '',
                'wiki_id': '',
                'tags':''
              };
var new_tr_label = 'newTelevision';

$(document).ready(function(){
       $("#admin_list > TBODY > TR > TD > A:eq(0)").bind('click',function(){
            changeTR($(this),new_tr_label,false);
       });

       $("INPUT[type=checkbox]").click(function(event){
               event.stopPropagation();
      });

      $("#admin_list > TBODY > TR > TD").bind('click',function(){
          if($(this).hasClass('actions')){
              return true;
          }
           var edit = $(this).parents('TR').attr('edit');
           if(edit == 0){
               changeTR($(this),new_tr_label,false);
               return true;
           }
     });

    $(".addNewProgram").live('click',function(){
       var table = $("#admin_list");
       changeTR(table,new_tr_label,true);
   });

   $(".toolbar .delete").click(function(){
       program_delete();
   });

});

function program_delete(element,method){
    if( method == 1 ) {
        element.parents('TR').siblings().each(function(){
            $(this).find("TD:eq(1) > INPUT[type=checkbox]").attr("checked",false);
        });
        element.parents('TR').find("TD:eq(1) > INPUT[type=checkbox]").attr("checked",true);
    }
    admin_form = document.getElementById("adminForm");
    admin_form.action = "<?php echo url_for('television/delete') ?>";
    admin_form.submit();
}

function checkAll(){
  $("#admin_list > TBODY ").find("INPUT[type=checkbox]").attr('checked',true);
}
</script>
<?php include_partial('television/toobal') ?>
<?php include_partial('global/flashes') ?>
<?php
    $week = array( "options" => array() );
    for($i = 1;$i<=7;$i++){
        $week["options"][$i]["text"] = '周'.$i;
        $week["options"][$i]["value"] = $i;
    }
    $week = json_encode($week);
?>
<form action="#" id="adminForm" name="adminForm" method="post" >
<table class="adminlist" id="admin_list" cellspacing="1">
    <thead>
        <tr>
            <th width="5%" class="noEdit autoID">#</th>
            <th id="sf_admin_list_batch_actions" class="noEdit checkbox" width="5%" name="ids[]" style="text-align: center;">
                <input id="sf_admin_list_batch_checkbox" name="toggle" onclick="checkAll();" type="checkbox" />
            </th>
            <th class="title sf_admin_text sf_admin_list_th_name name" name="name" style="width:30%;">
                  名称
            </th>
            <th class="title sf_admin_text sf_admin_list_th_channel noEdit channel" rel="<?php echo $channel_name ?>" style="width:15%;">
              频道
            </th>
            <th class="title sf_admin_date sf_admin_list_th_time time" name="time" style="width:10%;">
                播放时间
            </th>
            <th class="title sf_admin_date sf_admin_list_th_date select" rel='<?php echo $week ?>' name="date" style="width:10%;">
                  播放日期
            </th>
            <th class="title sf_admin_text sf_admin_list_th_wiki wiki" name="wiki" style="width:15%;">
              维基
            </th>
            <th class="title sf_admin_text sf_admin_list_th_actions noEdit actions edit" style="width:10%; text-align: center;">
              操作
            </th>
        </tr>
</thead>
<tfoot>
  <tr>
    <td colspan="13">
        <del class="container">
            <div class="pagination"></div>
        </del>
    </td>
  </tr>
</tfoot>
<tbody>
    <?php
            $i= 0;
            foreach($televisions as $television):
            $i++;
    ?>
    <tr class="row0" edit="0">
        <td class="noEdit autoID"><?php echo $i ?></td>
        <td class="noEdit checkbox" name="ids[]" style="text-align: center;">
          <input name="ids[]" value="<?php echo $television->getId() ?>" class="sf_admin_batch_checkbox " type="checkbox">
        </td>
        <td class="sf_admin_text sf_admin_list_td_name" name="name" style="text-align:left;">
            <?php echo $television->getWikiTitle() ?>
        </td>
        <td class="sf_admin_text sf_admin_list_td_channel noEdit channel" name="channel_id" style="text-align:center;">
           <?php echo $channel_name ?>
        </td>
        <td class="sf_admin_date sf_admin_list_td_time time" name="time" style="text-align:center;">
            <?php echo $television->getPlayTime() ?>
        </td>
        <td class="sf_admin_date sf_admin_list_td_date select" rel='<?php echo $week ?>' selected="<?php echo $television->getWeekDay() ?>" name="date" style="text-align:center;">
            周<?php echo $television->getWeekDay() ?>
        </td>
        <td class="sf_admin_text sf_admin_list_td_wiki wiki" name="wiki" rel="<?php echo $television->getWikiId() ?>">
            <?php echo  ($wiki = $television->getWiki()) ? $wiki->getTitle() : 'Wiki关联错误' ?>
        </td>
        <td class="sf_admin_text sf_admin_list_td_actions noEdit actions edit" rel="" style="text-align: center;">
            <a href="#" onclick=" changeTR($(this),new_tr_label,false);return false;" >编辑</a> | <a href="#" onclick="program_delete($(this),1);return false;"  >删除</a>
        </td>
    </tr>
   <?php endforeach; ?>
    </tbody>
</table>
</form>

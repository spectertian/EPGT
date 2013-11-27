<style>
	.aaa{overflow:auto;height:500px}
	.adminlist .red td{color:red}
</style>
<script type="text/javascript">
//全局数据
var wiki_ids;
//全选
function checkAll(biaozhi)
{
    if(biaozhi==1){
        var flag    = $("#sf_admin_list_batch_checkbox").attr('checked');
    }else{
        var flag    = $("#sf_admin_list_batch_checkbox_foot").attr('checked');
    }
    var box     = $("input[type=checkbox]");
    if (flag) {
        box.attr('checked',true);
    }else{
        box.attr('checked',false);
    }

}

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

function selectToImg(td,value,img_src){                    //由SELECT转换为图片格式
    var rel = eval("("+td.attr('rel')+")");
    if(value == 1){
        rel.options[0].selected = '"selected"' ;
        rel.options[1].selected = false;
    }else{
        rel.options[0].selected = false;
        rel.options[1].selected = '"selected"' ;
    }
    var select_rel = '{ "options":[ {text:"是",value:1,selected:'+ rel.options[0].selected +'},{text:"否",value:0,selected:'+ rel.options[1].selected +'} ] }';
    td.attr('rel',select_rel);
    var img = $("<img />").attr('src',img_src[value]);
    return img;
}

//更改 ACTIONS
function textToActions(action,is_new){
	var first_A =$("<a ></a>");
	var second_A =$("<a ></a>");
	var third_A = $("<a></a>");
  var div = $("<div></div>");
	if(action == 'edit'){

  	first_A.text(" 编辑 ").attr({'href':'#'}).unbind('click').bind('click',function(){
                        changeTR($(this),new_tr_label,false);
			return false;
		});
		second_A.text(" 删除 ").attr({'href':'#'}).unbind('click').bind('click',function(){
                        program_deleteone($(this),1);
			return false;
		});
	 


	}

	if(action == 'save'){
		first_A.text(' 保存 ').attr({'href':'#'}).unbind('click').bind('click',function(){


                        var current_element = $(this);
                        var tr = current_element.parents("tr");
                        program = makeUpDate($(this),program);
                        //如果没有输入内容提示lfc
                        var tishi=false;
                        /*
                        $("input[name='name']").each(function(){ 
                            if($(this).attr('value')==""){
                                tishi=true;
                            }
                        });  
                        */                      
                        var neirong=$(this).parent().parent().parent().find("input[name='name']").attr('value');
                        if(neirong==''){
                            alert('请输入节目名称');
                        }
                        else
                        {
                        	tr.find("input[type='text']").attr('disabled','true');
                            tr.find(".actions div ").prepend("<span>处理中...</span>");
                            tr.find(".actions div a:first").hide();
                            tr.find(".actions div a:eq(1)").hide();
                            tr.find(".actions div span:eq(1)").remove();    
                        }
                        //提交至服务端处理

                        
                        var this_end_time   = $(this).parent().parent().parent().find('td').eq(3).find("input[type='text']").val();
                        var next_start_time  = $(this).parent().parent().parent().next().find('td').eq(2).html();
                        if(typeof next_start_time!='undefined' && next_start_time!=null){//不是最后一行
                        	var next_start_time = next_start_time.replace(/[ ]/g,"").replace(/[\r\n]/g,"");
                        	if(this_end_time!=next_start_time){
                        		$(this).parent().parent().parent().next().addClass('red');
                            }else{
                            	$(this).parent().parent().parent().next().removeClass('red');
                            	$(this).parent().parent().parent().removeClass('red');
                            }
                        }
                        //时间约束**************start
                        /*var this_end_time   = $(this).parent().parent().parent().find('td').eq(3).find("input[type='text']").val();
                        var next_start_time  = $(this).parent().parent().parent().next().find('td').eq(2).find("input[type='text']").val();
                        if(typeof next_start_time=='undefined'){
                        	var next_start_time  = $(this).parent().parent().parent().next().find('td').eq(2).html();
                        }
                        if(typeof next_start_time!='undefined'){//不是最后一行
                        	var value = $(this).parent().parent().parent().next().find('td').eq(2).find("input[type='text']").val();
                        	if(typeof value == 'undefined'){
                        		$(this).parent().parent().parent().next().find('td').eq(2).html(this_end_time);
                            }else{
                            	$(this).parent().parent().parent().next().find('td').eq(2).find("input[type='text']").val(this_end_time);
                            }
                        }
                        var nextid = $(this).parent().parent().parent().next().find('td').eq(0).find("input[type='checkbox']").val();

                        $.post('<?php echo url_for('program/SaveStartTime') ?>',{
                            id:nextid,start_time:this_end_time
                            }, function(s){},'text')
                        */
                        //时间约束**************end

                        $.post('<?php echo url_for('program/Save') ?>',program, function(data,textStatus){
                        
                            if(textStatus == 'success')
                            {
                                data = eval(data);
                                
                                if(data.program_id)
                                {
                                    saveTR(current_element,true,is_new,data.program_id);
                                    
																		if(program.wiki!='')
																		{
																	  	//third_A.text(" 查看维基 | ").attr({'href':'/wiki/edit?id='+program.wiki_id,'target':'_blank'});
																			//tr.find(".actions div").prepend(third_A);
																		}
							 
                                   
                                }else{
                                    //console.log('ERROR:信息保存失败！服务端错误！');       
                                }
                                $("input[type=checkbox]").attr('checked',false);  //lfc
                            }
                        },'json');
			return false;
		});
		second_A.text(" 取消 ").attr({'href':'#'}).unbind('click').bind('click',function(){
			var tr = $(this).parents("tr");
			var backvalue = tr.find(".wiki").attr("backvalue");
			var wiki_id = tr.find(".wiki").attr("rel");
			wiki_ids = wiki_id;
			if(is_new){
				$(this).parents("TR").remove();
				return true;
			}
			saveTR($(this),false,false);
            if(backvalue!='')
            {
            	//third_A.text(" 查看维基 | ").attr({'href':'/wiki/edit?id='+wiki_id,'target':'_blank'}); 
            	//tr.find(".actions div").prepend(third_A);
			}           
			return false;
		});
	}
	
	if(action == 'edit'){
			var current_element = $(this);
			var tr = current_element.parents("td");
			program = makeUpDate($(this),program);
			var wiki_id = tr.find(".wiki").attr("rel");
			//bug修改 节目编辑后 下拉列表框无法正常显示 tianzhongsheng-ex@huan.tv 2013-08-28 17:11:00
			if(wiki_ids)
			{
				var a=' <select name="opt" id="opt"  onchange="programOpt($(this));">';
				 a +='<option value="">请选择</option>';
				 a +='<option value="/wiki/edit?id='+wiki_ids+'">查看维基</option>';
				 a +='<option value="deleteWiki">删除维基关联</option>';
				 a +='<option value="edit">编辑</option>';
				 a +='<option value="delete">删除</option>';
				 a +='<option value="addyesterday">添加到昨日回顾</option>';
				 a +='<option value="addnextweek">添加下周预告</option>';
				 a +='</select>';
			}else{
				var  a=' <select name="opt" id="opt"  onchange="programOpt($(this));">';
				a +='<option value="">请选择</option>';
				a +='<option value="edit">编辑</option>';
				a +='<option value="delete">删除</option>';
				a +='<option value="addyesterday">添加到昨日回顾</option>';
				a +='<option value="addnextweek">添加下周预告</option>';
				a +='</select>';
			}
			div.append(a);

	 
	 }else{
     	div.append(first_A).append('<span> | </span>').append(second_A);  //lfc
	 }
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
        var name = child_node.attr('name');//wangnan
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
        if(td.hasClass('select')){
            var img = selectToImg(td,value,img_src);
            insertElement(td,img,false);
            return true;
        }
        td.empty().text(value);
    });
    //window.location.reload(); //lfc
}

//将TD中元素的值记录到相应TD的REL中
function bakValue(off,td){
    if(!off){
        var value = ( td.find('INPUT,SELECT').val() || td.text() );
        value = value.replace(/[\s]+/ig,'');
        td.attr('backValue',value);
        return td;
    }
    return false;
}

//核心函数，主要负责创建和更改TR
function changeTR(element,new_tr_label,is_new,front){
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
                var channels_select = $("#program_channel_id");
                var text = channels_select.find("OPTION:selected").text();
                var textNode = document.createTextNode(text);
                td = insertElement(td,textNode,is_new,classes);
            }
            if(_element.hasClass('updatedat') && is_new){
                var textNode = document.createTextNode('');
                td = insertElement(td,textNode,is_new,classes);
            }            
            if(_element.hasClass('name') && is_new){
                var textNode = document.createTextNode('');
                td = insertElement(td,textNode,is_new,classes);
            }            
            if(_element.hasClass('createdat') && is_new){
                var textNode = document.createTextNode('');
                td = insertElement(td,textNode,is_new,classes);
            }            
            tr = insertTdToTr(tr,td,is_new);
            bakValue(is_new,td);                                    //备份原有数据
            return true;                                            //跳过本次循环
        }
        if(_element.hasClass('select')){                            //检查是否有IMG 标识的SELECT元素存在
            var select_options = _element.attr('rel');
            var select = textToSelect(name,select_options,is_new);
            td = insertElement(td,select,is_new,classes);
            td.attr("rel",select_options);
            tr = insertTdToTr(tr,td,is_new);
            bakValue(is_new,td);                                        //备份原有数据
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
            },function(date){
                var date = eval("("+date+")");
                var id = date.id;
                var tags = date.tags;
                td.parents("TR").find(".tags > INPUT").attr("value",tags);
                input.attr('rel',id);
            });
            input.attr('rel',td.attr('rel'));
        }

        td = insertElement(td,input,is_new,classes);
        tr = insertTdToTr(tr,td,is_new);
        bakValue(is_new,td);                                        //备份原有数据
    });
    insertTrToTable(tr,element,is_new,front);
}

function insertTrToTable(tr,table,is_new,front){  
	//TR插入TABLE事件，判断当前TR是否为新TR
	if(front)
    	return ((is_new) ? table.prepend(tr) : false);//向之前插入 add by wn
	else
    	return ((is_new) ? table.append(tr) : false);//向之后插入  add by wn
    
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
            ? input.DateTimeMask({masktype :'4', isnow :is_now,isnull:true})
            : (
                ( type == 'date' )
                ? input.DateTimeMask({is_null:true,isnow:false}).val(date)
                : input
            );
    return input;
}
//向TD中插入元素,并设置TD属性
function insertElement(td,element,is_new,classes){
    if(is_new){
        td.attr('class',classes);
        td.filter(".save").attr('align','center'); //lfc
        //td.attr('align','center');    //lfc  
    }
    
    td.empty().append(element);
    return td;
}
//将TD中的内容转换为SELECT下拉框
function textToSelect(name,value,is_new){
    value = eval("("+value+")");
    var options = value.options;
    var select = $("<select></select>").attr({'name':name});
    var option = null;
    var selected = null;
    for(i in options){
        selected = (is_new == true) ? false : options[i].selected  ;
        option = $("<option></option>").attr({'value':options[i].value,'selected':selected}).text(options[i].text);
        select.append(option);
    }
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
var new_tr_label = 'newProgram';
var img_src = ["<?php echo image_path('icon/delete.png') ?>","<?php echo image_path('accept.png') ?>"];

$(document).ready(function(){
       $("#admin_list > TBODY > TR > TD > A:eq(0)").bind('click',function(event){
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
       changeTR(table,new_tr_label,true,true);
   });
    $(".addNewEndProgram").live('click',function(){
        var table = $("#admin_list");
        changeTR(table,new_tr_label,true,false);
    });
   $(".toolbar .delete").bind('click',function(){
        if($("input[name='ids[]']:checked").length==0){
            alert('请选择要删除的节目!');
            return false;
        }    
        if(confirm('确定删除吗？')){
            var ids='';
            $("input[name='ids[]']:checked").each(function(){
                ids=ids+$(this).val()+','; 
            });
            program_deleteajax(ids);
            //program_delete();
        }    
   });

   $(".toolbar .app-save").bind('click',function(){
        save_onekey();
   });
   $(".toolbar .publish").click(function(){
       Publish(1);
   });

   $(".toolbar .unpublish").click(function(){
       Publish(0);
   });
});

//加载选中节目信息
function program_deleteajax(ids){
    $.ajax({
        url: '<?php echo url_for('program/deleteAjax')?>',
        type: 'post',
        dataType: 'text',
        data: {'ids': ids},
        success: function(data){
        	location.reload();	//在当前页面刷新 tianzhongsheng-ex@huan.tv 2013-08-29 10:57:00
        },
    });
   
}

//删除关联维基关联 tianzhongsheng-ex@huan.tv 2013-08-29 10:51:00
function program_deleteajaxwiki(id){
    $.ajax({
        url: '<?php echo url_for('program/DelWikiId')?>',
        type: 'post',
        dataType: 'text',
        data: {'id': id},
        success: function(data){
			location.reload();
        },
    });
   
}
function program_deleteone(element,method)
{
	if(method == 1)
	{
		 element.parents('TR').find("TD:eq(0) > INPUT[type=checkbox]").attr("checked",true);
	}
	var ids=element.parents('TR').find("TD:eq(0) > INPUT[type=checkbox]").val();
	program_deleteajax(ids);
}

//这个方法可以弃用 如果使用请把注释去掉 tianzhongsheng-ex@huan.tv 2013-08-29 10:58:00
//function program_delete(element,method){
//        if(method == 1)
//        {
//            element.parents('TR').find("TD:eq(0) > INPUT[type=checkbox]").attr("checked",true);
//        }
//        admin_form = document.getElementById("adminForm");
//        admin_form.action = "<?php echo url_for('program/delete') ?>";
//        admin_form.submit(); 
//}

//删除维基关联 tianzhongsheng-ex@huan.tv 2013-07-18 10:51:00
//第一次编辑后无法删除  tianzhongsheng-ex@huan.tv 2013-08-29 10:38:00
function delete_wiki(element,method)
{
	if(method == 1)
    {
        element.parents('TR').find("TD:eq(0) > INPUT[type=checkbox]").attr("checked",true);
    }
    var id=element.parents('TR').find("TD:eq(0) > INPUT[type=checkbox]").val();
    program_deleteajaxwiki(id);
}
function save_onekey(){
        var tishi=false;
        $("input[name='name']").each(function(){ 
            //alert($(this).attr('value'));
            if($(this).attr('value')==""){
                tishi=true;
            }
        });
        if(tishi){
            alert('请输入节目名称');
        }else{
            $("#admin_list > TBODY > TR[edit='1']").find("A:eq(0)").each(function(){ 
                //alert($(this).attr('href'));
                $(this).trigger("click");
            }); 
        }
        return false;
}       
function Publish(publish)
{
    $("#publish_off").val(publish);
    admin_form = document.getElementById('adminForm');
    admin_form.action = "<?php echo url_for('program/publish')?>";
    admin_form.submit();
}

</script>
<script>
$(function(){
//	$('#theadlist').find('tr').size();
	var len = $('#theadlist').find('tr').size();
	//alert($('#theadlist').find('tr').eq(0).find('td').eq(3).html().replace(/[ ]/g,"").replace(/[\r\n]/g,""));
	//alert($('#theadlist').find('tr').eq(1).find('td').eq(2).html().replace(/[ ]/g,"").replace(/[\r\n]/g,""));
	for(var i=0;i<len;i++){
		if((i+1)<len){
			var current = $('#theadlist').find('tr').eq(i).find('td').eq(3).html().replace(/[ ]/g,"").replace(/[\r\n]/g,"");
			var next    = $('#theadlist').find('tr').eq(i+1).find('td').eq(2).html().replace(/[ ]/g,"").replace(/[\r\n]/g,"");
			if((typeof next != 'undefined') && (current != next)){
				$('#theadlist').find('tr').eq(i+1).addClass('red');
		    }
		}
	}
})
</script>
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
.sf_admin_list_th_actions .noEdit .actions .edit .save DIV,.sf_admin_list_th_publish,.sf_admin_date,.sf_admin_list_th_time,.time,.sf_admin_list_th_channel,.channel{
    text-align:center;
}
</style>

<form action="#" id="adminForm" name="adminForm" method="post" >
<div class="clear"></div>
</div>
<table cellspacing="0" class="adminlist" id="admin_list">
  <thead>
    <tr>
      <th scope="col" class="noEdit checkbox"><input id="sf_admin_list_batch_checkbox" name="toggle" onclick="checkAll(1);" type="checkbox" name="ids"></th>
      <th scope="col" class="list_id" name="name">名称</th>
<!--      <th scope="col" class="title sf_admin_text sf_admin_list_th_channel noEdit channel" name="channel_id">频道</th>-->
      <th scope="col" class="time" name="time" style="width: 8%;">播放时间</th>
      <th scope="col" class="endtime" name="endtime" style="text-align: center;">结束时间</th>
      <th scope="col" class="title sf_admin_boolean sf_admin_list_th_publish select img"
                name="publish" rel='{ "options":[ {text:"是",value:1,selected:false},{text:"否",value:0,selected:"selected"} ] }'
                style="width:5%;" name="publish">发布</th>
      <th scope="col" class="wiki" name="wiki">维基</th>
      <th scope="col" class="tags" name="tags" style="width: 20%">Tags</th>
      <th scope="col" class="date" name="date" style="text-align: center;display:none">播放日期</th>
      <th scope="col" class="createdat" name="createdat" style="text-align: center;">获取时间</th>
      <th scope="col" class="sort" name="sort" style="text-align: center;" style="width:5%;">排序</th>
      <th scope="col" class="updatedat sf_admin_text sf_admin_list_th_channel noEdit" name="updatedat" style="text-align: center;">修改时间</th>
      <th scope="col" style="width:11%;text-align: center;" class="noEdit actions edit">操作</th>
    </tr>
  </thead>
  <tfoot>
    <tr>
     <th scope="col" class="list_checkbox"><input type="checkbox"  id="sf_admin_list_batch_checkbox_foot" onclick="checkAll(2);"></th>
      <th scope="col">名称</th>
      <th scope="col" style="text-align: center;">播放时间</th>
      <th scope="col" style="text-align: center;">结束时间</th>
      <th scope="col" style="text-align: center;">发布</th>
      <th scope="col">维基</th>
      <th scope="col">Tags</th>
      <th scope="col" style="text-align: center;display:none">播放日期</th>
      <th scope="col" style="text-align: center;">获取时间</th>
      <th scope="col" style="text-align: center;">排序</th>
      <th scope="col" style="text-align: center;">修改时间</th>
      <th scope="col" style="text-align: center;">操作</th>
    </tr>
  </tfoot>
  <tbody id='theadlist'>
  <?php
    if($programs):
    $i=0;
    foreach($programs as $program):
    $i++;
  ?>
    <tr edit="0">
        <td class="noEdit checkbox" name="ids[]">
          <input name="ids[]" value="<?php echo $program->getId() ?>" class="sf_admin_batch_checkbox " type="checkbox">
        </td>
        <td class="sf_admin_text sf_admin_list_td_name" name="name" style="text-align:left;" >
          <?php echo $program->getName() ?>
        </td>
        <td class="sf_admin_date sf_admin_list_td_time" name="time">
          <?php echo $program->getTime() ?>
        </td>
        <td class="" name="end_time" >
          <?php $endtime = $program->getEndTime(); if($endtime) echo $program->getEndTime()->format("H:i")?>
        </td>
          <?php
            if($program->getPublish() == 1){
                $publish_on = '"selected"';
                $publish_off = 'false';
            }else{
                $publish_on = 'false';
                $publish_off = '"selected"';
            }
        ?>
        <td class="sf_admin_boolean sf_admin_list_td_publish img select" name="publish"
            rel='{ "options":[ {text:"是",value:1,selected: <?php echo $publish_on; ?> },{text:"否",value:0,selected:<?php echo $publish_off; ?> } ] }'
            style="text-align:center;">
            <img alt="Checked" title="Checked" src="<?php echo $program->getPublishImgSrc(); ?>">
        </td>
        <td class="sf_admin_text sf_admin_list_td_wiki wiki" name="wiki" rel="<?php echo ($program->getWiki()) ? $program->getWiki()->getId() : ''; ?>">
            <?php echo $program->getWikiTitle() ?>
        </td>
        <td class="sf_admin_text sf_admin_list_td_tags tags" name="tags">
        <?php if($program->getTags()):?>
            <?php foreach($program->getTags() as $tag): ?>
                <?php echo $tag; ?>,
            <?php endforeach; ?>
        <?php endif;?>
        </td>
        <td class="sf_admin_date sf_admin_list_td_date date" name="date" style="display:none">
           <?php echo $program->getDate() ?>
        </td>
        <td class="sf_admin_createdat sf_admin_list_td_createdat noEdit createdat" name="createdat" >
           <?php $created_at = $program->getCreatedAt(); if($created_at) echo $program->getCreatedAt()->format("Y-m-d H:i:s") ?>
        </td>
        <td class="sf_admin_text sf_admin_list_td_sort sort" name="sort" style="text-align: center;">
           <?php echo $program->getSort() ?>
        </td>
        <td class="sf_admin_text sf_admin_list_td_channel noEdit channel"  style="text-align:left;" >
          <?php $update_at = $program->getUpdatedAt(); if($update_at) echo $program->getUpdatedAt()->format("Y-m-d H:i:s") ?>
        </td>       
        <td class="sf_admin_text sf_admin_list_td_actions noEdit actions edit" rel="<?php echo $program->getId();?>" style="text-align: center;">
            <select name="opt" id="opt['<?php echo $program->getId()?>']" onchange="programOpt($(this));">
           	<option value="">请选择</option>
            <?php if($program->getWikiId()):?>
            <option value="<?php echo url_for("wiki/edit?id=".$program->getWikiId())?>">查看维基</option>
            <option value="deleteWiki">删除维基关联</option>
            <?php endif;?>
            <option value="edit">编辑</option>
            <option value="delete">删除</option> 
            <option value="addyesterday">添加到昨日回顾</option>
            <option value="addnextweek">添加下周预告</option>
            </select>
        </td>
        <td  style="display:none" tv_code="<?php echo ($program->getChannelCode()) ? $program->getChannelCode() : ''; ?>"></td>
    </tr>
  <?php endforeach; ?>
<?php endif ?>
  </tbody>
</table>
<div class="clear"></div>
<input type="hidden" name="publish" value="0" id="publish_off" />
</form>
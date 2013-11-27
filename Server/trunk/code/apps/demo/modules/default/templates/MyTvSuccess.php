<script type="text/javascript">
$(document).ready(function(aa) {
   var page=parseInt(1);
   var date='';
   var channel_code='';
   date=  getQueryString("date");
   channel_code=  getQueryString("channel_code");
   if(channel_code=="null"){
      channel_code='';
    }


$('.mvcover').click(function(){
                   var  wikiid =  $(this).find("#wikiid").val();
			  $.ajax({
			        url: "/default/AjaxShow?wiki_id="+wikiid,
			        dataType: "json",
			        success:function(data)
			        {
                         $(".cover").show();
                         $(".more").show();
                         $(".cover").attr("src",data.cover);
                         $('.title').html(data.title);
                         $('.content').html(data.content);
                         $('.host span').html(data.host);
                         $('.content').html(data.content);
                         $(".show_wiki").attr("href",'/default/show?wiki_id='+wikiid);
			        }
			    });
              });
 
$('.ctrl').click(function(){
  
/**
 if(confirm("是否取消预约?")){
   return true;
 }else{
     return false;
 }
 */

 show("是否取消预约?");

 });



function keydown(e)
{    
　 var e=e||event;
　 var currKey=e.keyCode||e.which||e.charCode;
　 if((currKey>7&&currKey<14)||(currKey>31&&currKey<47))
　 {
　 　   switch(currKey)
   　 　 {
    　 　 　 case 8: keyName = "[退格]"; break;
    　 　 　 case 9: keyName = "[制表]"; break;
    　 　 　 case 13:keyName = "[回车]"; break;
    　 　 　 case 32:keyName = "[空格]"; break;
    　 　 　 case 33:
                   page=parseInt(parseInt(page)-1);
                   if(date=="null"){date='';}
                   if(channel_code=="null"){channel_code='';}
                   if(page<1){page=1;}
                   upPage(page,date,channel_code);
                   break;
    　 　 　 case 34:
                
                   var isAdd=$('#isadd').val();
                   if(isAdd==1){page=parseInt(parseInt(page)+1)};
                   if(date=="null"){date='';}
                   if(channel_code=="null"){channel_code='';}
                   if(page<1){page=1;}
                   downPage(page,date,channel_code);
                   break;
    　 　 　 case 35:keyName = "[End]";   break;
    　 　 　 case 36:keyName = "[Home]";   break;
    　 　 　 case 37:keyName = "[方向键左]";   break;
    　 　 　 case 38:keyName = "[方向键上]";   break;
    　 　 　 case 39:keyName = "[方向键右]";   break;
   　 　 　  case 40:keyName = "[方向键下]";   break;
    　 　 　 case 46:keyName = "[删除]";   break;
    　 　 　 default:keyName = "";    break;
　 　   }
 
　 }
}
 
document.onkeydown =keydown;

})
//重新绑定点击事件
function ajax(){

$('.mvcover').click(function(){
               var  wikiid =  $(this).find("#wikiid").val();
			  $.ajax({
			        url: "/default/AjaxShow?wiki_id="+wikiid,
			        dataType: "json",
			        success:function(data)
			        {
                         $(".cover").show();
                         $(".more").show();
                         $(".cover").attr("src",data.cover);
                         $('.title').html(data.title);
                         $('.content').html(data.content);
                         $('.host span').html(data.host);
                         $('.content').html(data.content);
                         $(".show_wiki").attr("href",'/default/show?wiki_id='+wikiid);
			        }
			    });
              });
}
 
function upPage(page,date,channel_code){
            if(page<1){
                page=1;
            }
			  $.ajax({
			        url: "/default/AjaxProgramList?page="+page+"&date="+date+"&channel_code="+channel_code,
			        success:function(data)
			        {
                      
                        $('.epg_list').html(data);
                        ajax();
                         if(page==1){
                            show("已经是第一页了，不能往上翻了！！！！！");
                            $('#isadd').val('1');
                            return false;
                          } 
                   
			        }
			    });
      
 
           
}
 
function downPage(page,date,channel_code){
			  $.ajax({
			        url: "/default/AjaxProgramList?page="+page+"&date="+date+"&channel_code="+channel_code,
			        success:function(data)
			        {
                     
                          if(data){
                          $('.epg_list').html(data);
                          }else{
                             show("已经是最后一页了，不能往下翻了！！！！！");
                             $('#isadd').val('0');
                          }
                            ajax();
			        }
			    });
}
 
function getQueryString(name) {
var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
var r = window.location.search.substr(1).match(reg);
if (r != null) return unescape(r[2]); return null;
}
</script> 
<input type="hidden" name="" id="isadd" value="1">
<div class="play_now clr">
             <?php include_partial("mytv") ?>
            
            <div class="list_bg">
                <ul class="epg_list">

                 <?php foreach ($program_now as $program):?>
                     <li class="mvcover">
                      <a href='javascript:void(0)' id="program" >
                       <span class="time"><?php  echo $program['start_time'];?></span>
                               <?php   $wikiid=$program['wikiid'];?>
                            <input type="hidden" name="" id="wikiid" value="<?php echo $wikiid;?>"> 
                            <!-- <span class="name"><a href="<?php echo url_for('/default/show').'?wiki_id='.$wikiid ;?>" target="_blank"> <?php echo $program->name;?></a></span> -->
                              <span class="name"><?php echo $program['name'];?></span>
                            <span class="ctrl">取消</span>
                     </a>
                    </li>
                <?php endforeach;?>

 
                </ul>
                
                <div class="tv_info">
                    <img src="" alt="" class="cover" style="display:none;"/>
                    <ul>
                        <li class="title"><h2></h2></li>
                        <li class="host"><span></span></li>
                        <li class="content"></li>
                        <li class="more" style="display:none;"><a href="#" class="show_wiki">查看详情</a></li>
                    </ul>
                </div>
            
            </div> 
            
        </div>
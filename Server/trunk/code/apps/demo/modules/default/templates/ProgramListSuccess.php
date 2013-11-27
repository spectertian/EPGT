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
                        $('.page_current').html(page);
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
                          $('.page_current').html(page);
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
        	<ul class="item_list">


                      <?php
            $tmps = array();
            $times = 24 * 60 * 60; 
            //$channel_id = $sf_user->getAttribute('channel_id'); //debug
            $channel_id=1;
           // $current_date = $sf_request->getParameter('date',( $sf_user->getAttribute('date') ? $sf_user->getAttribute('date') :date("Y-m-d",time()))) ;
           $current_date =  date("Y-m-d",time());
            $w = date('w', strtotime($current_date));
               
            if ($w == 0) $w = 7;
            $s = strtotime($current_date);
            $weeks = array('上一周', '(周一)', '(周二)', '(周三)', '(周四)', '(周五)', '(周六)', '(周日)', '下一周');
        
            for ($i = 1; $i < 8; ++$i) {
                if ($i == 0) {
                    $n = $s - (6 + $w) * $times;
                } else if ($i == $w) {
                    $n = $s;
                } else {
                    $n = $s + ($i - $w) * $times;
                }
               $tmps[$i] = date('Y-m-d', $n);
             /**
                echo sprintf('<li><a href="%s" class="%s" link=\'{"channel_id":%s,"date":"%s"}\'>%s</a></li>',
                        url_for('/@program').'?channel_id=' . $channel_id . '&date=' . $tmps[$i],  $current_date == $tmps[$i] ? 'active' : '', $channel_id, $tmps[$i], (($i != 0 && $i != 8 ) ? $tmps[$i] : '') . $weeks[$i]);
             */
             $channel_code='cctv1';
              if($_GET['channel_code']){
                  $channel_code=$_GET['channel_code'];
              }
              if($tmps[$i]==$_GET['date']){
                  if($tmps[$i]==date('Y-m-d',time())){
                      echo '<li class="this"><a href="'.url_for('/default/ProgramList').'?date='.$tmps[$i] .'&channel_code='.$channel_code.'">今天</a></li>';
                  }else{
                      echo '<li class="this"><a href="'.url_for('/default/ProgramList').'?date='.$tmps[$i] .'&channel_code='.$channel_code.'">'.$tmps[$i].'</a></li>';
                  }
              }else{
               
                 if($tmps[$i]==date('Y-m-d',time())){
                     if($_GET['date']==""){
                    echo '<li  class="this"><a href="'.url_for('/default/ProgramList').'?date='.$tmps[$i] .'&channel_code='.$channel_code.'">今天</a></li>';
                     }else{
                    echo '<li><a href="'.url_for('/default/ProgramList').'?date='.$tmps[$i] .'&channel_code='.$channel_code.'">今天</a></li>';
                     }
                   }else{
                    echo '<li><a href="'.url_for('/default/ProgramList').'?date='.$tmps[$i] .'&channel_code='.$channel_code.'">'.$tmps[$i].'</a></li>';                             
                    }
              }
 

            }
                 
      ?>



            	<!-- <li class="this"><a href="#">周日</a></li>
                <li><a href="#">周一</a></li>
                <li><a href="#">周二</a></li>
                <li><a href="#">周三</a></li>
                <li><a href="#">周四</a></li>
                <li><a href="#">周五</a></li>
                <li><a href="#">周六</a></li> -->
            </ul>
            
            <div class="list_bg">
                <ul class="epg_list">

                <?php $time_now=strtotime(date("Y-m-d H:i:s",time()));?>

                 <?php foreach ($programLists as $program):?>
                     <li class="mvcover">
                      <a href='javascript:void(0)' id="program" >
                      
                            <span class="time"><?php echo $program->time;?></span>
                            <?php  $wikiid= $program->getWikiId(); ?>
                            <input type="hidden" name="" id="wikiid" value="<?php echo $wikiid;?>"> 
                            <!-- <span class="name"><a href="<?php echo url_for('/default/show').'?wiki_id='.$wikiid ;?>" target="_blank"> <?php echo $program->name;?></a></span> -->
                              <span class="name"><?php echo $program->name;?></span>


                            <!-- <span class="ctrl">正在播放中...</span> -->


                            <!-- <span class="ctrl">回看</span> -->
                           <?php
                           if($program->getEndTime()){
                         $EndTime=strtotime(date("Y-m-d H:i:s",$program->getEndTime()->getTimestamp()));
                         $StartTime=strtotime(date("Y-m-d H:i:s",$program->getStartTime()->getTimestamp()));
                           }
                           ?>
                        <?php if($time_now>= $StartTime && $time_now <$EndTime){?>
                             <span class="ctrl">正在播放中...</span>  
                        <?php }else{?>
                             <span class="ctrl">回看</span>
                        <?php }?>

                           

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
   
        <div class="page">
        	当前第<span class="page_current"><?php echo $programLists->getPage();?></span>页 / 共<span><?php echo $programLists->getLastPage();?></span>页
        </div>  
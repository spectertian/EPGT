<script type="text/javascript">
$(document).ready(function() {
 $('.tip').powerTip({ placement: 'n' });
var page=parseInt(1);
var tag;
tag=  getQueryString("Channel_type");
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
                   var  pages="<?php echo $ChannelList->getLastPage(); ?>";
                   if(page>pages){
                       page=pages;
                    }
                  if(page<=1){page=1;}
                   upPage(page,tag);
                   break;
    　 　 　 case 34:
                   var isAdd=$('#isadd').val();
                   if(isAdd==1){page=parseInt(parseInt(page)+1)};
                       var  pages="<?php echo $ChannelList->getLastPage(); ?>";
                  if(page>pages){
                       page=pages;
                    }
                   if(page<=1){page=1;}
                   downPage(page,tag);
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
 $('.tip').powerTip({ placement: 'n' });
}
function upPage(page,tag){
			  $.ajax({
			        url: "/default/AjaxChannelList?page="+page+"&Channel_type="+tag,
			        success:function(data)
			        {
                        $('.nowplay').html(data);
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
 
function downPage(page,tag){

			  $.ajax({
			        url: "/default/AjaxChannelList?page="+page+"&Channel_type="+tag,
			        success:function(data)
			        {
                          if(data){
                          $('.nowplay').html(data);
                          $('.page_current').html(page);
                            var  pages="<?php echo $ChannelList->getLastPage();?>";
                           if(page==pages){
                              show("已经是最后一页了，不能往下翻了！！！！！");
                            }
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

            	<li <?php if($_GET['Channel_type'] == 'cctv' || $_GET['Channel_type'] == ''):?> class="this" <?php endif;?> ><a href="<?php echo url_for('/default/ChannelList').'?Channel_type=cctv';?>">中央频道</a></li>
                <li <?php if($_GET['Channel_type'] == 'tv'):?> class="this" <?php endif;?> ><a href="<?php  echo url_for('/default/ChannelList').'?Channel_type=tv';?>">卫视频道</a></li>
                <li <?php if($_GET['Channel_type'] == 'local'):?> class="this" <?php endif;?>><a href="<?php  echo url_for('/default/ChannelList').'?Channel_type=local';?>">本地频道</a></li>
                <li <?php if($_GET['Channel_type'] == 'hd'):?> class="this" <?php endif;?>><a href="<?php  echo url_for('/default/ChannelList').'?Channel_type=hd';?>">高清频道</a></li>
                <li <?php if($_GET['Channel_type'] == 'pay'):?> class="this" <?php endif;?> ><a href="<?php  echo url_for('/default/ChannelList').'?Channel_type=pay';?>">付费频道</a></li>
                <li <?php if($_GET['Channel_type'] == 'all'):?> class="this" <?php endif;?> ><a href="<?php  echo url_for('/default/ChannelList').'?Channel_type=all';?>">全部频道</a></li>
            </ul>
            
            <div class="list_bg">
            	<!-- <ul class="text_list clr nowplay"  >
         

                </ul> -->
                

 <ul class="icolist clr nowplay">



                  <?php foreach ($program as $programs):?>
                       <!-- <li class="tip"  title="<?php echo "当前播放：".$programs['name']."<br/>即将播放：".$programs['next_name']; ?>"><a href="<?php  echo url_for('/default/ProgramList').'?channel_code='.$programs['channel_code'];?>"><span><?php echo $programs['logic_number'];?></span></a></li> -->
                 

                        <?php $channel_logo= $programs['channel_logo']; // thumb_url($cover, 216, 320);
                        $img= sprintf(sfConfig::get('app_static_url').'thumb/'.'%s/%s/%s',  108, 160, $channel_logo);
                        ?>
                    <li class="tip"  title="<?php echo "当前播放：".$programs['name']."<br/>即将播放：".$programs['next_name']; ?>"><a href="<?php  echo url_for('/default/ProgramList').'?channel_code='.$programs['channel_code'];?>"><img src="<?php echo  $img;?>" alt=""/><?php echo $programs['channelname'];?></a></li>
                
                 <?php endforeach;?>
               </ul>
                   


                 <!-- <ul class="logo_list clr">

                </ul> -->  

            </div> 
            
        </div>


       <div class="page">                               
         	当前第<span class="page_current"><?php echo $ChannelList->getPage();?></span>页 / 共<span><?php echo $ChannelList->getLastPage();?></span>页
        </div>  
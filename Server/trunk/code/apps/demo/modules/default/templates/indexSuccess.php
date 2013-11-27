<script type="text/javascript">
$(document).ready(function() {
$('.tip').powerTip({ placement: 'nw-alt' });
var page=parseInt(1);
var tag;
tag=  getQueryString("tag");
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
                  var  pages="<?php echo $pager_wiki->getLastPage(); ?>";
                  if(page>=pages){
                       page=pages;
                  }
                   page=parseInt(parseInt(page)-1);
                  if(page<=1){page=1;}
                   upPage(page,tag);
                   break;
    　 　 　 case 34:
                   var isAdd=$('#isadd').val();
                   if(isAdd==1){page=parseInt(parseInt(page)+1)};
                   var  pages="<?php echo $pager_wiki->getLastPage(); ?>";
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
$('.tip').powerTip({ placement: 'nw-alt' });
}
function upPage(page,tag){
			  $.ajax({
			        url: "/default/AjaxNowPlaying?page="+page+"&tag="+tag,
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
			        url: "/default/AjaxNowPlaying?page="+page+"&tag="+tag,
			        success:function(data)
			        {
                          if(data){
                          $('.nowplay').html(data);
                          $('.page_current').html(page);
                           var  pages="<?php echo $pager_wiki->getLastPage();?>";
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

            	<li <?php if($_GET['tag'] == 'all' || $_GET['tag'] == ''):?> class="this" <?php endif;?> ><a href="<?php echo url_for('/default/index').'?tag=all';?>">全部</a></li>
                <li <?php if($_GET['tag'] == 'teleplay'):?> class="this" <?php endif;?> ><a href="<?php  echo url_for('/default/index').'?tag=teleplay';?>">电视剧</a></li>
                <li <?php if($_GET['tag'] == 'film'):?> class="this" <?php endif;?>><a href="<?php  echo url_for('/default/index').'?tag=film';?>">电影</a></li>
                <li <?php if($_GET['tag'] == 'sports'):?> class="this" <?php endif;?>><a href="<?php  echo url_for('/default/index').'?tag=sports';?>">体育</a></li>
                <li <?php if($_GET['tag'] == 'entertainment'):?> class="this" <?php endif;?> ><a href="<?php  echo url_for('/default/index').'?tag=entertainment';?>">娱乐</a></li>
                <li <?php if($_GET['tag'] == 'children'):?> class="this" <?php endif;?> ><a href="<?php  echo url_for('/default/index').'?tag=children';?>">少儿</a></li>
                <li <?php if($_GET['tag'] == 'science'):?> class="this" <?php endif;?> ><a href="<?php  echo url_for('/default/index').'?tag=science';?>">科教</a></li>
                <li <?php if($_GET['tag'] == 'finance'):?> class="this" <?php endif;?> ><a href="<?php  echo url_for('/default/index').'?tag=finance';?>">财经</a></li>
                <li <?php if($_GET['tag'] == 'comprehensive'):?> class="this" <?php endif;?> ><a href="<?php  echo url_for('/default/index').'?tag=comprehensive';?>">综合</a></li>
            </ul>
            
            <ol class="nowplay">
 
 
          <?php foreach ($nowplaying as $program):?>
 
           <?php
          $img= sprintf(sfConfig::get('app_static_url').'thumb/'.'%s/%s/%s',  216, 320, $program['cover']); 
          $id=$program['wikiid'];
          $next_time=  (strtotime($program['end_time'])- strtotime(date('H:i:s',time())));
          $next_time= date('i',$next_time)
           ?>

            <li><a href="<?php   echo url_for('/default/show').'?wiki_id='.$id;?>"><img src="<?php  echo $img;?>"  class="tip" title="<?php echo "节目名称：".$program['name']."<br/>频道名称：".$program['channel']."<br/>播放时间：开始".$program['start_time']." <----> 结束".$program['end_time']."<br/>即将播出：".$program['next_name']." ------剩余时间".$next_time."分钟"; ?>" /><?php echo  $program['name'];?></a></li>
          <?php endforeach;?>


                



            </ol>
        </div>

        <div class="page">
        	当前第<span class="page_current"><?php echo $pager_wiki->getPage();?></span>页 / 共<span><?php echo $pager_wiki->getLastPage();?></span>页
        </div>  
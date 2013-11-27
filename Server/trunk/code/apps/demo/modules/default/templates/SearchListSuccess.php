<script type="text/javascript">
$(document).ready(function() {
$('.tp').powerTip({ placement: 'nw-alt' });
var page=parseInt(1);
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
                   var tag =encodeURI($('#program_name').val());
                   upPage(page,tag);
           
                   break;
    　 　 　 case 34:
  
                   var isAdd=$('#isadd').val();
                   if(isAdd==1){page=parseInt(parseInt(page)+1)};
                   var tag =encodeURI($('#program_name').val());
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
            if(page<1){
                page=1;
            }
			  $.ajax({
			        url: "/default/AjaxSearchList?page="+page+"&program_name="+tag,
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
			        url: "/default/AjaxSearchList?page="+page+"&program_name="+tag,
			        success:function(data)
			        {
                     
                          if(data){
                          $('.nowplay').html(data);
                          $('.page_current').html(page);
                          }else{
                             show("已经是最后一页了，不能往下翻了！！！！！");
                             $('#isadd').val('0');
                          }
                          ajax();
			        }
			    });
}
 
</script>
<?php use_helper('Text')?> 
<input type="hidden" name="" id="isadd" value="1">
 <div class="search">
        	<form name="" method="get" class="" id="">
            	<input type="text" name="program_name" value="<?php echo $_GET['program_name'];?>" id="program_name"/>
                <input type="submit" value="搜索"/>
 
            </form>
            <div class="tj">
                <h2>搜索结果</h2>
                <ul class="tjlist clr nowplay" >

                 <?php foreach ($searchlist as $programs):?>
                 
                    <?php  $img= sprintf(sfConfig::get('app_static_url').'thumb/'.'%s/%s/%s',  216, 320, $programs['cover']);?>


                    	<li><a href="<?php   echo url_for('/default/show').'?wiki_id='.$programs['wiki_id'];?>"><img src="<?php echo $img;?>" alt="" class="tp" title="<?php echo "频道名称：".$programs['channel_name']."<br/>播放出时间：".$programs['start_time']; ?>" /><?php echo $programs['name'];?></a></li>
                        


                   <?php endforeach;?>
                </ul>
            </div>
            
   
        </div>

 

        <!-- <div class="page">                               
         	当前第<span class="page_current"><?php echo $search->getPage();?></span>页 / 共<span><?php echo $search->getLastPage();?></span>页
        </div>   -->


 

 
        
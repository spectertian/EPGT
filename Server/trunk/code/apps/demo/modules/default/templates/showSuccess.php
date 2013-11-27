<script type="text/javascript">
$(document).ready(function() {

$('.tab .feng').click(function(){

    $('.fj').show();
    $('.piclist').hide();
    $('.yylist2').hide();
    $('.yylist1').hide();


   $('.tab .ju').removeClass("this");
   $('.tab .dian').removeClass("this");
   $('.tab .yu').removeClass("this");
   $(this).addClass("this");

});


 $('.tab .ju').click(function(){

    $('.piclist').show();
    $('.fj').hide();
    $('.yylist2').hide();
    $('.yylist1').hide();
    $('.tab .feng').removeClass("this");
    $('.tab .dian').removeClass("this");
    $('.tab .yu').removeClass("this");
    $(this).addClass("this");

});


 $('.tab .dian').click(function(){

    $('.yylist1').show();
    $('.fj').hide();
    $('.piclist').hide();
    $('.yylist2').hide();
    $('.tab .ju').removeClass("this");
    $('.tab .feng').removeClass("this");
   $('.tab .yu').removeClass("this");
    $(this).addClass("this");

});


 $('.tab .yu').click(function(){

    $('.yylist2').show();
    $('.fj').hide();
    $('.piclist').hide();
    $('.yylist1').hide();
    $('.tab .feng').removeClass("this");
    $('.tab .ju').removeClass("this");
    $('.tab .dian').removeClass("this");
    $(this).addClass("this");

});

/**
 $('.tab .ju').click(function(){

    $('.piclist').show();
    $('.fj').hide();

   $(".tab").each(function(){$(this).removeClass("this")});
   $(this).addClass("this");

});
*/

$('.addcollect').click(function(){
    var wiki_id='<?php echo  $_GET["wiki_id"];?>';
    $.ajax({
        url: "/default/AddCollect?wiki_id="+wiki_id,
        success:function(data)
        {
             if(data==1){
                show("添加收藏成功");
                return false;
              }else{
                show("添加收藏失败");
              }
        }
    });
});


$('.deletecollect').click(function(){
    var wiki_id='<?php echo $_GET["wiki_id"];?>';
    $.ajax({
        url: "/default/DeletCollect?wiki_id="+wiki_id,
        success:function(data)
        {
             if(data==1){
                show("取消收藏成功");
                return false;
              }else{
                show("取消收藏失败");
              }
        }
    });
});



});
</script> 
 <div class="movie clr">
            <div class="movie_info">
          

                <?php $cover= $wiki->getCover(); // thumb_url($cover, 216, 320);
                $img= sprintf(sfConfig::get('app_static_url').'thumb/'.'%s/%s/%s',  216, 320, $cover);
                
 
          
                ?>
                      <img src="<?php echo $img;?>" alt="" class="cover"/>
                <ul class="mil">
                    <li><h2><?php echo $wiki->title;?>
                    <?php if($collect){?>
                      <a href="#" class=" deletecollect">取消收藏</a>
                    <?php }else{ ?>
                      <a href="#" class="addcollect">添加收藏</a>
                    <?php }?>
                    
                    </h2></li>
                    <li>主演：<?php 
                       if($wiki->getStarring()){
                           foreach($wiki->getStarring() as  $screen) {
                             echo  $screen .',';
                           }
                       }
                    ?></li>
                    <li>上映日期：
                    <?php
                        if($wiki->getDodate()){
                         echo $wiki->getDodate()->format("Y-m-d H:i");
                        }
                     ?>
                     
                     </li>
                    <li>类型：<?php 
                       if($wiki->getStarring()){
                           foreach($wiki->getTags() as  $screen) {
                             echo  $screen .',';
                           }
                       }
                    ?></li>
                    <li>剧情介绍： <?php 
                     if($wiki->content){
                      echo $wiki->content;
                     }
                    
                    ?></li>
                    <li class="more"><a href="#">查看详情</a></li>
                </ul>
                
                <div class="tab">
                    <h2><a href="javascript:void(0)" class="ju this">剧照</a>         

                    <?php if(!empty($metas)){?>
                    <a href="javascript:void(0)" class="feng">分集</a>
                    <?php }?>
                    
                    <a href="javascript:void(0)" class="dian">点播</a><a href="javascript:void(0)" class="yu">预约</a></h2>
                    
                    <div class="hide">
                    	<ul class="piclist clr">
                        	 
                        <?php foreach ($wiki->getscreenshots() as $program):?>

                         <?php 
                        
                         
                           $img= sprintf(sfConfig::get('app_static_url').'thumb/'.'%s/%s/%s',  216, 320, $program); ?>
    
                           <li>
                            	<a href="#"><img src="<?php echo $img;?>"/></a>
                            </li>
                        <?php endforeach;?>
                        </ul>
                        <ul class="fj" style="display:none;">
                       <?php foreach ($metas as $key=>$metass):?>
                          <li><a href="#"><?php echo $key+1;?></a></li>
                       <?php endforeach;?>
                        </ul>
                        
                        <ul class="yylist yylist1" style="display:none;">
                        	<li><a href="#"><span>09.21</span><sapn>21:33</span><span>辽宁卫视</span><span class="ctrl">取消</span></a></li>
 
                        </ul>

                        <ul class="yylist yylist2" style="display:none;">
                        	<li><a href="#"><span>09.21</span><sapn>21:33</span><span>北京卫视</span><span class="ctrl">取消</span></a></li>
                         
                        </ul>

                    </div>
                </div>
            </div>
            
            <div class="movie_list">
            	<div class="sty">
                	<h2>频道回看</h2>
                    <ul>

                        <?php foreach ($nextweekprogram as $nextweek):?>


                    	<li><a href="<?php echo url_for('/default/show').'?wiki_id='.$nextweek->getWikiId();?>"><?php echo $nextweek->getProgramName();?><span>东方卫视</span></a></li>

                         <?php endforeach;?>
                    </ul>
                </div>
                
                <div class="sty">
                	<h2>播出预告</h2>
                    <ul>
                <?php foreach ($yesterdayprogram as $yester):?>
                    
                    	<li><a href="<?php echo url_for('/default/show').'?wiki_id='.$yester->getWikiId();?>"><span>14:00</span><? echo $yester->getProgramName();?></a></li>
                 <?php endforeach;?>
                    </ul>
                </div>
            </div> 
        </div>
        
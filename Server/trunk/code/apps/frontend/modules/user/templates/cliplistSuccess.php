<div class="container profile">
  <div class="container-inner">
    <div class="main-bd clearfix">
      <section id="section">
        <div class="feed-mod">
          <h1><?php if ($sf_user->checkMe($sf_request->getParameter('uid'))==false){echo "我";}else{echo $user->getNickName()==NULL ? "TA" : $user->getNickName();}?>的片单</h1>
          <div class="feed-hd">
          <a href="<?php echo url_for("user/cliplist?type=default&uid=".$sf_request->getParameter('uid'));?>"<?php if($sf_request->getParameter('type')=="default") {?> class="active"<?php } ?>>我的收藏</a>
          <a href="<?php echo url_for("user/cliplist?type=watched&uid=".$sf_request->getParameter('uid'));?>" <?php if($sf_request->getParameter('type')=="watched") {?> class="active"<?php } ?>>看过</a>
          <a href="<?php echo url_for("user/cliplist?type=like&uid=".$sf_request->getParameter('uid'));?>" <?php if($sf_request->getParameter('type')=="like") {?> class="active"<?php } ?>>喜欢</a> </div>
          <div class="queue-list">
            <ul>
                <?php if(isset($chipPager)) {?>
                <?php foreach($chipPager->getResults() as $key => $chip) {?>
                <?php $wiki = $chip->getWiki();?>
              <li>
                <div class="program">
                  <div class="poster"><a href="<?php echo url_for('wiki/'.$wiki->getSlug())?>"><img src="<?php echo $wiki->getCoverUrl();?>" width="80" height="120" alt="<?php echo $wiki->getTitle();?>"></a></div>
                  <h3 class="title"><a href="<?php echo url_for('wiki/'.$wiki->getSlug())?>" target="_blank"><?php echo $wiki->getTitle();?></a></h3>
                  <div class="text-block"><span class="label">上映时间：</span><span class="param"><?php echo $wiki->getReleased();?></span></div>
                  <div class="text-block"><span class="label">导演：</span>
                      <?php if($wiki->getDirector()!=NULL):?>
                      <?php foreach($wiki->getDirector() as $key => $director):?>
                       <span class="param">
                           <a href="<?php echo url_for('search/index?q='.$director)?>"><?php echo $director;?></a>
                       </span>
                      <?php endforeach ?>
                      <?php endif?>
                    </div>
                  <div class="text-block"><span class="label">主演：</span>
                      <?php if($wiki->getStarring()!=NULL):?>
                      <?php foreach($wiki->getStarring() as $key => $starring) {?>
                      <span class="param"><a href="<?php echo url_for('search/index?q='.$starring)?>"><?php echo $starring;?></a></span>
                       <?php } ?>
                      <?php endif ?>
                  </div>
                  <div class="text-block"><span class="label">简介：</span><span class="param"><?php echo $wiki->getHtmlCache(120);?></span></div>
                  <?php if($chip->getUserId()==$sf_user->getAttribute("user_id")) {?>
                  <div class="queue-act">
                  <a href="javascript:cancalchip('<?php echo $wiki->getId();?>')">取消片单</a> 
                  </div>
                   <?php } ?>
                  <div class="date"><?php $date = $chip->getCreatedAt();
                     echo  $date->format('Y-m-d H:i:s');
                      ?> 添加</div>
                </div>
              </li>
            <?php } ?>
            <?php if ($chipPager->count()==0):?>
              <div class="no-data">暂无动态</div>
            <?php endif;?>
            <?php } else { echo NULL;?>
            <?php }?>
            </ul>
          </div>
          <?php if ($chipPager->count()!=0):?>
          <div class="pagination">
               <a href="<?php echo url_for('user/cliplist?page='.$chipPager->getPreviousPage().'&type='.$type);?>" class="page-prev">上一页</a>
                  <?php foreach ($chipPager->getLinks(5) as $page ):?>
                        <?php if ($page == $chipPager->getPage()):?>
                            <span class="present"><?php echo $page;?></span>
                        <?php else:?>
                            <a href="<?php echo url_for('user/cliplist?page='.$page);?>"><?php echo $page;?></a>
                        <?php endif;?>
                  <?php endforeach;?>
              <a href="<?php echo url_for('user/cliplist?page='.$chipPager->getNextPage().'&type='.$type);?>" class="page-next">下一页</a>
              (页码 <?php echo $chipPager->getPage();?>/<?php echo $chipPager->getLastPage();?>)
          </div>
          <?php endif?>
        </div>
      </section>
      <aside id="aside">
        <?php include_component('user', 'user_borad')?>
        <?php include_component('user', 'user_summ')?>
      </aside>
    </div>
  </div>
</div>
<script type="text/javascript">
function cancalchip(wiki_id) {
    if(wiki_id=="") return false;
     $.ajax({
     type: "POST",
     url: "<?php echo url_for('user/cancel_chip')?>",
     data:   "wiki_id="+wiki_id,
     success: function(m)
     {
         if(m===1){
            alert( "您成功从此片单中删除");
            window.location.reload();
         }else{
            alert("您从此片单中删除失败")
         }
     }
    });
}

function watched(wiki_id) {
    if(wiki_id=="") alert("wiki id is null");
    $.ajax ( {
        type: "POST",
        url: "<?php echo url_for("user/watched"); ?>",
        data: "wiki_id="+wiki_id,
        success: function(m)
        {
            //alert(m);
            if(m==1){
                alert("重复");
            }
            if(m==2){
                alert("添加成功");
            }else{
                alert("失败");
            }
        }
    })
}
</script>
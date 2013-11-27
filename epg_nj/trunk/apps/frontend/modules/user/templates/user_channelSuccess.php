<div class="container profile">
  <div class="container-inner">
    <div class="main-bd clearfix">
      <section id="section">
        <div class="feed-mod">
          <h1><?php if ($sf_user->checkMe($sf_request->getParameter('uid'))==false){echo "我";}else{echo $user->getNickName()==NULL ? "TA" : $user->getNickName();}?>的频道</h1>
          <div class="channel-list clearfix">
            <ul>
            <?php //var_dump($channels);?>
            <?php if ($channels!=NULL) :?>
            <?php foreach($channels as $key => $channel) {?>
              <li><div class="channel-logo">
              <span class="station"><a href="<?php echo url_for('channel/show?id='.$channel->getId());?>"><img src="<?php echo $channel->getLogoUrl();?>" alt="<?php echo $channel->getName();?>"></a></span> <span class="channel"><a href="<?php echo url_for('channel/show?id='.$channel->getId());?>"><?php echo $channel->getName();?></a></span>
              </div>
              <?php if($sf_request->getParameter('uid')==$sf_user->getAttribute("user_id" || $sf_request->getParameter('uid')=="")):?>
              <div class="del"><a href="javascript:removechannelId('<?php echo $channel->getCode();?>')">x 取消收藏</a></div>
              <?php endif;?>
              </li>  
            <?php } ?>
            <?php else:?>
              <div class="no-data">暂无动态</div>
            <?php endif; ?>
            </ul>
          </div>
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
function removechannelId(code) {
    if(code=="") return false;
    $.ajax({
    type: "POST",
    url: "<?php echo url_for('user/removechannel')?>",
    data:   "code="+code,
    success: function(m)
    {
         if(m==1){
            alert( "您成功从此片单中删除");
            window.location.reload();
         }else{
            alert("您从此片单中删除失败")
         }
    }
    });
}
</script>
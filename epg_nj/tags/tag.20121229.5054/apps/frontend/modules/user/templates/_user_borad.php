        <div class="profile-info">
          <div class="clearfix">
            <div class="profile_picture"> <img src="<?php echo thumb_url($user->getAvatar(), 96, 96)?>" width="96" height="96" alt="avatar"> </div>
            <div class="user-info">
              <h2>
         <?php if($user->getNickname()!=NULL):?>
         <?php echo $user->getNickname();?>
         <?php endif;?>
              </h2>
              <div class="location">常居：<a href="#"><?php echo $province;?></a></div>
            </div>
          </div>
           <?php if(!empty($desc)):?>
          <div class="user-intro">
            <p><?php echo $desc;?></p>
          </div>
          <?php endif;?>
          <?php if($sf_user->getAttribute('user_id')==$sf_request->getParameter('uid') || $sf_request->getParameter('uid')==""):?>
          <div class="button edit-button"><a href="<?php echo url_for("user/updateInfo");?>">编辑信息</a> </div>
          <?php endif;?>
           </div>
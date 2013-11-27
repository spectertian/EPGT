<?php if ($sf_user->hasFlash('notice')): ?>
            <div id="message-box" class="default">
              <div class="status info">
                <p><?php echo __($sf_user->getFlash('notice'), array(), 'sf_admin') ?></p>
                <a href="javascript:void(0)" onClick="$('#message-box').slideUp('slow')" class="close">Close</a>
              </div>
            </div>
<?php endif; ?>


<?php if ($sf_user->hasFlash('error')): ?>
           <div id="message-box" class="default">
              <div class="status error">
                <p><?php echo __($sf_user->getFlash('error'), array(), 'sf_admin') ?></p>
                <a href="javascript:void(0)" onClick="$('#message-box').slideUp('slow')" class="close">Close</a>
              </div>
            </div>
<?php endif; ?>
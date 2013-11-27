        <h3>上传封面/剧照</h3>
        <div class="widget-body">
          <div class="poster">
            <?php echo $form["cover"]->render(array("id" => "screenshots_cover")); ?>
            <a href="#"><img alt="" src="<?php echo $form->getDocument()->getCoverUrl(); ?>" id="screenshots_pic_cover"/></a>
            <a onclick="url_save('cover');" id="file-upload" href="<?php echo url_for('media/link'); ?>?function_name=screenshots">更改或上传海报</a>
          </div>
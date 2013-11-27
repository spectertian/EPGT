<?php include_partial("screenshots"); ?>
<form action="<?php echo url_for('wiki/'.($form->isNew() ? 'create' : 'update').(!$form->isNew() ? '?id='.$form->getDocument()->getId() : '')) ?>" method="post" name="adminForm">
    <?php echo $form->renderHiddenFields(); ?>
    <?php if ($form->hasGlobalErrors()): ?>
      <?php echo $form->renderGlobalErrors() ?>
    <?php endif; ?>
    <input type="hidden" id="wiki_model" name="model" value="<?php echo $form->getDocument()->getModelName(); ?>" />
    <div style="float:left; width:65%;">
      <div class="widget">
        <h3>基本资料</h3>
        <div class="widget-body">
          <ul class="wiki-meta">
            <li><label>采集维基地址：</label> <input type="text" id="wiki-url"> <input name="" type="button" value="采集维基" id="get-wiki-btn" onclick="javascript:getSiteWikiData()"></li>
            <li><label>姓名：</label><?php echo $form["title"]->render(array("size" => "50")); ?><?php echo $form['title']->getError() ?></li>
            <li><label>英文名：</label><?php echo $form["english_name"]->render(); ?></li>
            <li><label>昵称：</label><?php echo $form["nickname"]->render(); ?></li>
            <li><label>性别：</label><?php echo $form["sex"]->render(); ?></li>
            <li><label>生日：</label><?php echo $form["birthday"]->render(array("size" => "40")); ?></li>
            <li><label>籍贯：</label><?php echo $form["birthplace"]->render(array("size" => "40")); ?></li>
            <li><label>国籍：</label><?php echo $form["nationality"]->render(); ?></li>
            <li><label>地域：</label><?php echo $form["region"]->render(); ?></li>
            <li><label>职业：</label><?php echo $form["occupation"]->render(array("size" => "40")); ?></li>
            <li><label>星座：</label><?php echo $form["zodiac"]->render(); ?></li>
            <li><label>血型：</label><?php echo $form["blood_type"]->render(); ?></li>
            <li><label>身高：</label><?php echo $form["height"]->render(); ?></li>
            <li><label>体重：</label><?php echo $form["weight"]->render(); ?></li>
            <li><label>宗教信仰：</label><?php echo $form["faith"]->render(array("size" => "40")); ?></li>
            <li><label>出道日期：</label><?php echo $form["debut"]->render(array("size" => "40")); ?></li>
            <li><label>艺人简介：</label><?php echo $form["content"]->render(array("rows" => "30")); ?></li>
            <input id="wiki_admin_id" type="hidden" name="wiki[admin_id]" value="<?php echo $sf_user->getAttribute('adminid');?>">
          </ul>
        </div>
      </div>
    </div>
    <div style="width:33%; float:right;">
     <div class="widget">
        <?php include_partial("cover", array("form" => $form)); ?>
          <div class="filmstill">
            <?php $screenshots = $form->getDocument()->getScreenshots(); ?>
            <ul  id="right">
              <?php if (!empty ($screenshots)):?>
                <?php foreach ($screenshots as $key => $screenshot):?>
              <li>
                <input type="hidden" value="<?php echo $screenshot;?>" name="wiki[screenshots][]" id="screenshots_<?php echo $key;?>" />
                <a href="#"><img src="<?php echo file_url($screenshot);?>" id="screenshots_pic_<?php echo $key;?>" width="100%"></a>
                 <a id="file-uploads" class="update" href="<?php echo url_for('media/link'); ?>?function_name=screenshotAdds">更改</a> | <a href="#" class="delete" onclick="$(this).parent().remove();">删除</a>
              </li>
                <?php endforeach;?>
            <?php endif;?>
            </ul>

            <div class="action-box">
              <a id="file-uploads" class="button" href="<?php echo url_for('media/link'); ?>?function_name=screenshotAdds">上传剧照</a>

            </div>
          </div>
        </div>



    </div>
  </form>

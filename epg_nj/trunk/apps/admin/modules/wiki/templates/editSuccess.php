<?php if($form->getDocument()->getModelName() == "television"): ?>
<script type="text/javascript">
$(document).ready(function(){
    $.datepicker.setDefaults($.datepicker.regional['zh_CN']);
    $('.datepicker').datepicker({
        //			changeMonth: true,
        //			changeYear: true
        showButtonPanel: true,
        showOtherMonths: true,
        selectOtherMonths: true,
        dateFormat: 'yymmdd',
        showWeek: true,
        firstDay: 1,
        defaultDate: +0
    });

    $("#addMate").click(function(){
        var date = $(".datepicker").val();
        var len = $("#opt_"+date).length;
        if (len > 0) {
            alert('改分期已存在!');
            return ;
        }

        if(date == '选择综艺分期'){
            alert('请选择综艺分期!');
            return true;
        }

      $(".meta_id").val("");
      $(".meta_mark").val(date);
      $(".meta_mark").text(date);

      var wiki_id = $("#wiki_id").val();
      $(".meta_wiki_id").val(wiki_id);

      $(".widget").hide();
      $("#widget").show();
      $(".meta_title,.meta_content,.meta_guests").val("");
      $("#widgets ul").empty();
      $("#widgets .action-box").empty();
       content = "<a id=\"file-uploads\" class=\"button\" href=\"<?php echo url_for('media/link'); ?>?function_name=columnDramaScreenshot>上传剧照<\/a>";
      $("#widgets .action-box").append(content);
      $("input[value='删除']").hide();
      add_drama_fancybox();
      $("#widgets").show();
    });
});
</script>
<?php endif;?>
<div id="content">
    <div class="content_inner">
      <header>
        <h2 class="content">修改：<?php echo $form->getDocument()->getTitle();?></h2>
        <?php include_partial('toolbar',array('id' => $form->getDocument()->getId()));?>
    
        <?php if($form->getDocument()->getModel() == "teleplay"):?>
        <div class="header-meta">
            <a class="button" onClick="javascript:showMain(); return false">编辑主条目</a>
              <label>
                请选择：
                <select id="showDrama" onchange="showDramaAction();">
                    <?php if (!empty ($metas)): ?>
                        <?php foreach ($metas as $meta):?>
                        <option value="<?php echo $meta->getMark();?>" id="opt_<?php echo $meta->getMark();?>">第 <?php echo $meta->getMark();?> 集</option>
                        <?php endforeach;?>
                    <?php else:?>
                        <option>还没有分集</option>
                    <?php endif;?>
                </select>
              </label>
            <a class="button" href="javascript:dramaAdd();">添加分集剧情</a>
        </div>
        <?php endif;?>
        
       <?php if($form->getDocument()->getModelName() == "television"):?>
           <div class="header-meta">
              <a class="button" onclick="window.location.reload()">编辑主条目</a>
              <label>
                请选择：
                <select id="staging" onchange="showstaging();">
                    <option>选择分期</option>
                    <?php if (!empty ($television_metas)): ?>
                        <?php foreach ($television_metas as $meta):?>
                        <option value="<?php echo $meta->getId();?>" id="opt_<?php echo $meta->getMark();?>">第 <?php echo $meta->getMark();?>期 </option>
                        <?php endforeach;?>
                    <?php endif;?>
                </select>
                <input name="date" class="datepicker" maxlengtjh="10" value="<?php echo ($sf_request->getParameter('date')) ? $sf_request->getParameter('date') : '选择综艺分期' ?>" type="button" />
                <input value="确定" id="addMate" type="button">
              </label>
            </div>
        <?php endif;?>
      </header> 
      <?php include_partial('global/flashes') ?>
      <?php include_partial($form->getDocument()->getModel().'_form', array('form'=> $form))?>
    </div>
  </div>
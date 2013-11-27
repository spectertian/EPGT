<div class="container settings">
  <div class="container-inner">
<?php include_partial('setmenu')?>
    <div class="main-bd">
      <div class="settings-tip">将你在5iTV的动态分享同步到各大社交网站，和朋友们一起分享看电视的乐趣 ~</div>
      <div class="common-form">
        <div class="sync">
          <div class="synced clearfix">
            <h3>同步分享</h3>
            <ul>
            <?php if (!empty($sharelist)) { ?>
            <?php foreach($sharelist as $key => $val) {?>
              <li class="<?php echo $key ?>">
               <?php if($val['enable']==false) {?>
               <a href="<?php echo $$key; ?>">
               <?php } ?>
              <img src="<?php echo image_path($key.'.jpg');?>" alt="<?php echo $key ?>"/>
              <?php if($val['enable']==false) {?>
              </a>
              <?php } ?>
              <?php if($val['enable']!==false) {?>
                <p id="<?php echo $key ?>"><a href="javascript:cleanshare('<?php echo $key?>')">取消同步</a></p>
              <?php } ?>
              </li>
            <?php } ?>
            <?php } ?>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
function cleanshare(type) {
    $.ajax({
     type: "POST",
     url: "<?php echo url_for('user/cleanShare')?>",
     data:   "type="+type,
     success: function(m)
     { 
         if(m==1){
            //alert("您成功的取消了此分享");
            $("#"+type).html('');
            window.location.reload();
         }else{
           // alert("您的取消了此分享失败")
         }
     } 
    }); 
}
</script>
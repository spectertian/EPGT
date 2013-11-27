<script type="text/javascript">
$(document).ready(function(){
    $.ajax({
       type: "GET",
       url: "<?php echo url_for('admin/auths') ?>",
       data: "admin_id=<?php echo $form->getObject()->getId(); ?>",
       dataType: "json",
       success: function(data){
          $.each(data, function(i, v){
              $('input[value="' + v.credential +'"]').attr('checked', 'checked');
          })
       }
    });
}); 
</script>
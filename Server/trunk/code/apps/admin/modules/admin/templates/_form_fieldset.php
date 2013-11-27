<script type="text/javascript">
    $(document).ready(function(){
        $("#select_all").click(function(){
            $(".wiki-meta").find("input[type=checkbox]").each(function(x){
                $(this).attr("checked",true);
            });
        });
        $("#reverse").click(function(){
            $(".wiki-meta").find("input[type=checkbox]").each(function(x){
                if($(this).attr("checked") == true)
                {
                    $(this).attr("checked",false);
                }else{
                    $(this).attr("checked",true);
                }
            });
        });
        <?php if($form->isNew()):?>
        $("#admin_status option[value='1']").attr("selected", true); 
        <?php endif;?>
      
    });
</script>




<ul class="wiki-meta">

        <?php foreach ($fields as $name => $field): ?>
            <?php if ((isset($form[$name]) && $form[$name]->isHidden()) || (!isset($form[$name]) && $field->isReal())) continue ?>
            <?php 
              $attributes = $field->getConfig('attributes', array());
              $label      = $field->getConfig('label');
             ?>
        <li>
            <?php echo $form[$name]->renderLabel($label); ?>
                    <?php echo $form[$name]->render($attributes instanceof sfOutputEscaper ? $attributes->getRawValue() : $attributes); ?>
                    <?php echo $form[$name]->renderError(); ?>
        </li>

        <?php endforeach; ?>
        <li><a href="#" id="select_all" onclick="return false;">全选</a> / <a href="#" id="reverse" onclick="return false;">反选</a></li>
</ul>



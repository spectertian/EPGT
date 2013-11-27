<div class="main">
	
    <div class="srclist">
    	<h2><a href="<?php echo url_for('search/index')?>">重新搜素</a><strong>搜索</strong>"<?php echo $key?>"的结果(共<?php echo $count;?>)</h2>
        <div>
        	<ul class="clr">
            <?php foreach($wikis as $wiki):?>         
            	<li><a href="javascript:wiki('<?php echo (string)$wiki->getId()?>')"><img src="<?php echo thumb_url($wiki->getCover(), 100, 150)?>"/></a></li>
            <?php endforeach;?>
            </ul>
        </div>
        
        <dl>
        	<dt><a href="">标题</a></dt>
            <dd>介绍</dd>
        </dl>
    </div>
    
</div>
<script type="text/javascript">
$(document).ready(function() {
	$('dl dt').html("");
    $('dl dd').html("");
})
//加载选中数据
function wiki(id){
	$.ajax({
	    url: '/search/wiki',
	    type: 'get',
	    dataType: 'json',
	    data: {'id': id},
	    success: function(data)
	    {
        	if(data==null){
        		$('dl dt').html("暂无该节目信息");
                $('dl dd').html("");
        	}else{
        	    $('dl dt').html("<a href='/wiki/show/slug/"+data.slug+"'>"+data.title+"</a>");
                $('dl dd').html(data.content);
        	}       
	    }
	});
}
</script>    

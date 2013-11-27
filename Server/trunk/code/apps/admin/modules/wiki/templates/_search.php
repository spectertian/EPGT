<form method="get" action="">
    <label>名称：</label>
    <input name="q" id="q"  value="<?php echo $q?>" type="text">
    <label>创建者：</label>

<select name="c" id="c">
<option value="">不限</option>
<option value="null" <?php echo ('null' == $c) ? 'selected=selected' : ''?>>暂无记录</option>
<?php foreach($users as $user):?>
<option value="<?php echo $user->getId()?>" <?php echo ($user->getId() == $c) ? 'selected=selected' : ''?>><?php echo $user->getName()?></option>
<?php endforeach;?>
</select>
<script>
$(document).ready(function(){
	if($("#moxing").change(function(){
		if($(this).val() == 'television')
		{
			$("#seconddownlist").show();
		}
		else
			$("#seconddownlist").hide();
	}));
});
</script>
 模型：
 <select name="m" id="moxing" >
                        <option value="all"<?php echo ('all' == $m) ? 'selected=selected' : ''?>>全部</option>
                        <option value="film"<?php echo ('film' == $m) ? 'selected=selected' : ''?>>电影</option>
                        <option value="teleplay"<?php echo ('teleplay' == $m) ? 'selected=selected' : ''?>>电视剧</option>
                        <option value="television"<?php echo ('television' == $m) ? 'selected=selected' : ''?>>栏目</option>
                        <option value="actor"<?php echo ('actor' == $m) ? 'selected=selected' : ''?>>艺人</option>
</select>

<span id="seconddownlist" <?php if(('television' != $m)):?>style="display:none"<?php endif;?>>
	<select name="tag">
		<option value=""<?php echo ('' == $tag) ? 'selected=selected' : ''?>>请选择</option>
		<option value="体育"<?php echo ('体育' == $tag) ? 'selected=selected' : ''?>>体育</option>
		<option value="娱乐"<?php echo ('娱乐' == $tag) ? 'selected=selected' : ''?>>娱乐</option>
		<option value="少儿"<?php echo ('少儿' == $tag) ? 'selected=selected' : ''?>>少儿</option>
		<option value="科教"<?php echo ('科教' == $tag) ? 'selected=selected' : ''?>>科教</option>
		<option value="财经"<?php echo ('财经' == $tag) ? 'selected=selected' : ''?>>财经</option>
		<option value="综合"<?php echo ('综合' == $tag) ? 'selected=selected' : ''?>>综合</option>
	</select>
</span>
 <input type="submit" value="查询"> &nbsp;

<script type="text/javascript">
$(function() {
	$('.tips').powerTip({ placement: 'ne-alt' });
});
function save(arg) 
{
	$('#div_1').show();
	return;
}
//关闭层
function closediv(getdiv)
{
	$('#div_1').hide();
	return;
}
//提交表单
function tijiao( )
{
	$('#form_1').submit();
	return;
}
</script>
	<?php include_partial('global/flashes') ?> 
	<div id="warp">
	<div class="r">
		<header>
			<h2 class="content"><?php echo $PageTitle; ?></h2>
			<nav class="utility">
				<li class="add"><a href="<?php echo "/nextweek_program/add?date=".$date;?>">添加</a></li>
				<li class="save"><a href="#" onclick="save($(this));">另存为</a></li>
				<li class="back"><a href="<?php echo "/nextweek_program?date=".$date ?>">返回列表</a></li>
			</nav>
		</header>
		<?php include_partial('weeks'); ?>
		<div id="stock">
		<?php if($nextWeekPrograms):?>
		<div class="listwarp" >
			<ul class="list" >
			<?php foreach($nextWeekPrograms as $k => $rs):?>
				
					<li class="mvcover">
							<img class="tips" src="<?php echo ($rs->getStyle() == 470*350)?thumb_url($rs->getPoster(),470,350):thumb_url($rs->getPoster(),230,350); ?>" 
							alt="<?php echo $rs->getProgramName() ?>" title="<?php echo "节目名称：".$rs->getProgramName()."<br/>频道名称：".$channelNmaes[$rs->getChannelCode()]."<br/>频道号：".$rs->getChannelCode() ?>" >
							<a href="<?php echo "/nextweek_program/edit?id=".$rs->getId()?>" class="edit">编辑</a>|
							<a href="<?php echo "/nextweek_program/delete?id=".$rs->getId()?>" onclick="if(!confirm('确定删除吗？')) return false;">删除</a>
					</li>
				
			<?php if((($k+1)%5 == 0)): ?>
			</ul>
			</div>
			<div class="listwarp">
			<ul class="list" >
			<?php endif?>
			<?php endforeach; ?>
		<?php endif; ?>
		</div>
	</div>
	<div id="div_1" style="display: none">
	<form name="form_1" method="post" id="form_1" action="/nextweek_program/OtherSave">
		<ul class="aboutsave">
			<li><h2>节目另存为</h2></li>
			<li><label>选择另存为时间:</label>
				<input id="datepicker" class="datepicker" type="text" onclick="displayCalendar(document.getElementById('datepicker'),'yyyy-mm-dd',this)" 
				value="<?php echo $date?date("Y-m-d",strtotime("$date   +1   day")):date('Y-m-d H:i:s',strtotime('+1 day')) ?>" name="myDate">
				<input name="id" type="hidden" value="<?php echo $ids?>" >
			</li>
			<li>
				<input type="button" value="保存" class="btn" onclick="tijiao();" />
				<input type="button" value="取消" class="btn" onclick="closediv('div_1');" />
			</li>
		</ul>
	</form>
	</div>

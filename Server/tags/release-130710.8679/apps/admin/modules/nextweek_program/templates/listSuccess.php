<script type="text/javascript">
$(function() {
	$('.tips').powerTip({ placement: 'ne-alt' });
});
</script>
	<?php include_partial('global/flashes') ?> 
	<div id="warp">
	<div class="r">
		<header>
			<h2 class="content"><?php echo $PageTitle; ?></h2>
			<nav class="utility">
				<li class="add"><a href="<?php echo "/nextweek_program/add?date=".$date;?>">添加</a></li>
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

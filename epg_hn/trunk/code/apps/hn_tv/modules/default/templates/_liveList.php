<div class="list">
<?php foreach($program_list as $program):?>
			<?php $all = strtotime($program->getEndTime()->format("Y-m-d H:i:s")) - strtotime($program->getTime());?>
			<?php $plan = time() - strtotime($program->getTime());?>
			<?php $width = round($plan/$all,2) * 100?>
            <h3><?php echo date("H:i",$program->getStartTime()->getTimestamp());?> <a href="<?php echo url_for('wiki/show?slug='.$program->getWiki()->getSlug())?>"><?php echo mb_strcut($program->getWikiTitle(), 0, 27, 'utf-8');?></a> <span><em style="width:<?php echo ($width > 100) ? ($width - 100) : $width?>%"></em></span></h3>
<?php endforeach;?>	
</div>
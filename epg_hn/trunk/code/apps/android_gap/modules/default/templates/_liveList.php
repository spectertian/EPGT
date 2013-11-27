<?php if($module=='default'):?>
	<?php foreach($program_list as $program):?>
    			<?php $all = strtotime($program->getEndTime()->format("Y-m-d H:i:s")) - strtotime($program->getTime());?>
				<?php $plan = time() - strtotime($program->getTime());?>
				<?php $width = round($plan/$all,2) * 100?>
                        	<li>
                            	<h3>
                                	<a href="<?php echo url_for('wiki/show?slug='.$program->getWiki()->getSlug())?>">
                                        <m><img src="<?php echo thumb_url($program->getChannelLogo(), 70, 36);?>" alt=""/></m>
                                        <b><?php echo mb_strcut($program->getChannelName(), 0, 27, 'utf-8');?></b>
                                        <strong><?php echo mb_strcut($program->getWikiTitle(), 0, 27, 'utf-8');?></strong>
                                        <span><em style="width:<?php echo ($width > 100) ? ($width - 100) : $width?>%;"></em></span>
                                        <i><?php echo date("H:i",$program->getStartTime()->getTimestamp());?></i><dfn><?php echo date("H:i",$program->getEndTime()->getTimestamp());?></dfn>
                                    </a>
                                </h3>
                            </li> 
	<?php endforeach;?>	
<?php endif;?>
<?php if($module=='dtv'):?>
	<?php if(count($program_list)<=0):?>
	<?php else:?>
			<section class="playlist">
				<h2 class="<?php echo $key;?>"><?php echo $tag;?></h2>
				<ul class="playlist">
					<?php foreach($program_list as $program):?>
					<li><a href="<?php echo url_for('wiki/show?slug='.$program->getWiki()->getSlug())?>"><img src="<?php echo thumb_url($program->getWiki()->getCover(), 114, 146);?>" alt=""/><?php echo $program->getWikiTitle();?></a></li>
					<?php endforeach;?>
				</ul>
			</section>	
	<?php endif;?>
<?php endif;?>

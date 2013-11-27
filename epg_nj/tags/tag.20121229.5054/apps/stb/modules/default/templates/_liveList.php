<?php
    $i=1; 
    foreach($program_list as $program):
        if($i>8){break;} 
        if($program->getWikiScreen()):
?>
			<?php $all = strtotime($program->getEndTime()->format("Y-m-d H:i:s")) - strtotime($program->getStartTime()->format("Y-m-d H:i:s"));?>
			<?php $plan = time() - strtotime($program->getStartTime()->format("Y-m-d H:i:s"));?>
			<?php $width = round($plan/$all,2) * 100?>  
					<li>
						<a href="#" onclick="return goChannelByName('<?php echo $program->getSpName();?>');">
							<img src="<?php echo thumb_url($program->getWikiScreen(),207,155);?>" />
							<span>
								<i><big><?php echo $program->getSpName();?>:<?php echo $program->getWikiTitle();?></big></i>
								<strong><b style="width:<?php echo ($width > 100) ? 100 : $width?>%"></b></strong>
							</span>
							<em><?php echo $program->getSpHot();?>人</em>
                            <em class="tvnumber"><?php echo $program->getSpHot();?>人</em>
						</a>
					</li>                                
<?php
            $i++;
        endif;
    endforeach;
?>
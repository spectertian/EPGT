<?php
    $i=1; 
    foreach($program_list as $program):
        if($i>6){break;} 
        if($program['wikiscreen']):
?>
					<li>
						<a href="#" onclick="return goChannelByName('<?php echo $program['spname'];?>');">
							<img src="<?php echo thumb_url($program['wikiscreen'],207,155);?>" />
							<span>
								<i><big><?php echo $program['spname'];?>:<?php echo $program['wikititle'];?></big></i>
								<strong><b style="width:<?php echo ($program['width'] > 100) ? 100 : $program['width']?>%"></b></strong>
							</span>
							<em><?php echo $program['sphot'];?>人</em>
                            <em class="tvnumber"><?php echo $program['sphot'];?>人</em>
						</a>
					</li>                                
<?php
            $i++;
        endif;
    endforeach;
?>
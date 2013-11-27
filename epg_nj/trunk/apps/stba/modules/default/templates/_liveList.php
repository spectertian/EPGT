<?php
    $i=1; 
    foreach($program_list as $program):
        if($i>6){break;} 
        if($program['wikiscreen']):
?>
					<li>
						<a href="#" title="" class="there" onclick="return goChannelByName('<?php echo $program['spname'];?>');" onmouseover="showNum('num1',<?php echo $i?>)">
							<img src="<?php echo thumb_url($program['wikiscreen'],148,194);?>" alt=""/>
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
                <?php if($count_programs==0):?>
				<div class="hotplays">
					<h2>热播频道推荐</h2>
					<div class="hotplash">
						<ul id="hotplays">
                            <?php 
                                  $i=1;
                                  foreach($hot_programs as $program): 
                            ?>
							<li <?php if(($i%2)!=1){echo ' class="even"';}?>><a href="#" onclick="return goChannelByName('<?php echo $program->getSpName()?>')"><i><big><?php echo $program->getSpName() ?> ：<?php echo $program->getName(); ?></big></i></a></li>
							<?php $i++;
                                  endforeach; 
                            ?>
						</ul>
					</div>
				</div>
				<?php else:?>
				<div class="hotplays">
					<h2>频道回看</h2>
					<div class="hotplash"  id='hotplasy'>
						<ul id='huikan'>
                            <?php 
                                  $i=1;
                                  foreach($played_programs as $program): 
                            ?>
							<li <?php if($i%2!=1){echo ' class="even"';}?>>
                            <a href="#" onclick="played('<?php echo $program->getCpgContentId();?>')"><i><big><?php echo $program->getSpName() ?>:<?php echo $program->getName(); ?></big></i></a>
                            </li>
							<?php $i++;
                                  endforeach; 
                                  for($k=$i;$k<4;$k++){
                                      if($k%2!=1)
                                          echo '<li class="even">&nbsp;</li>';
                                      else
                                          echo '<li>&nbsp;</li>';
                                  }
                            ?>
						</ul>
					</div>
				</div>
				
				<div class="hotplays advance">
					<h2>播出预告</h2>
					<div class="hotplash">
						<ul id="yugao">
                            <?php 
                                  $i=1;
                                  foreach($unplayed_programs as $program): 
                            ?>
							<li <?php if($i%2!=1){echo ' class="even"';}?>>
                            <a href="#" onclick="orderAdd('<?php echo $program->getSpName() ?>','<?php echo $program->getName() ?>','<?php echo $program->getStartTime()->format("Y/m/d H:i:s") ?>','<?php echo $program->getChannelCode() ?>')"><i><big><?php echo $program->getSpName() ?> : <?php echo $program->getName(); ?></big></i><span><?php echo $program->getStartTime()->format("H:i") ?></span></a>
                            </li>
							<?php $i++;
                                  endforeach; 
                                  for($k=$i;$k<4;$k++){
                                      if($k%2!=1)
                                          echo '<li class="even">&nbsp;</li>';
                                      else
                                          echo '<li>&nbsp;</li>';
                                  }
                            ?>                        
						</ul>
					</div>
				</div>
                <?php endif;?>
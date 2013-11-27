                <?php if($count_programs==0):?>
				<div class="hotplays">
					<h2>正在热播</h2>
					<div class="hotplash" id="hotLiveProgram">
						<ul>
                            <?php 
                                  $i=0;
                                  foreach($hot_programs as $program): 
                            ?>
							<li <?php if($i%2!==0){echo ' class="even"';}?>><a href="#" onclick="return goChannelByName('<?php echo $program->getChannelName()?>')"><span><b><?php echo $program->getName(); ?></b></span><?php echo $program->getChannelName() ?></a></li>
							<?php $i++;
                                  endforeach; 
                            ?>
						</ul>
					</div>
				</div>
				<?php else:?>
				<div class="hotplays reseeding reseedings">
					<h2>频道回看</h2>
					<div class="hotplash">
						<ul>
                            <?php 
                                  $i=0;
                                  foreach($played_programs as $program): 
                            ?>
							<li <?php if($i%2==0){echo ' class="even"';}?>><a href="#"><span><?php echo mb_strcut($program->getName(), 0, 15, 'utf-8') ?></span><?php echo $program->getChannelName() ?></a></li>
							<?php $i++;
                                  endforeach; 
                                  for($k=$i;$k<3;$k++){
                                      if($k%2==0)
                                          echo '<li class="even"></li>';
                                      else
                                          echo '<li></li>';
                                  }
                            ?>
						</ul>
					</div>
				</div>
				
				<div class="hotplays advance">
					<h2>播出预告</h2>
					<div class="hotplash">
						<ul>
                            <?php 
                                  $i=0;
                                  foreach($unplayed_programs as $program): 
                            ?>
							<li <?php if($i%2==0){echo ' class="even"';}?>><a href="#" onclick="orderAdd('<?php echo $program->getChannelName() ?>','<?php echo $program->getName() ?>','<?php echo $program->getStartTime()->format("Y/m/d H:i:s") ?>','<?php echo $program->getChannelCode() ?>')"><span><?php echo $program->getStartTime()->format("H:i") ?></span><?php echo $program->getChannelName() ?></a></li>
							<?php $i++;
                                  endforeach; 
                                  for($k=$i;$k<3;$k++){
                                      if($k%2==0)
                                          echo '<li class="even"></li>';
                                      else
                                          echo '<li></li>';
                                  }
                            ?>                        
						</ul>
					</div>
				</div>
                <?php endif;?>
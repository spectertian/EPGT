<?php if($model=='television') : //综艺节目?>
    <dl class="mvjj">

        <dt>栏目信息</dt>
        <dd>
            <ul>
                <?php if($hosts = $wiki->getHost()): $i = 0 ?>
                <li><strong>      
		                <?php foreach($hosts as $host) : $i++;?>
		                <?php echo ($i > 1) ? ' /' : ''; echo $host;?>
		                <?php endforeach;?>                   
                </strong><span>主持人</span></li>
                <?php endif; ?> 

                <?php if($guests = $wiki->getGuests()): $i = 0 ?>
                <li><strong>      
		                <?php foreach($guests as $guest) : $i++;?>
		                <?php echo ($i > 1) ? ' /' : ''; echo $guest;?>
		                <?php endforeach;?>               
                </strong><span>嘉宾</span></li>
                <?php endif; ?> 
                
                <?php if($wiki->getChannel()): ?>	
                <li><strong>      
		                <?php echo $wiki->getChannel()?>                  
                </strong><span>播出频道</span></li>
                <?php endif; ?> 
                
                <?php if($wiki->getPlayTime()): ?>	
                <li><strong>      
		                <?php echo $wiki->getPlayTime()?>                   
                </strong><span>播出时间</span></li>
                <?php endif; ?> 
                
                <?php if($wiki->getRuntime()): ?>
                <li><strong>      
		                <?php echo $wiki->getRuntime()?>                   
                </strong><span>播出时长</span></li>
                <?php endif; ?> 
                
                <?php if($wiki->getCountry()): ?>
                <li><strong>      
		                <?php echo $wiki->getCountry()?>                   
                </strong><span>国家</span></li>
                <?php endif; ?> 
                
                <?php if($wiki->getLanguage()): ?>
                <li><strong>      
		                <?php echo $wiki->getLanguage()?>           
                </strong><span>语言</span></li>
                <?php endif; ?>                                                                                            
            </ul>
        </dd>
        <dt>栏目介绍</dt>
        <dd><?php if($wiki->getHtmlCache()): ?>	<?php echo $wiki->getHtmlCache(1000, ESC_RAW); ?><?php endif; ?></dd>        
    </dl>
<?php elseif($model=='telplay'):  //电视剧?>
    <dl class="mvjj">

        <dt>节目信息</dt>
        <dd>
            <ul>
                <?php if($wiki->getReleased()): ?>
                <li><strong>      
		                <?php echo $wiki->getReleased()?>                 
                </strong><span>上映时间</span></li>
                <?php endif; ?> 
                
                <?php if($wiki->getEpisodes()): ?>
                <li><strong>      
		                <?php echo $wiki->getEpisodes()?>           
                </strong><span>集数</span></li>
                <?php endif; ?>  
                            
                <?php if($Directors = $wiki->getDirector()): $i = 0 ?>  
                <li><strong>            
                <?php foreach($Directors as $Director) : $i++;?>
                <?php echo ($i > 1) ? ' /' : ''; echo $Director;?>
                <?php endforeach;?>                
                </strong><span>导演</span></li>
                <?php endif; ?>     
                
                <?php if($Writers = $wiki->getWriter()): $i = 0 ?>    
                <li><strong>
                <?php foreach($Writers as $Writer) : $i++;?>
                <?php echo ($i > 1) ? ' /' : ''; echo $Writer;?>
                <?php endforeach;?>                  
                </strong><span>编剧</span></li>
                <?php endif; ?>  
                  
                <?php if($Stars = $wiki->getStarring()): $i = 0 ?>    
                <li><strong>       
                <?php foreach($Stars as $Star) : $i++;
                      if($i<6):
                ?>
                <?php echo ($i > 1) ? ' /' : ''; echo $Star;?>
                <?php 
                      endif;
                      endforeach;?>                           
                </strong><span>主演</span></li>  
                <?php endif; ?>  
                
                <?php if($wiki->getCountry()): ?>
                <li><strong>      
		                <?php echo $wiki->getCountry()?>                   
                </strong><span>国家</span></li>
                <?php endif; ?> 
                
                <?php if($wiki->getLanguage()): ?>
                <li><strong>      
		                <?php echo $wiki->getLanguage()?>           
                </strong><span>语言</span></li>
                <?php endif; ?>    
                
                <?php if($Distributors = $wiki->getDistributor()): $i=0 ?>
                <li><strong>      
		                <?php foreach($Distributors as $Distributor) : $i++;?>
		                <?php echo ($i > 1) ? ' /' : ''; echo $Distributor;?>
		                <?php endforeach;?>                   
                </strong><span>出品公司</span></li>
                <?php endif; ?> 
                
                <?php if($wiki->getProduced()): ?>	
                <li><strong>      
		                <?php echo $wiki->getProduced()?>       
                </strong><span>制作日期</span></li>
                <?php endif; ?>                                   
            </ul>
        </dd>
        <dt>剧情简介</dt>
        <dd><?php if($wiki->getHtmlCache()): ?>	<?php echo $wiki->getHtmlCache(1000, ESC_RAW); ?><?php endif; ?></dd>        
    </dl>
<?php else:  //电影?>    
    <dl class="mvjj">

        <dt>节目信息</dt>
        <dd>
            <ul>
                <?php if($wiki->getReleased()): ?>
                <li><strong>      
		                <?php echo $wiki->getReleased()?>          
                </strong><span>上映时间</span></li>
                <?php endif; ?> 

                <?php if($wiki->getRuntime()): ?>
                <li><strong>      
		                <?php echo $wiki->getRuntime()?>分钟            
                </strong><span>片长</span></li>
                <?php endif; ?> 
                                            
                <?php if($Directors = $wiki->getDirector()): $i = 0 ?>  
                <li><strong>            
                <?php foreach($Directors as $Director) : $i++;?>
                <?php echo ($i > 1) ? ' /' : ''; echo $Director;?>
                <?php endforeach;?>                
                </strong><span>导演</span></li>
                <?php endif; ?>     
                
                <?php if($Writers = $wiki->getWriter()): $i = 0 ?>    
                <li><strong>
                <?php foreach($Writers as $Writer) : $i++;?>
                <?php echo ($i > 1) ? ' /' : ''; echo $Writer;?>
                <?php endforeach;?>                  
                </strong><span>编剧</span></li>
                <?php endif; ?>  
                  
                <?php if($Stars = $wiki->getStarring()): $i = 0 ?>    
                <li><strong>       
                <?php foreach($Stars as $Star) : $i++;
                      if($i<6):
                ?>
                <?php echo ($i > 1) ? ' /' : ''; echo $Star;?>
                <?php 
                      endif;
                      endforeach;?>                           
                </strong><span>主演</span></li>  
                <?php endif; ?>  
                
                <?php if($wiki->getCountry()): ?>
                <li><strong>      
		                <?php echo $wiki->getCountry()?>                   
                </strong><span>国家</span></li>
                <?php endif; ?> 
                
                <?php if($wiki->getLanguage()): ?>
                <li><strong>      
		                <?php echo $wiki->getLanguage()?>           
                </strong><span>语言</span></li>
                <?php endif; ?>    
                
                <?php if($Distributors = $wiki->getDistributor()): $i=0 ?>
                <li><strong>      
		                <?php foreach($Distributors as $Distributor) : $i++;?>
		                <?php echo ($i > 1) ? ' /' : ''; echo $Distributor;?>
		                <?php endforeach;?>                   
                </strong><span>出品公司</span></li>
                <?php endif; ?> 
                
                <?php if($wiki->getProduced()): ?>	
                <li><strong>      
		                <?php echo $wiki->getProduced()?>       
                </strong><span>制作日期</span></li>
                <?php endif; ?>                                   
            </ul>
        </dd>
        <dt>剧情简介</dt>
        <dd><?php if($wiki->getHtmlCache()): ?>	<?php echo $wiki->getHtmlCache(1000, ESC_RAW); ?><?php endif; ?></dd>        
    </dl>
<?php endif;?>
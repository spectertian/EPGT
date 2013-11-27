<?php if($model=='television') : //综艺节目?>
        <?php if($hosts = $wiki->getHost()): $i = 0 ?>
        <li>主持人：      
                <?php foreach($hosts as $host) : $i++;?>
                <?php echo ($i > 1) ? ' /' : ''; echo $host;?>
                <?php endforeach;?>                  
        </li>
        <?php endif; ?> 
    
        <?php if($guests = $wiki->getGuests()): $i = 0 ?>
        <li>嘉宾：      
                <?php foreach($guests as $guest) : $i++;?>
                <?php echo ($i > 1) ? ' /' : ''; echo $guest;?>
                <?php endforeach;?>               
        </li>
        <?php endif; ?> 
        
        <?php if($wiki->getChannel()): ?>	
        <li>播出频道：      
                <?php echo $wiki->getChannel()?>                  
        </li>
        <?php endif; ?> 
        
        <?php if($wiki->getPlayTime()): ?>	
        <li>播出时间：     
                <?php echo substr($wiki->getPlayTime(),30)?>                   
        </li>
        <?php endif; ?> 
        
        <?php if($wiki->getRuntime()): ?>
        <li>播出时长：     
                <?php echo $wiki->getRuntime()?>                   
        </li>
        <?php endif; ?> 
        
        <?php if($wiki->getCountry()): ?>
        <li>国家：     
                <?php echo $wiki->getCountry()?>                   
        </li>
        <?php endif; ?> 
        
        <?php if($wiki->getLanguage()): ?>
        <li>语言：     
                <?php echo $wiki->getLanguage()?>           
        </li>
        <?php endif; ?> 
        <?php if($wiki->getHtmlCache()): ?>
        <li>介绍：      
            <?php echo $wiki->getHtmlCache(100, ESC_RAW); ?>         
        </li>
        <?php endif; ?>                                                                                                            
<?php elseif($model=='telplay'):  //电视剧?>

        <?php if($wiki->getReleased()): ?>
        <li>上映时间:     
                <?php echo $wiki->getReleased()?>                 
        </li>
        <?php endif; ?> 
        
        <?php if($wiki->getEpisodes()): ?>
        <li>集数:      
                <?php echo $wiki->getEpisodes()?>           
        </strong><span>集数</span></li>
        <?php endif; ?>  
                    
        <?php if($Directors = $wiki->getDirector()): $i = 0 ?>  
        <li>导演:           
        <?php foreach($Directors as $Director) : $i++;?>
        <?php echo ($i > 1) ? ' /' : ''; echo $Director;?>
        <?php endforeach;?>                
        </li>
        <?php endif; ?>     
        
        <?php if($Writers = $wiki->getWriter()): $i = 0 ?>    
        <li>编剧:
        <?php foreach($Writers as $Writer) : $i++;?>
        <?php echo ($i > 1) ? ' /' : ''; echo $Writer;?>
        <?php endforeach;?>                  
        </li>
        <?php endif; ?>  
          
        <?php if($Stars = $wiki->getStarring()): $i = 0 ?>    
        <li>主演:     
        <?php foreach($Stars as $Star) : $i++;
              if($i<6):
        ?>
        <?php echo ($i > 1) ? ' /' : ''; echo $Star;?>
        <?php 
              endif;
              endforeach;?>                           
        </li>  
        <?php endif; ?>  
        
        <?php if($wiki->getCountry()): ?>
        <li>国家:      
                <?php echo $wiki->getCountry()?>                   
        </li>
        <?php endif; ?> 
        
        <?php if($wiki->getLanguage()): ?>
        <li>语言:      
                <?php echo $wiki->getLanguage()?>           
        </li>
        <?php endif; ?>    
        
        <?php if($Distributors = $wiki->getDistributor()): $i=0 ?>
        <li>出品公司:      
                <?php foreach($Distributors as $Distributor) : $i++;?>
                <?php echo ($i > 1) ? ' /' : ''; echo $Distributor;?>
                <?php endforeach;?>                   
        </li>
        <?php endif; ?> 
        
        <?php if($wiki->getProduced()): ?>	
        <li>制作日期:     
                <?php echo $wiki->getProduced()?>       
        </li>
        <?php endif; ?>   

        <?php if($wiki->getHtmlCache()): ?>	
        <li>简介:     
                <?php echo $wiki->getHtmlCache(100, ESC_RAW);?>       
        </li>
        <?php endif; ?>                                                 

<?php else:  //电影?>    

        <?php if($wiki->getReleased()): ?>
        <li>上映时间:    
                <?php echo $wiki->getReleased()?>          
        </li>
        <?php endif; ?> 

        <?php if($wiki->getRuntime()): ?>
        <li>片长:      
                <?php echo $wiki->getRuntime()?>分钟            
        </li>
        <?php endif; ?> 
                                    
        <?php if($Directors = $wiki->getDirector()): $i = 0 ?>  
        <li>导演:           
        <?php foreach($Directors as $Director) : $i++;?>
        <?php echo ($i > 1) ? ' /' : ''; echo $Director;?>
        <?php endforeach;?>                
        </li>
        <?php endif; ?>     
        
        <?php if($Writers = $wiki->getWriter()): $i = 0 ?>    
        <li>编剧:
        <?php foreach($Writers as $Writer) : $i++;?>
        <?php echo ($i > 1) ? ' /' : ''; echo $Writer;?>
        <?php endforeach;?>                  
        </li>
        <?php endif; ?>  
          
        <?php if($Stars = $wiki->getStarring()): $i = 0 ?>    
        <li>主演:       
        <?php foreach($Stars as $Star) : $i++;
              if($i<6):
        ?>
        <?php echo ($i > 1) ? ' /' : ''; echo $Star;?>
        <?php 
              endif;
              endforeach;?>                           
        </li>  
        <?php endif; ?>  
        
        <?php if($wiki->getCountry()): ?>
        <li>国家:     
                <?php echo $wiki->getCountry()?>                   
        </li>
        <?php endif; ?>         
        <?php if($Distributors = $wiki->getDistributor()): $i=0 ?>
        <li>出品公司:      
                <?php foreach($Distributors as $Distributor) : $i++;?>
                <?php echo ($i > 1) ? ' /' : ''; echo $Distributor;?>
                <?php endforeach;?>                   
        </li>
        <?php endif; ?> 
        
        <?php if($wiki->getProduced()): ?>	
        <li>制作日期:      
                <?php echo $wiki->getProduced()?>       
        </li>
        <?php endif; ?>    
        
        <?php if($wiki->getHtmlCache()): ?>	
        <li>简介:  
                <?php echo $wiki->getHtmlCache(100, ESC_RAW)?>       
        </li>
        <?php endif; ?>                                                

<?php endif;?>
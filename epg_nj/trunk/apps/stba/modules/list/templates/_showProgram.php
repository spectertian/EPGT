<?php sfContext::getInstance()->getConfiguration()->loadHelpers('GetFileUrl');?>
<?php $i=0;?>
<?php $k=0;?>
<?php if($tag!='vod'):?>
    <!--直播4个-->
    <?php $n=0;?>
    <?php foreach ($programList as $program):?>
    <?php $wikia=$program->getWiki();?>
    <?php $spService=$program->getSpService();?>
    <?php $spLogo=thumb_url($spService->getChannelLogo(),65,68)?>
    <?php if(!$wikia||!$wikia->getCover()) continue;?>
    <?php if($i<4):?>
    <?php $all = strtotime($program->getEndTime()->format("Y-m-d H:i:s")) - strtotime($program->getTime());?>
    <?php $plan = time() - strtotime($program->getTime());?>
    <?php $width = round($plan/$all,2) * 100?>                 
    <?php if($wikia->getTitle()=='新闻联播'&&$n>0):
              continue;
          else:
    ?>
    <li <?php echo $i==3?"class='no4'":""?>>
       <a href="javascript:void(0);" onclick="goChannelByName('<?php echo $spService->getName();?>',2,'visible');currentProgram();showPlayPage();setLiveHit();" onmouseover="showPlay('<?php echo $program->getSpName();?>','<?php echo $wikia->getTitle();?>','<?php echo strtotime($program->getStartTime()->format("Y-m-d H:i:s"));?>','<?php echo strtotime($program->getEndTime()->format("Y-m-d H:i:s"));?>','<?php echo $spLogo;?>');">
       		<img src="<?php echo thumb_url($wikia->getCover(),125,167);?>" alt=""/>
            <span>
            	<em><?php echo $wikia->getTitle();?></em>
            </span>
            <b></b>
       </a> 
    </li>
    <?php     $i++;?>
    <?php endif;?>
    <?php if($wikia->getTitle()=='新闻联播'){$n++;}?>
    <?php endif;?>
    <?php endforeach;?>
    <!--点播不足4个补齐-->
    <?php for($m=$i;$m<4;$m++):?>
    <li <?php echo $m==3?"class='no4'":""?>>
    &nbsp;
    </li>
    <?php endfor;?>
    <!--点播4个-->
    <?php if($refer=='center'):?>
        <?php foreach($wikis as $wiki):?>
        <?php if($k>=4) break;?>
        <?php if($wiki['poster']=='') continue; else $k++;  //有图片才显示?>      
        <?php
              if(strpos($wiki['poster'],'morenhaibao.gif')){
                  $image='/images/'.$tag.$k.'.jpg';
              }else{
                  $image=$wiki['poster'];
              }
              $urls=explode('&amp;backurl=',$wiki['url']);
              $url = $urls[0]."&param2=bokong&param3=TopRating&backurl=".$urls[1];
              //$url = $wiki['url']."&param2=bokong&param3=TopRating";
        ?>    
        <li>
           <a  <?php echo $k==4?'id ="dianboend"':''?>  href="<?php echo $url;?>" onclick="hidPlay();"  onmouseover="showWiki('<?php echo $wiki['Title'];?>');">
           		<img src="<?php echo $image;?>" alt=""/>
                <span>
                	<em><?php echo $wiki['Title'];?></em>
                </span>
           </a> 
        </li>
        <?php endforeach;?>
    <?php elseif($refer=='tongzhou'):?>
        <?php foreach($wikis as $wiki):?>
        <?php if($k>=4) break;?>
        <?php if($wiki['poster']=='') continue; else $k++;  //有图片才显示?>          
        <li>
           <a  <?php echo $k==4?'id ="dianboend"':''?>  href="<?php echo $wiki['url'];?>" onclick="hidPlay();"  onmouseover="showWiki('<?php echo $wiki['Title'];?>');">
           		<img src="<?php echo $wiki['poster'];?>" alt=""/>
                <span>
                	<em><?php echo $wiki['Title'];?></em>
                </span>
           </a> 
        </li>
        <?php endforeach;?>
    <?php else:?>
        <?php foreach($wikis as $wiki):?>
        <?php if($k>=4) break;?>   
        <?php if(!$wiki) continue;?> 
        <?php if($wiki->getCover()=='') continue; else $k++;  //有图片才显示?>         
        <li>
           <a <?php echo $k==4?'id ="dianboend"':''?> href="<?php echo url_for('wiki/show?id='.$wiki->getId().'&refer=list') ?>" onclick="hidPlay();"  onmouseover="showWiki('<?php echo $wiki->getTitle();?>');">
           		<img src="<?php echo thumb_url($wiki->getCover(), 125, 167);?>" alt=""/>
                <span>
                	<em><?php echo $wiki->getTitle();?></em>
                </span>
           </a> 
        </li>
        <?php endforeach;?>
    <?php endif;?>

<?php else:?>
    <?php if($refer=='center'):?>
        <?php foreach($wikis as $wiki):?> 
        <?php if($i>=8) break;?>
        <?php if($wiki['poster']!=''):  //有图片才显示?>   
        <?php
              if(strpos($wiki['poster'],'morenhaibao.gif')){
                  //srand((double)microtime()*1000000);
                  //$rand_number= rand(1,4);
                  $image='/images/'.$tag.$i.'.jpg';
              }else{
                  $image=$wiki['poster'];
              }
              $urls=explode('&amp;backurl=',$wiki['url']);
              $url = $urls[0]."&param2=bokong&param3=Ranking&backurl=".$urls[1];
              //$url = $wiki['url']."&param2=bokong&param3=Ranking";
        ?>     
        <li <?php echo $i==3?"class='no4'":""?>>
           <a <?php echo $i==7?'id ="dianboend"':''?> href="<?php echo $url;?>" onclick="hidPlay();"  onmouseover="showWiki('<?php echo $wiki['Title'];?>');">
           		<img src="<?php echo $image;?>" alt=""/>
                <span>
                	<em><?php echo $wiki['Title'];?></em>
                </span>
           </a> 
        </li>
        <?php $i++;?>
        <?php endif;?>
        <?php endforeach;?>
    <?php elseif($refer=='tongzhou'):?>
        <?php foreach($wikis as $wiki):?> 
        <?php if($i>=8) break;?>
        <?php if($wiki['poster']!=''):  //有图片才显示?>      
        <li <?php echo $i==3?"class='no4'":""?>>
           <a <?php echo $i==7?'id ="dianboend"':''?> href="<?php echo $wiki['url'];?>" onclick="hidPlay();"  onmouseover="showWiki('<?php echo $wiki['Title'];?>');">
           		<img src="<?php echo $wiki['poster'];?>" alt=""/>
                <span>
                	<em><?php echo $wiki['Title'];?></em>
                </span>
           </a> 
        </li>
        <?php $i++;?>
        <?php endif;?>
        <?php endforeach;?>
    <?php else:?>
        <?php foreach($wikis as $wiki):?>   
        <?php if($i>=8) break;?> 
        <?php if(!$wiki) continue;?> 
        <?php if($wiki->getCover()!=''):  //有图片才显示?>      
        <li <?php echo $i==3?"class='no4'":""?>>
           <a  <?php echo $i==7?'id ="dianboend"':''?> href="<?php echo url_for('wiki/show?id='.$wiki->getId().'&refer=list') ?>" onclick="hidPlay();"  onmouseover="showWiki('<?php echo $wiki->getTitle();?>');">
           		<img src="<?php echo thumb_url($wiki->getCover(), 125, 167);?>" alt=""/>
                <span>
                	<em><?php echo $wiki->getTitle();?></em>
                </span>
           </a> 
        </li>
        <?php $i++;?>
        <?php endif;?>
        <?php endforeach;?>
    <?php endif;?>
<?php endif;?>
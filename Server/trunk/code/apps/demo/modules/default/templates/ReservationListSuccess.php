 <div class="play_now clr">
        	<ul class="item_list">
 
                      <?php
            $tmps = array();
            $times = 24 * 60 * 60; 
            //$channel_id = $sf_user->getAttribute('channel_id'); //debug
            $channel_id=1;
           // $current_date = $sf_request->getParameter('date',( $sf_user->getAttribute('date') ? $sf_user->getAttribute('date') :date("Y-m-d",time()))) ;
           $current_date =  date("Y-m-d",time());
            $w = date('w', strtotime($current_date));
               
            if ($w == 0) $w = 7;
            $s = strtotime($current_date);
            $weeks = array('上一周', '(周一)', '(周二)', '(周三)', '(周四)', '(周五)', '(周六)', '(周日)', '下一周');
        
            for ($i = 1; $i < 8; ++$i) {
                if ($i == 0) {
                    $n = $s - (6 + $w) * $times;
                } else if ($i == $w) {
                    $n = $s;
                } else {
                    $n = $s + ($i - $w) * $times;
                }
               $tmps[$i] = date('Y-m-d', $n);
             /**
                echo sprintf('<li><a href="%s" class="%s" link=\'{"channel_id":%s,"date":"%s"}\'>%s</a></li>',
                        url_for('@program').'?channel_id=' . $channel_id . '&date=' . $tmps[$i],  $current_date == $tmps[$i] ? 'active' : '', $channel_id, $tmps[$i], (($i != 0 && $i != 8 ) ? $tmps[$i] : '') . $weeks[$i]);
             */
 
              if($tmps[$i]==$_GET['date']){
            
                            echo '<li class="this"><a href="'.url_for('/default/ReservationList').'?date='.$tmps[$i] .'">'.$tmps[$i].'</a></li>';
              }else{
         
                            echo '<li ><a href="'.url_for('/default/ReservationList').'?date='.$tmps[$i] .'">'.$tmps[$i].'</a></li>';
              }

 

            }
                 
      ?>
            </ul>
            
            <div class="list_bg">
                <ul class="epg_list">


                  <?php foreach ($programusers as $program):?>
                     <li>
                       
                            <span class="time"><?php echo $program->getStartTime()->format("H:i");?></span>
                            <span class="name">
                             <?php  $wikiid= $program->getWikiId(); ?>
                             <a href="<?php echo url_for('/default/show').'?wiki_id='.$wikiid ;?>"> <?php echo $program->name;?></a>
                            </span>
                            <span class="ctrl">取消</span>
                        
                    </li>
                  <?php endforeach;?>

             

 



                </ul>
                
                <div class="tv_info">
                    <img src="" alt=""/>
                    <ul>
                        <li><h2>节目名称</h2></li>
                        <li><span>演员名称一</span><span>演员名称二</span><span>演员名称三</span><span>演员名称四</span><span>演员名称五</span></li>
                        <li>故事情节简介故事情节简介故事情节简介故事情节简介故事情节简介故事情节简介故事情节简介故事情节简介故事情节简介故事情节简介故事情节简介故事情节简介故事情节简介故事情节简介故事情节简介故事情节简介故事情节简介</li>
                        <li class="more"><a href="#">查看详情</a></li>
                    </ul>
                </div>
            
            </div> 
            
        </div>
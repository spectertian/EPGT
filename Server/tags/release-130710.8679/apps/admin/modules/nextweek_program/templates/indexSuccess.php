<style>
.pre_{
	border:1px solid;border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
	border-bottom-left-radius:4px;border-top-left-radius:4px;float;left;background-image:linear-gradient(to bottom, #FFFFFF, #E6E6E6);
	padding:0.6em;
	height:1.9em;
	line-height:1.9em;
	cursor: pointer;
	margin-right:-4px;
}
.pre__{
	font-family: "Courier New",Courier,monospace;
    font-size: 2em;
    margin: 0 0.1em;
    vertical-align: baseline;
    font-weight:bold;
    color:#999999;
}
.next_{
	border:1px solid;border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
	border-bottom-right-radius:4px;border-top-right-radius:4px;float;left;background-image:linear-gradient(to bottom, #FFFFFF, #E6E6E6);
	padding:0.6em;
	height:1.9em;
	line-height:1.9em;
	cursor: pointer;
	margin-right:0px;
}
.next__{
	font-family: "Courier New",Courier,monospace;
    font-size: 2em;
    margin: 0 0.1em;
    vertical-align: baseline;
    font-weight:bold;
    color:#999999;
}

.today{
	border-bottom-left-radius:4px;border-top-left-radius:4px;
	border-bottom-right-radius:4px;border-top-right-radius:4px;float;left;background-image:linear-gradient(to bottom, #FFFFFF, #E6E6E6);
	border:1px solid;border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
	padding:0.6em;
	height:1.9em;
	line-height:1.9em;
	cursor: pointer;
}
.today__{
	font-family: "Courier New",Courier,monospace;
    font-size: 1.5em;
    margin: 0 0.1em;
    vertical-align: baseline;
    color:#999999;
}

</style>
<script>
$(function(){
	$('.pre_').mouseover(function(){
		$(".pre__").css('color','green');
		
	});
	$('.pre_').mouseout(function(){
		$(".pre__").css('color','#999999');
	});

	$('.next_').mouseover(function(){
		$(".next__").css('color','green');
	});
	$('.next_').mouseout(function(){
		$(".next__").css('color','#999999');
	});

	$('.pre_').click(function(){
		location.href='/nextweek_program?date=<?php echo $pre; ?>';
	});
	$('.next_').click(function(){
		location.href='/nextweek_program?date=<?php echo $next; ?>';
	});
	$('.today').click(function(){
		location.href='/nextweek_program?date=<?php echo date('Y-m-d',time()); ?>';
	});
})
</script>
<div id="content">
      <div class="content_inner">
        <header>
          <h2 class="content">下周预告日历</h2>
        </header>
      </div>
      <div class='widget'>
          <p style='padding:20px;'>
	           <span style='margin-left:20px;font-size:15px;font-weight:bold;font-family: georgia,"times new roman",serif;
font-size: 28px;line-height:24px'><?php echo $currentdate; ?></span>
	           <span style='float:right'>
    	           <span class='today'><span class='today__'<?php if ($currentdate==date('Y-m',time())) echo 'style="color:green"'?>>today</span></span>
    	           <span class='pre_'><span class='pre__'>‹</span></span>
    	           <span class='next_'><span class='next__'>›</span></span>
	           </span>
	      </p>
	  </div>
    <table cellspacing="0" id='day'>
    
      <tr>
          <th>日</th>
          <th>一</th>
          <th>二</th>
          <th>三</th>
          <th>四</th>
          <th>五</th>
          <th>六</th>
      </tr>
    </table>
      <table cellspacing="0">
      <tr>
          
      </tr>
      <tr style="display: table-row;vertical-align: inherit;border-color: inherit;">
          <?php 
            foreach($week as $k=>$v){
                echo '<td style="text-align:center;color:#ddd;width:auto;border: 1px solid #ddd;vertical-align: top;">'.$v[0].'</td>';
            }
          
          ?>
          <?php foreach ($yProgram as $k=>$v): ?>
          <td style="text-align:center;width:auto;border: 1px solid #ddd;vertical-align: top;">
              <a href='/nextweek_program/list?date=<?php echo $v[2]; ?>' style='display:block; width:100%; height:100%;'>
                  <font style='<?php if($v[0]) echo 'color:orange;font-size: 18px;font-weight: bold;' ?>'><?php echo $k; ?></font>
              </a>
          
          </td>
          <?php if($v[1]==6)echo '</tr><tr style="display: table-row;vertical-align: inherit;border-color: inherit;">' ?>
          <?php endforeach; ?>
      <tr>
      
      </table>
</div>

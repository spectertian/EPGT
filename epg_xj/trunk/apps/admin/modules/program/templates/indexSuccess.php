      <div id="content">
        <div class="content_inner">
            <?php include_partial("toobal") ?>
            <?php include_partial('global/flashes') ?>
            <?php include_partial('weeks'); ?>
            <div class="table_nav">
            <?php include_partial('search',array( 'topTvStations'=>$parentTvStations,'channels'=>$channels,'channel_code'=>$channel_code,'update'=>$update ,'updatetime'=>$updatetime));?>
            <?php include_partial("list",array("programs"=>$programs,'channel'=>$channel)); ?>
            <?php include_partial("addYesterdayNextdayProgaram",array('style'=>$style)); ?>
			</div>
        <div class="padib"><?php include_partial("foottoobal") ?></div>
      </div>
      <style type="text/css">
        #tinybox{position:absolute; display:none; padding:10px; background:#ffffff url(http://admin.5i.test.cedock.net/images/loadingAnimation.gif) no-repeat 50% 50%; border:10px solid #e3e3e3; z-index:2000;}
		#tinybox li{list-style-type: none; float: left;width: 20%;height: 18px;}
        #tinymask{position:absolute; display:none; top:0; left:0; height:100%; width:100%; background:#000000; z-index:1500;}
		#tinycontent{background:#ffffff; font-size:1.1em;}
      </style>
      <script type="text/javascript">
		
      //changeNotice();
      //setInterval("changeNotice()",1000*60*5);

	  function changeNotice(){

		  var cookieArr = document.cookie.split('; ');
		  var hasView,arr;
		  var str = '';
		  for(var i=0; i<cookieArr.length ;i++){
			  arr = cookieArr[i].split('=');
			  if('hasview' == arr[0]){
				  hasView = arr[1];
		      }
		  }
		  
		  if(hasView!=1){
			  $.ajax({
			        url: "program/programNotice",
			        dataType: "json",
			        success:function(data)
			        {
			        	str += '<ul>';
			        	for ( var i = 0; i < data.length; i++) {
				        	if(data[i].name){
			        			str += '<li><a href="/program?channel_id='+data[i].id+'&setcookie=1">'+data[i].name+'</a></li> ';
				        	}
						}
			        	str+="</ul>已有更新内容";
			  			TINY.box.show(str,0,0,0,3);
			        }
			    });
		  }
		  
		  
	  }
      </script>
      
       
      
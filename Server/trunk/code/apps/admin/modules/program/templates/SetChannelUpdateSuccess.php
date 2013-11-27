<?php use_helper('Date');?>
<script>
function submitform(action){
    if (action) {
        document.adminForm.batch_action.value=action;
    }
    if (typeof document.adminForm.onsubmit == "function") {
        document.adminForm.onsubmit();
    }
    document.adminForm.submit();
}

function checkall(){
	$('.checkall').each(function(){
		var state = $(this).attr('checked');
		//alert(state);return false;
		if(state==true){
			$(this).attr('checked',false);
			//continue;
		}
		if(state==false){
			$(this).attr('checked',true);
			//continue;
		}
	})
}
</script>
<div id="content">
        <div class="content_inner">
            <header class="toolbar">
              <h2 class="content">tvsou监控设置</h2>
              <nav class="utility">
            	<li class="save"><a href="###" onclick="javascript:submitform()">保存设置</a></li>
            	<li class="back"><a href="<?php echo url_for('program/channelUpdate')?>">监控页面</a></li>
              </nav>
            </header>
            <?php include_partial('global/flashes') ?>
            
            <table cellspacing="0">
              <thead>
                <tr>
                  <th scope="col" style="width: 25%;"><input type='checkbox' name='checkall' id='checkall_' value='' onclick='checkall();'>反选</th>
                  <th scope="col" style="width: 25%;"></th>
                  <th scope="col" style="width: 25%;"></th>
                  <th scope="col" style="width: 25%;"></th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col" style="width: 25%;"><input type='checkbox' name='checkall' id='checkall_' value='' onclick='checkall();'>反选</th>
                  <th scope="col" style="width: 25%;"></th>
                  <th scope="col" style="width: 25%;"></th>
                  <th scope="col" style="width: 25%;"></th>
                </tr>
              </tfoot>
              <tbody>
              <form action="<?php echo url_for("program/SetChannelUpdate")?>" id="adminForm" name="adminForm" method="post" >
                  <tr>
                  <?php
                      $setid = array();
                      foreach($sf_user->getAttribute('setid') as $k=>$v){
                          $setid[$k] = $v;
                      }
                  ?>
                  
                  <?php $i=0; ?>
                    <?php foreach ($channel_list as $channel):?>
                      <td style='text-align:left;padding-left:20px'><input class='checkall' <?php if(in_array($channel['id'], $setid)): ?> checked='checked' <?php endif; ?>type='checkbox' name='id[]' value='<?php echo $channel['id'] ?>'><?php echo $channel['name']?></td>
                      <?php $i++; ?>
                      <?php if($i%4==0): ?>
                      </tr>
                      <tr>
                      <?php endif; ?>
                    <?php endforeach;?>
                   </tr>
              </form>
              </tbody>
            </table>    
            <div class="clear"></div>
          
        </div>
      </div>
<script type="text/javascript">
    $(document).ready(function () {
       setInterval("reload()",120000);
    });
    function reload()
    {
       location.href='<?php echo url_for("program/channelUpdate") ?>';
       //window.location.reload();
    }
</script>      
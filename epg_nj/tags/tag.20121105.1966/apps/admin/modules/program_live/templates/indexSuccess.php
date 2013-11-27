<script type="text/javascript">
ajax_cctv();ajax_tv();
setInterval("ajax_cctv()",1000*60);
setInterval("ajax_tv()",1000*60);
function ajax_cctv()
{
	$('#load_cctv').html("<img src='/images/throbber.gif' />");
    $.ajax({
        url: "program_live/CCTV",
        success:function(data)
        {
            $('#cctv').prepend(data);
        },error:function()
        {
        }
    });
}
function ajax_tv()
{
    $.ajax({
        url: "program_live/TV",
        success:function(data)
        {
            $('#tv').prepend(data);
        },error:function()
        {
        }
    });
}
</script>
      <div id="content">
        <div class="content_inner">
            <?php //include_partial("toobal") ?>
            <?php //include_partial('global/flashes') ?>
            <?php //include_partial('weeks'); ?>
            <div class="table_nav">
            <?php //include_partial('search',array( 'channel'=>$channel,'start_time'=>$start_time,'end_time'=>$end_time));?>
            <?php //include_partial("list",array("pager"=>$pager,'channel'=>$channel,'start_time'=>$start_time,'end_time'=>$end_time)); ?>
            
            
            
            <div style="float:left;width:50%" >
            <h3>cctv:</h3><span id="load_cctv"></span>
            <table cellspacing="0" class="adminlist" id="admin_list">
              <thead>
                <tr>
                  <th scope="col"  name="name" style="width: 30em">名称</th>
                  <th scope="col"  name="channel_id">频道</th>
                  <th scope="col"  name="time" >播放时间</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col">名称</th>
                  <th scope="col">频道</th>
                  <th scope="col">播放时间</th>
                </tr>
              </tfoot>
              <tbody id="cctv">

              </tbody>
            </table>           
            </div>
            
            
            
            
            <div style="float:right;width:50%" >
            <h3>tv:</h3><span id="load_tv"></span>
            <table cellspacing="0" class="adminlist" id="admin_list">
              <thead>
                <tr>
                  <th scope="col"  name="name" style="width: 30em">名称</th>
                  <th scope="col"  name="channel_id">频道</th>
                  <th scope="col"  name="time" >播放时间</th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <th scope="col">名称</th>
                  <th scope="col">频道</th>
                  <th scope="col">播放时间</th>
                </tr>
              </tfoot>
              <tbody id="tv">

              </tbody>
            </table>            
            </div>
        </div>
      
      
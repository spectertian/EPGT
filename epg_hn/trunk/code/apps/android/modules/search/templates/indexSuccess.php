<script type="text/javascript">
function setSearchMenu(m) {
    document.searchForm.m.value=m;
    var tab = document.getElementById('toptab').getElementsByTagName("li");
    for(var i=0; i< tab.length; i++) {
        tab[i].className='';
    }
    if (m == 'channel'){
        tab[0].className='act';
    }else{
        tab[1].className='act';
    }
}
</script>
<div class="toptab3" id="toptab">
    <ul>
        <li <?php echo ('channel' == $m) ? 'class="act"' : ''?> onclick="setSearchMenu('channel')">搜频道</li>
        <li <?php echo ('program' == $m) ? 'class="act"' : ''?> onclick="setSearchMenu('program')">搜节目</li>
    </ul>
<div class="clear"></div>

<div class="search">
  <form name="searchForm" method="get" action="<?php echo url_for('search/index')?>">
      <input name="m" type="hidden" value="<?php echo isset($m) ? $m : ''?>">
      <input name="q" type="text" value="<?php echo isset($q) ? $q : ''?>">
      <input type="submit" value="搜索">
  </form>
</div>
</div>

<div id="wrapper">
<div class="row-container epg08" id="scroller">
<?php
if(!is_null($results)) {
    if ('channel' == $m)
       include_partial('channelResult', array('results' => $results));
    else
        include_partial('wikiResult', array('results' => $results));
}
?>
</div>
</div>
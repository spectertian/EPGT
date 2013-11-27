<script type="text/javascript">
    function initPage() { 
        <?php if($page==2):?>
    	//publicInit();
        //playVideoReturn();
        showTip('智能导航维护中......');
        setTimeout(showPlayPage,2000);
        <?php else:?>
        showPlayPage();
        <?php endif;?>
    }
    function eventHandler(evt){
    	var evtcode = evt.which ? evt.which : evt.code;
    	switch (evtcode) {			
    		case 36:    //"HOME键"
            case 3864:  //"KEY_LIANXIANG"
            case 0x31:  //1
    			showPlayPage();
    			break;
            case 0x72:  //退出
                showPlayPage();
                break;
    	}	
    }
</script>
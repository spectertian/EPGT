<?php echo (strlen($channel->getLogo()) <= 1) ? '无' : '<a href="#" id="logo_photo">有</a>' ?>
<div style="position:relative; display: none;">
    <div style="position:absolute;width:200px;height:100px;z-index: 9999;">
        <img src="<?php echo file_url($channel->getLogo()) ?>" />
    </div>
</div>
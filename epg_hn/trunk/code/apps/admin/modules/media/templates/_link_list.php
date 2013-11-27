<?php use_helper('Text') ?>
<fieldset id="folderview">
    <legend>文件列表</legend>
    <div class="view" style="height:320px;overflow:scroll;overflow-x:auto;" >
            <div class="manager">
                <?php foreach ($pager->getResults() as $attachment): ?>
                <div class="imgOutline" style="margin-left:10px;">
                    <div class="imgTotal">
                        <div align="center" class="imgBorder">
                            <a style="display: block; width: 100%; height: 100%;" title="<?php echo $attachment->getSourceName() ?>" href="#" onclick="return false;" class="img-preview <?php //if($attachment->IsImage()){ echo 'thickbox'; } ?>">
                                <div class="image">
                                        <img border="0" alt="cancel.png - 564 bytes" src="<?php echo $attachment->getFileThumbNail(); ?>">
                                </div>
                            </a>
                            <div id="show_file_info" style="display:none;" rel="0">
                                <span><?php echo $attachment->getFileUrl() ?></span>
                                <span><?php echo $attachment->getFileName() ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="controls"></div>
                    <div class="imginfoBorder">
                        <a href="#" onclick="return false;" title="<?php echo $attachment->getSourceName() ?>" >
                          <?php echo truncate_text($attachment->getSourceName(),18)  ?>
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <div id="file_info" style="display:none;">
            <span>0</span>
            <span>0</span>
        </div>
    </div>
</fieldset>

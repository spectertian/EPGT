<script type="text/javascript">
</script>
<div id="details">
    <div class="inner-body">
      <div class="info">
        <img src="<?php echo file_url($wiki->getStills()); ?>" alt="加载中"/>
        <div class="inner-title"><?php echo $wiki->getTitle()?> </div>
            <?php echo mb_substr(htmlspecialchars_decode($wiki->getContent()), 0, 200, 'utf-8'); ?>
        <div class="producer">导演：<?php echo trim($wiki->getDirector()); ?></div>
        <div class="actors">主演：<?php echo trim($wiki->getStarring()); ?></div>
        <div class="cats">类型：<?php echo trim(str_replace(',', ' / ', $wiki->getTags()));?></div>
        <div class="clear-both"></div>
      </div>
      <div class="clear-both"></div>
      <?php $i = 1 ;?>
      <?php foreach ($wiki->getScreenshotAll() as $rs) {?>
      <?php $i++;?>
      <?php if($i>5){break;}?>
        <img class="show_pic" src="<?php echo file_url($rs->getWikiValue()); ?>" alt="加载中"/>
      <?php }?>
      <div class="utility">
        <div class="action play hover">播放片库</div>
        <div class="action remind">预订提醒<!--取消提醒--></div>
        <div class="action recommend">&nbsp;分享它<!--&nbsp;已分享--></div>
      </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        var src = $(".show_pic");
        var len = src.length -1 ;
        src.eq(len).css('margin', 0);
    });
    
    $(function(){
            var nav_1 = new $.Remote({
                elements : {
                    block : $('#details'),
                    nodes : $('.utility .action'),
                    container : $('.utility')
                },
                viewer : {
                    rowHeight   : 48
                },
                isDefault : true,
                customRemote : {
                    right : function(){
                        this._down();
                        //this.changeBlock(content);
                        return true;
                    },
                    left : function(){
                        this._up();
                        //this.changeBlock(content);
                        var index   = this.viewer.hoverIndex -1;
                        return true;
                    },
                    up : function(){
                        return true;
                    },
                    down : function(){
                        return true;
                    },
                    bind : function(){
                        this._bind();
                        var scope = this;
                        this.elements.container.bind(this.uuid + '.keydown', function(event, docEvent){
                            var evt = scope.keyCode = docEvent;
                            if(evt.keyCode == $.keyCode.DELETE){
                                $("#page").show();
                                $("#details").hide();
                                scope.changeBlock($.epg.attrs.right);
                                $("#details").remove();
                            }
                        });
                        return true;
                    },
                    ok:function(){

                    }
                }
            });
        });
</script>

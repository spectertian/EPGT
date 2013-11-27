<ul>
<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
if(!empty($tv)){ foreach ($tv as $rs) {?>
<li class="action" id="channel_id_<?php echo $rs->getId();?>"><span><?php echo $rs->getName();?></span></li>
<?php }
}
?>
</ul>

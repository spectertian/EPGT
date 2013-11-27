	  <div id="content">
        <div class="content_inner">
			<header>
				<h2 class="content">敏感词导入</h2>
			</header>
			<?php include_partial('global/flashes')?>
            <form method="POST" id="wordForm" name="wordForm" action="<?php echo url_for("words/import");?>"  enctype="multipart/form-data">
            <div style="float:left; width:65%;">
              <div class="widget">
                <h3>请选择要导入的敏感词文件（txt格式，每行一个）：</h3>
				<div class="widget-body">
				  <ul class="wiki-meta">
					 <li>
                            文本文件的编码请设为utf8格式，否则无法导入！<br />&nbsp;<br />
					 		<input type="file" name="wordfile" id="wordfile"/><br />&nbsp;<br />
                            <input type="submit" value="确定导入"/> 
					 </li>
                  </ul>
				  <ul id="right">
                  </ul>
				</div>
              </div>
            </div> 
			</form>
            <div style="width:33%; float:right;">
              <div class="widget">
                <h3>辅助函数</h3>                
              </div>
            </div>   
        </div>
      </div>

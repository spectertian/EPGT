                    <div id="toolbar" class="toolbar">
                        <table class="toolbar">
                            <tbody>
                                <tr>

                                    <td id="toolbar-publish" class="button">
                                        <a class="toolbar" onclick="javascript:submitform()" href="#">
                                            <span title="保存" class="icon-32-save"></span>
                                            保存    </a>
                                    </td>
                                    <td class="button">
                                        <a href="<?php echo url_for('video/index');?>"><span class="icon-32-cancel"></span>
                                            返回列表</a></td>
                                    <td class="button">
                                        <a href="<?php echo url_for('video/delete?id='.$id); ?>" onclick="if (confirm('确认删除吗？')) { var f = document.createElement('form'); f.style.display = 'none'; this.parentNode.appendChild(f); f.method = 'post'; f.action = this.href;var m = document.createElement('input'); m.setAttribute('type', 'hidden'); m.setAttribute('name', 'sf_method'); m.setAttribute('value', 'delete'); f.appendChild(m);var m = document.createElement('input'); m.setAttribute('type', 'hidden'); m.setAttribute('name', '_csrf_token'); m.setAttribute('value', '7ae5f9bb4952382f3637ea68bfafe589'); f.appendChild(m);f.submit(); };return false;"><span class="icon-32-delete"></span>
                                            删除</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

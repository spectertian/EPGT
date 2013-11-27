(function($){
    var base_url = window.location.href;
    var program = 'program_template';
    var program_index = base_url.search(program);
    var url = base_url.substr(0, program.length + program_index);

    var Grid = window.Grid = function(){};
    $.extend(Grid, {
        templates : {
            textInput : '<input type="text" class="editor-text"/>',
            timeInput : '<input type="text" class="editor-text" maxlength="5" />',
            boolInput : '<input type="checkbox" class="editor-text" />',
            imgUnCheck: '<img src="/mozitek/epg/web_admin/images/icon/publish_x.png" title="UnChecked" alt="Unhecked"/>',
            imgCheck  : '<img src="/mozitek/epg/web_admin/images/icon/publish_g.png" title="Checked" alt="Checked"/>',
            programTr : '<tr class="">'+
                '<td>{id}</td>'+
                '<td><input type="checkbox" class="sf_admin_batch_checkbox" value="{id}" name="ids[]"/></td>'+
                '<td class="sf_admin_text sf_admin_list_td_id"><a href="/mozitek/epg/web_admin/admin_dev.php/program/{id}/edit">{id}</a></td>'+
                '<td class="sf_admin_text sf_admin_list_td_name"></td>'+
                '<td class="sf_admin_text sf_admin_list_td_channel">{channel_id}</td>'+
                '<td class="sf_admin_boolean sf_admin_list_td_publish"><img src="/mozitek/epg/web_admin/images/icon/publish_x.png" title="UnChecked" alt="Unhecked"/></td>'+
                '<td class="sf_admin_date sf_admin_list_td_time"></td>'+
                '<td class="sf_admin_date sf_admin_list_td_date">{date}</td>'+
                '<td class="sf_admin_text sf_admin_list_td_wiki"><a href="#"></a></td>'+
                '<td class="sf_admin_text sf_admin_list_td_tags"></td>'+
                '</tr>'
        },

        editor : {
            
            textCellEditor : function(args){
                var $input;
                var scope = this;

                this.init = function(){
                    if(args.container.find('input').length) return;
                    var v = $.trim(args.container.text());
                    args.container.data('defaultValue', v)
                    if(!args.container.data('id')){
                        var id = args.container.parent().find('.sf_admin_list_td_id a').text();
                        args.container.data('id', id);
                    }
                    args.container.html('');
                    $input = $(Grid.templates.textInput)
                    .val(v)
                    .appendTo(args.container)
                    .focus()
                    .select();

                    this.blur();
                };

                this.destory = function(){
                    $input.remove();
                };

                this.focus = function(){
                    $input.focus();
                };

                this.blur = function(){
                    $input.blur(function(){
                        var isChange = scope.isValueChanged();
                        var v = scope.serializeValue();
                        scope.destory();
                        args.container.text(v);
                        if(isChange){
                            var id = args.container.data('id');
                            var className = args.container.attr('class');
                            var names = className.match(/(td_([\w]+))$/);
                            Grid.ajaxEdit(id, names[2], v, function(){
                                args.container.data('defaultValue', v);
                            });
                        }
                    });
                };

                this.serializeValue = function(){
                    return $.trim($input.val());
                };

                this.isValueChanged = function(){
                    var v = scope.serializeValue();
                    var v2 = args.container.data('defaultValue');
                    return v != '' && v2 != null && v != v2;
                };

                this.init();
            },

            timeCellEditor  : function(args){
                var $input;
                var scope = this;
                var i = 0;

                this.init = function(){
                    if(args.container.find('input').length) return;
                    var v = $.trim(args.container.text());
                    args.container.data('defaultValue', v)
                    args.container.html('');
                    if(!args.container.data('id')){
                        var id = args.container.parent().find('.sf_admin_list_td_id a').text();
                        args.container.data('id', id);
                    }
                    $input = $(Grid.templates.timeInput)
                    .val(v)
                    .appendTo(args.container)
                    .setSelectRange(0, 1);

                    this.keydown();
                    this.blur();
                    this.click();
                };

                this.destory = function(){
                    $input.remove();
                };

                this.focus = function(){
                    $input.focus();
                };

                this.blur = function(){
                    $input.blur(function(evt){
                        var isChange = scope.isValueChanged();
                        var v = scope.serializeValue();
                        scope.destory();
                        args.container.text(v);
                        if(isChange){
                            var id = args.container.data('id');
                            var className = args.container.attr('class');
                            var names = className.match(/(td_([\w]+))$/);
                            Grid.ajaxEdit(id, names[2], v, function(){
                                args.container.data('defaultValue', v);
                            });
                        }
                    });
                };

                this.click = function(){
                    $input.click(function(){
                        $input.setSelectRange(0, 1);
                    });
                };

                this.keydown = function(){
                    $input.keydown(function(evt){
                        if(evt.keyCode == 8){
                            i = 0;
                            $input.setSelectRange(0, 1);
                            return false;
                        }
                        if(evt.keyCode < 48 || evt.keyCode > 57){
                            return false;
                        }

                    });

                    $input.keyup(function(evt){
                        if(evt.keyCode == 8){
                            i = 0;
                            $input.setSelectRange(0, 1);
                            return false;
                        }
                        i++;
                        var p = $input.getSelectRange();
                        $input.setSelectRange(i, i+1);
                        if(p == 2) $input.setSelectRange(++i, i+1);
                    });
                };

                this.serializeValue = function(){
                    return $.trim($input.val());
                };

                this.isValueChanged = function(){
                    var v = scope.serializeValue();
                    var v2 = args.container.data('defaultValue');
                    return v != '' && v2 != null && v != v2;
                };

                this.init();
            },

            boolCellEditor : function(args){
                var $input;
                var scope = this;

                this.init = function(){
                    if(args.container.find('input').length) return;
                    var v = args.container.find('img').attr('alt') == 'Checked' ? 1 : 0;
                    
                    args.container.data('defaultValue', v)
                    args.container.html('');
                    if(!args.container.data('id')){
                        var id = args.container.parent().find('.sf_admin_list_td_id a').text();
                        args.container.data('id', id);
                    }
                    $input = $(Grid.templates.boolInput)
                    .attr('checked', Boolean(v))
                    .appendTo(args.container)
                    .focus();

                    this.blur();
                };

                this.destory = function(){
                    $input.remove();
                };

                this.isValueChanged = function(){
                    var v = Number(scope.serializeValue());
                    var v2 = args.container.data('defaultValue');
                    return v != v2;
                };

                this.serializeValue = function(){
                    return $input[0].checked;
                };

                this.blur = function(){
                    $input.blur(function(evt){
                        var isChange = scope.isValueChanged();
                        var v = scope.serializeValue();
                        scope.destory();
                        args.container.html(v ? Grid.templates.imgCheck : Grid.templates.imgUnCheck);
                        if(isChange){
                            var id = args.container.data('id');
                            var className = args.container.attr('class');
                            var names = className.match(/(td_([\w]+))$/);
                            Grid.ajaxEdit(id, names[2], Number(v), function(){
                                args.container.data('defaultValue', Number(v));
                            });
                        }
                    });
                };

                this.init();
            }
        }
    }, {
        ajaxEdit : function(id, name, value, fn){
            $.ajax({
                url: url + '/ajax_update',
                dataType: 'json',
                data: 'id=' + id + '&name=' + name + '&value=' + value,
                success: function(data){
                    if(data.code == 1){
                        fn();
                    }else{
                        alert(data.msg);
                    }
    
                }
            });
        }
    });

    $(function(){
        var td_types = {
            'text' : 'sf_admin_text',
            'boolean' : 'sf_admin_boolean',
            'date' : 'sf_admin_date'
        };
        
        var td_name = $('.sf_admin_list_td_name');
        var td_publish = $('.sf_admin_list_td_publish');
        var td_time = $('.sf_admin_list_td_time');

        td_name.click(function(){
            var name = new Grid.editor.textCellEditor({
                container: $(this)
            });
        });

        td_time.click(function(){
            var time = new Grid.editor.timeCellEditor({
                container: $(this)
            });
        });

        td_publish.click(function(){
            var publish = new Grid.editor.boolCellEditor({
                container: $(this)
            });
        });

        var batch_add = $('#batch_add');
        batch_add.click(function(){
            
        });
        
    });
    
    $.fn.setSelectRange = function(start, end){
        return this.each(function(){
            if(this.setSelectionRange){
                this.focus();
                this.setSelectionRange(start, end);
            }
            else if(this.createTextRange){
                var range = this.createTextRange();
                range.collapse(true);
                range.moveEnd('character', end);
                range.moveStart('character', start);
                range.select();
            }
        });
    };

    $.fn.getSelectRange = function(){
        var x = 0;
        this.each(function(){
            if(document.selection){
                this.focus ();
                var Sel = document.selection.createRange();
                Sel.moveStart ('character', -this.value.length);
                x = Sel.text.length;
            }else if(this.selectionStart || this.selectionStart == '0'){
                x = this.selectionStart;
            }
        });
        return x;
    };
})(jQuery);


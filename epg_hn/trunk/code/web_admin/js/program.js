(function($){
    var base_url = window.location.href;
    var program = 'program';
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
            programTr : '<tr class="{className}">'+
                '<td>{row_number}</td>'+
                '<td><input type="checkbox" class="sf_admin_batch_checkbox" value="{id}" name="ids[]"/></td>'+
                '<td class="sf_admin_text sf_admin_list_td_id"><a href="/mozitek/epg/web_admin/admin_dev.php/program/{id}/edit">{id}</a><input type="button" value="保存" class="tr_save" /><input type="button" value="取消" class="tr_cancel" /></td>'+
                '<td class="sf_admin_text sf_admin_list_td_name">{name}</td>'+
                '<td class="sf_admin_text sf_admin_list_td_channel" rel="{channel_id}">{channel}</td>'+
                '<td class="sf_admin_boolean sf_admin_list_td_publish"><img src="/mozitek/epg/web_admin/images/icon/publish_x.png" title="UnChecked" alt="Unhecked"/></td>'+
                '<td class="sf_admin_date sf_admin_list_td_time">00:00</td>'+
                '<td class="sf_admin_date sf_admin_list_td_date">{date}</td>'+
                '<td class="sf_admin_text sf_admin_list_td_wiki"><a href="#{wiki_id}">{wiki}</a></td>'+
                '<td class="sf_admin_text sf_admin_list_td_tags"></td>'+
                '</tr>',
            autoInput : '<input size="50" id="tags" class="ui-autocomplete-input" autocomplete="off" role="textbox" aria-autocomplete="list" aria-haspopup="true"/>'
        },

        editor : {
            addColumn : function(args){
                args.rel['row_number'] = ++Grid.editor.trsNumber;
                args.rel['className'] = Grid.editor.trsNumber%2 ? 'row0' : 'row1';
                var tr = $(Grid.templates.programTr.replace(/\{[\w\_]+\}/g, function(rep){
                    if (rep == '{id}') return rep;
                    var rep = rep.substr(1, rep.length-2);
                    return args.rel[rep];
                }));
                if(args.table.find('tbody tr:first').length){
                     tr.insertBefore(args.table.find('tbody tr:first'));
                }else{
                     tr.appendTo(args.table.find('tbody'));
                }
               
                tr.find('td.sf_admin_list_td_name').click();
            },

            textCellEditor : function(args){
                var $input;
                var scope = this;

                this.init = function(){
                    if(args.container.find('input').length) return;
                    var v = $.trim(args.container.text());
                    args.container.data('defaultValue', v)
                    if(!args.container.parent().data('id')){
                        var id = args.container.parent().find('.sf_admin_list_td_id a').text();
                        args.container.parent().data('id', id);
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
                            var id = args.container.parent().data('id');
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
                    if(!args.container.parent().data('id')){
                        var id = args.container.parent().find('.sf_admin_list_td_id a').text();
                        args.container.parent().data('id', id);
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
                            var id = args.container.parent().data('id');
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
                    if(!args.container.parent().data('id')){
                        var id = args.container.parent().find('.sf_admin_list_td_id a').text();
                        args.container.parent().data('id', id);
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
                            var id = args.container.parent().data('id');
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
//            console.log(id)
            if(isNaN(Number(id))) return;
            $.ajax({
                url: url + '/ajax_update',
                dataType: 'json',
                data: 'id=' + id + '&name=' + name + '&value=' + value,
                success: function(data){
                    if(data.status == 'ok'){
                        fn();
                    }else{
                        alert(data.message);
                    }
    
                }
            });
        },
        ajaxAdd : function(params, fn){
            $.ajax({
                url: url + '/ajax_add',
                dataType: 'json',
                data: params,
                success: function(data){
                    if(data.status == 'ok'){
                        fn(data);
                    }else{
                        alert(data.message);
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
        var tr_save = $('.tr_save');
        var tr_cancel = $('.tr_cancel');
        var td_tags = $('.sf_admin_list_td_tags');

        Grid.editor.trsNumber = $('.adminlist tbody tr').length;

        tr_save.live('click', function(){
            var td = $(this).parent();
            var tr = $(this).parents('tr');
            $(this).next().remove();
            $(this).remove();

            var name = tr.find('td.sf_admin_list_td_name').text();
            var channel_id = tr.find('td.sf_admin_list_td_channel').attr('rel');
            var publish = tr.find('td.sf_admin_list_td_publish img').attr('title') == 'Checked' ? 1 : 0;
            var time = tr.find('td.sf_admin_list_td_time').text();
            var date = tr.find('td.sf_admin_list_td_date').text();
            var checkbox = tr.find('.sf_admin_batch_checkbox');
            var wiki_id = tr.find('.sf_admin_list_td_wiki a').attr('href').match(/[\d]+$/)[0];

            Grid.ajaxAdd({
                name : name,
                channel_id : channel_id,
                publish : publish,
                time : time,
                date : date,
                wiki_id : wiki_id
            }, function(data){
                var a = td.find('a');
                a.text(data.id);
                a[0].href = a.attr('href').replace('{id}', data.id);
                tr.data('id', data.id);
                checkbox.val(data.id);
            });
            
        });

        tr_cancel.live('click', function(){
            $(this).parents('tr').remove();
        });

        td_name.live('click', function(){
            var name = new Grid.editor.textCellEditor({
                container: $(this)
            });
        });

        td_time.live('click', function(){
            var time = new Grid.editor.timeCellEditor({
                container: $(this)
            });
        });

        td_publish.live('click', function(){
            var publish = new Grid.editor.boolCellEditor({
                container: $(this)
            });
        });

//        td_tags.live('click', function(){
//
//        });

        var batch_add = $('#batch_add');
        var admin_list = $('#admin_list');
        batch_add.click(function(evt){
            //Grid.editor.addColumn({table: admin_list, rel: $.parseJSON(batch_add.attr('rel'))});
            $('#dialog-form-index').show();
            $('#dialog-form-template').hide();
            $('#dialog-form-action').hide();
            var id = $.parseJSON($(this).attr('rel'))['channel_id'];
            $.ajax({
                url: url.replace('program', 'program_index') + '/show_index',
                dataType: 'json',
                data: 'id=' + id,
                success: function(data){
                    if(data.code == 1){
                        $('#dialog-form').dialog({
                            height: 350,
                            width: 700,
                            modal: true,
                            title: '请选择一个模版',
                            open: function(event, ui) {
                                $('#dialog-form-index ul').html('');

                                $.each(data['msg'], function(i,v){
                                    var li = '<li><a href="#'+v['id']+'"><span>wiki_id-{wiki_id}</span><span>wiki_name-{wiki_name}</span><span>channel_id-{channel_id}</span><span>channel_name-{channel_name}</span><span>title-{title}</span></a></li>';
                                    var li = $(li.replace(/\{[\w\_]+\}/g, function(rep){
                                        var rep = rep.substr(1, rep.length-2);
                                        return v[rep];
                                    })).data('datas', v);

                                    $('#dialog-form-index ul').append(li);
                                    
                                });
                            },
                            buttons: {
                                '确定': function(){
                                    var input = $('#dialog-form-template ul li input:checked');
                                    if(input){
                                    var li = input.parents('li');
                                    var data = li.data('datas');
                                    var rel = {
                                        name: data.self.name,
                                        channel: data.parent.channel_name,
                                        channel_id: data.parent.channel_id,
                                        wiki_id: data.parent.wiki_id,
                                        wiki: data.parent.wiki_name,
                                        date: $.parseJSON(batch_add.attr('rel'))['date'],
                                        time: data.self.time
                                    };
                                    Grid.editor.addColumn({table: admin_list, rel: rel});
                                    }
                                    $(this).dialog('close');
                                }
                            }
                        });
                    }else{
                        alert(data.msg);
                    }

                }
            });
            
			
            evt.preventDefault();
        });


        $('.removeTags').live('click', function(evt){
           var id = this.href.match(/[\d]+$/);
           var span = $(this).parent();
           $.ajax({
                url: url.replace('program', 'tag_relationships') + '/ajax_delete',
                dataType: 'json',
                data: 'id=' + id,
                success: function(data){
                    if(data.status == 'ok'){
                        span.remove();
                    }else{
                        alert(data.message);
                    }

                }
            });
            evt.preventDefault();
       });

       $('#dialog-form-index li a').live('click', function(evt){
           var id = this.href.match(/[\d]+$/);
           var scope = $(this).parent();
            $.ajax({
                url: url.replace('program', 'program_template') + '/show_template',
                dataType: 'json',
                data: 'id=' + id,
                success: function(data){
                    if(data.code){
                        $('#dialog-form-index').hide();
                        $('#dialog-form-template').show();
                        $('#dialog-form-action').show();
                        $('#dialog-form-template ul').html('');

                        $.each(data['msg'], function(i,v){
                            var li = '<li><input type="radio" name="temp_radio" /><span>wiki_id-{wiki_id}</span><span>time-{time}</span><span>name-{name}</span></a></li>';
                            var li = $(li.replace(/\{[\w\_]+\}/g, function(rep){
                                var rep = rep.substr(1, rep.length-2);
                                return v[rep];
                            })).data('datas', {'self':v, 'parent': scope.data('datas')});

                            $('#dialog-form-template ul').append(li);

                        });
                    }else{
                        alert(data.msg);
                    }
                }
            })
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


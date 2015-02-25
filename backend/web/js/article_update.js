CKEDITOR.plugins.addExternal('dropdown', '/js/CKEditor_plugins/dropdown/');
CKEDITOR.plugins.addExternal('delbutton', '/js/CKEditor_plugins/delbutton/');
CKEDITOR.on('instanceCreated', function (event) {
    var editor = event.editor;

    editor.on('configLoaded', function () {
        this.config.extraPlugins = 'sourcedialog,dropdown,delbutton';
        this.config.language = 'zh-cn';
        this.config.allowedContent = true;
        this.config.toolbarGroups.push({
            name: 'digpage'
        });
        this.config.dropdowns = {
            'onClick': onDropDownClick,
            'items' : window.digpage.update_dropdown_items
        };
        this.config.delbutton = {
            'onClick' : onDelButtonClick
        };
    });
    
    editor.on('blur', function(event){
        editor = event.editor;
        if (editor.checkDirty()) {
            dataset = editor.element.$.dataset;
            id = dataset['sectionid'];
            ver = dataset['sectionver'];
            $data = $('<div>' + editor.getData() + '</div>');
            $header = $data.children('h1,h2,h3,h4,h5,h6');
            header = $header[0].outerHTML;
            $header.remove();
            content = $data.html().trim();
            $.ajax({
                url: 'http://api.dev.com/sections/' + id,
                type: 'PUT',
                data: {
                    ver: ver,
                    title: header,
                    content: content
                },
                success :function (data, text, xhr){
                    dataset['sectionver'] = parseInt(ver) + 1;
                }
            });
        }
    });
    var onDropDownClick = function (editor, index, value) {
        editor.element.$.dataset['section'+index] = value;
        dataset = editor.element.$.dataset;
        id = dataset['sectionid'];
        ver = dataset['sectionver'];
        tmp = {
            ver: ver
        };
        tmp[index] = value;
        $.ajax({
            url: 'http://api.dev.com/sections/' + id,
            type: 'PUT',
            data: tmp,
            success :function (data, text, xhr){
                    dataset['sectionver'] = parseInt(ver) + 1;
                }
        });
    };
    var onDelButtonClick = function(editor) {
        console.log(editor);
    };
});
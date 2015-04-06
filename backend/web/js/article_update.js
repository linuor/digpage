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
            id = dataset['id'];
            ver = dataset['ver'];
            $data = $('<div>' + editor.getData() + '</div>');
            $header = $data.find('h1,h2,h3,h4,h5,h6');
            header = $.trim($header.text());
            $header.remove();
            content = $data.html().trim();
            $.ajax({
                url: window.digpage.apiUrl + 'sections/' + id,
                type: 'PUT',
                data: {
                    ver: ver,
                    title: header,
                    content: content
                },
                success :function (data, text, xhr){
                    dataset['ver'] = parseInt(ver) + 1;
                }
            });
        }
    });
    var onDropDownClick = function (editor, index, value) {
        editor.element.$.dataset[index] = value;
        dataset = editor.element.$.dataset;
        id = dataset['id'];
        ver = dataset['ver'];
        tmp = {
            ver: ver
        };
        tmp[index] = value;
        $.ajax({
            url: window.digpage.apiUrl + 'sections/' + id,
            type: 'PUT',
            data: tmp,
            success :function (data, text, xhr){
                    dataset['ver'] = parseInt(ver) + 1;
                }
        });
    };
    var onDelButtonClick = function(editor) {
        if (!confirm('确定要删除这一部分内容么？')) {
            return;
        }
        dataset = editor.element.$.dataset;
        id = dataset['id'];
        ver = dataset['ver'];
        $.ajax({
            url: window.digpage.apiUrl + 'sections/' + id,
            type: 'DELETE',
            data: {
                ver:ver
            },
            success :function (data, text, xhr){
                    dataset['ver'] = parseInt(ver) + 1;
                    window.digpage.touchVer(data.next);
                    window.digpage.touchVer(data.prev);
                    editor.element.$.remove();
                }
        });
    };
});
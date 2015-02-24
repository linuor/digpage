CKEDITOR.plugins.addExternal('dropdown', '/js/CKEditor_plugins/dropdown/');
CKEDITOR.on('instanceCreated', function (event) {
    var editor = event.editor;

    editor.on('configLoaded', function () {
        this.config.extraPlugins = 'sourcedialog,dropdown';
        this.config.language = 'zh-cn';
        this.config.allowedContent = true;
        this.config.dropdowns = {
            'onClick': onClick,
            'items' : window.digpage.update_dropdown_items
        };
    });
    onClick = function (editor, index, value) {
        editor.element.$.dataset['section'+index] = value;
        console.log(editor.element.$.dataset['section'+index]);
        console.log(value);
    };
});
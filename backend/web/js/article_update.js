CKEDITOR.on('instanceCreated', function (event) {
    var editor = event.editor;

    editor.on('configLoaded', function () {
        editor.config.language = 'zh-cn',
        editor.config.allowedContent = true,
        editor.config.extraPlugins = 'sourcedialog';
    });
});
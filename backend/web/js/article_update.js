CKEDITOR.on('instanceCreated', function (event) {
    var editor = event.editor;

    editor.on('configLoaded', function () {
        editor.config.extraPlugins='sourcedialog';
    });
});
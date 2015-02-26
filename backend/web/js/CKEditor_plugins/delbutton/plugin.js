(function () {
    CKEDITOR.plugins.add('delbutton', {
        icons: 'delete',
        hidpi: true,
        init: function (editor) {
            editor.addCommand('delbutton', {
                exec: function (editor) {
                    editor.config.delbutton.onClick(editor);
                }
            });
            editor.ui.addButton('Delete', {
                label: 'Delete',
                command: 'delbutton',
                toolbar: 'digpage'
            });
        }
    });
})();
CKEDITOR.on('instanceCreated', function (event) {
    var editor = event.editor;

    editor.on('configLoaded', function () {
        editor.config.extraPlugins='sourcedialog';
//        // Remove unnecessary plugins to make the editor simpler.
//        editor.config.removePlugins = 'colorbutton,find,flash,font,' +
//                'forms,iframe,image,newpage,removeformat,' +
//                'smiley,specialchar,stylescombo,templates';
//
//        // Rearrange the layout of the toolbar.
//        editor.config.toolbarGroups = [
//            {name: 'editing', groups: ['basicstyles', 'links']},
//            {name: 'undo'},
//            {name: 'clipboard', groups: ['selection', 'clipboard']},
//            {name: 'about'}
//        ];
    });
});
//CKEDITOR.inline('editable', {
//    extraPlugins: 'sourcedialog'
//});